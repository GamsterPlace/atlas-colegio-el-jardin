<?php

namespace App\Http\Controllers\Direccion;

use App\Http\Controllers\Controller;
use App\Models\CicloEscolar;
use App\Models\ConceptoCobro;
use App\Models\ConfiguracionColegiatura;
use App\Models\ConfiguracionMora;
use App\Models\Grado;
use App\Models\Nivel;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class ConfiguracionFinancieraController extends Controller
{
    public function index(Request $request): View
    {
        $request->validate(['ciclo_escolar_id' => ['nullable', 'integer', 'exists:ciclos_escolares,id']]);
        $ciclos = CicloEscolar::query()->orderByDesc('fecha_inicio')->get();
        $cicloId = $request->filled('ciclo_escolar_id')
            ? (int) $request->input('ciclo_escolar_id')
            : (CicloEscolar::vigente()?->id ?? $ciclos->first()?->id);

        return view('direccion.finanzas.configuracion.index', [
            'ciclos' => $ciclos,
            'cicloId' => $cicloId,
            'conceptos' => ConceptoCobro::query()->orderBy('nombre')->get(),
            'niveles' => Nivel::query()->orderBy('orden')->get(),
            'grados' => Grado::query()->orderBy('orden')->get(),
            'colegiaturas' => ConfiguracionColegiatura::query()
                ->with(['concepto_cobro', 'nivel', 'grado'])
                ->when($cicloId, fn ($q) => $q->where('ciclo_escolar_id', $cicloId))
                ->orderByDesc('id')->get(),
            'moras' => ConfiguracionMora::query()
                ->with('concepto_cobro')
                ->when($cicloId, fn ($q) => $q->where('ciclo_escolar_id', $cicloId))
                ->orderByDesc('vigente_desde')->orderByDesc('id')->get(),
        ]);
    }

    public function guardarConcepto(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'codigo' => ['required', 'string', 'max:50', 'regex:/^[A-Za-z0-9_-]+$/', 'unique:conceptos_cobro,codigo'],
            'nombre' => ['required', 'string', 'max:150'],
            'tipo' => ['required', Rule::in(['INSCRIPCION', 'COLEGIATURA', 'OTRO'])],
        ]);
        $data['codigo'] = strtoupper($data['codigo']);
        $data['activo'] = true;
        ConceptoCobro::query()->create($data);
        return back()->with('success', 'Concepto de cobro registrado.');
    }

    public function actualizarConcepto(Request $request, ConceptoCobro $concepto): RedirectResponse
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:150'],
            'activo' => ['required', 'boolean'],
        ]);
        $concepto->update($data);
        return back()->with('success', 'Concepto actualizado.');
    }

    public function guardarColegiatura(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'ciclo_escolar_id' => ['required', 'integer', 'exists:ciclos_escolares,id'],
            'concepto_cobro_id' => ['required', 'integer', 'exists:conceptos_cobro,id'],
            'nivel_id' => ['required', 'integer', 'exists:niveles,id'],
            'grado_id' => ['nullable', 'integer', 'exists:grados,id'],
            'monto' => ['required', 'numeric', 'min:0', 'max:9999999999'],
            'meses_cobro' => ['nullable', 'array'],
            'meses_cobro.*' => ['integer', 'between:1,12', 'distinct'],
            'dia_vencimiento' => ['nullable', 'integer', 'between:1,31'],
            'vigente_desde' => ['nullable', 'date'],
            'vigente_hasta' => ['nullable', 'date', 'after_or_equal:vigente_desde'],
        ]);
        $concepto = ConceptoCobro::findOrFail($data['concepto_cobro_id']);
        if (!$concepto->activo || $concepto->tipo !== 'COLEGIATURA') {
            throw ValidationException::withMessages(['concepto_cobro_id' => 'Seleccione un concepto activo de colegiatura.']);
        }
        if (!empty($data['grado_id']) && !Grado::whereKey($data['grado_id'])->where('nivel_id', $data['nivel_id'])->exists()) {
            throw ValidationException::withMessages(['grado_id' => 'El grado no pertenece al nivel seleccionado.']);
        }
        $ciclo = CicloEscolar::findOrFail($data['ciclo_escolar_id']);
        $desde = $data['vigente_desde'] ?? $ciclo->fecha_inicio->toDateString();
        $hasta = $data['vigente_hasta'] ?? $ciclo->fecha_fin->toDateString();
        if ($desde < $ciclo->fecha_inicio->toDateString() || $hasta > $ciclo->fecha_fin->toDateString()) {
            throw ValidationException::withMessages(['vigente_desde' => 'La vigencia debe estar dentro del ciclo escolar.']);
        }
        $gradoId = $data['grado_id'] ?? null;
        $conflicto = ConfiguracionColegiatura::query()
            ->where('ciclo_escolar_id', $ciclo->id)
            ->where('concepto_cobro_id', $concepto->id)
            ->where('nivel_id', $data['nivel_id'])
            ->where('grado_id', $gradoId)
            ->where(function ($q) use ($desde, $hasta) {
                $q->where(function ($q) use ($hasta) {
                    $q->whereNull('vigente_desde')->orWhereDate('vigente_desde', '<=', $hasta);
                })->where(function ($q) use ($desde) {
                    $q->whereNull('vigente_hasta')->orWhereDate('vigente_hasta', '>=', $desde);
                });
            })->exists();
        if ($conflicto) {
            throw ValidationException::withMessages(['vigente_desde' => 'Ya existe una configuración para este nivel, grado y concepto con vigencia superpuesta.']);
        }
        $data['grado_id'] = $gradoId;
        $data['vigente_desde'] = $desde;
        $data['vigente_hasta'] = $hasta;
        $data['meses_cobro'] = array_values(array_map('intval', $data['meses_cobro'] ?? []));
        ConfiguracionColegiatura::query()->create($data);
        return back()->with('success', 'Configuración de colegiatura guardada.');
    }

    public function guardarMora(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'ciclo_escolar_id' => ['required', 'integer', 'exists:ciclos_escolares,id'],
            'concepto_cobro_id' => ['required', 'integer', 'exists:conceptos_cobro,id'],
            'tipo' => ['required', Rule::in(['FIJO', 'PORCENTAJE'])],
            'valor' => ['required', 'numeric', 'min:0', 'max:9999999999'],
            'dias_gracia' => ['required', 'integer', 'between:0,65535'],
            'periodicidad' => ['required', Rule::in(['UNICA', 'DIARIA', 'MENSUAL'])],
            'vigente_desde' => ['required', 'date'],
            'vigente_hasta' => ['nullable', 'date', 'after_or_equal:vigente_desde'],
        ]);
        if ($data['tipo'] === 'PORCENTAJE' && $data['valor'] > 100) {
            throw ValidationException::withMessages(['valor' => 'El porcentaje no puede superar 100.']);
        }
        $ciclo = CicloEscolar::findOrFail($data['ciclo_escolar_id']);
        $fin = $data['vigente_hasta'] ?? $ciclo->fecha_fin->toDateString();
        if ($data['vigente_desde'] < $ciclo->fecha_inicio->toDateString() || $fin > $ciclo->fecha_fin->toDateString()) {
            throw ValidationException::withMessages(['vigente_desde' => 'La vigencia de la mora debe estar dentro del ciclo escolar.']);
        }
        if (!ConceptoCobro::whereKey($data['concepto_cobro_id'])->where('activo', true)->exists()) {
            throw ValidationException::withMessages(['concepto_cobro_id' => 'Seleccione un concepto de cobro activo.']);
        }
        $conflicto = ConfiguracionMora::query()
            ->where('ciclo_escolar_id', $data['ciclo_escolar_id'])
            ->where('concepto_cobro_id', $data['concepto_cobro_id'])
            ->whereDate('vigente_desde', '<=', $fin)
            ->where(fn ($q) => $q->whereNull('vigente_hasta')->orWhereDate('vigente_hasta', '>=', $data['vigente_desde']))
            ->exists();
        if ($conflicto) {
            throw ValidationException::withMessages(['vigente_desde' => 'Ya existe una regla de mora vigente para este concepto en las fechas indicadas.']);
        }
        $data['aprobado_por'] = auth::id();
        ConfiguracionMora::query()->create($data);
        return back()->with('success', 'Regla de mora autorizada y registrada. No se generaron recargos retroactivos.');
    }
}
