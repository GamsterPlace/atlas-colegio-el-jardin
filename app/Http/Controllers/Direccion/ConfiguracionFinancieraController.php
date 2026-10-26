<?php

namespace App\Http\Controllers\Direccion;

use App\Http\Controllers\Controller;
use App\Models\BitacoraAuditoria;
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
            'cicloSeleccionado' => $ciclos->firstWhere('id', $cicloId),
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

    private function registrarAuditoria(Request $request, string $accion, string $entidad, int $id, ?array $antes, array $despues): void
    {
        BitacoraAuditoria::query()->create([
            'usuario_id' => auth::id(),
            'accion' => $accion,
            'entidad' => $entidad,
            'entidad_id' => $id,
            'datos_anteriores' => $antes,
            'datos_nuevos' => $despues,
            'motivo' => 'Actualización de configuración financiera autorizada por Dirección.',
            'ip' => $request->ip(),
            'fecha_evento' => now('America/Guatemala'),
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
        DB::transaction(function () use ($data, $request) {
            $concepto = ConceptoCobro::query()->create($data);
            $this->registrarAuditoria($request, 'CREAR', 'conceptos_cobro', $concepto->id, null, $concepto->getAttributes());
        });
        return back()->with('success', 'Concepto de cobro registrado.');
    }

    public function actualizarConcepto(Request $request, ConceptoCobro $concepto): RedirectResponse
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:150'],
            'activo' => ['required', 'boolean'],
        ]);
        DB::transaction(function () use ($concepto, $data, $request) {
            $antes = $concepto->getAttributes();
            $concepto->update($data);
            $this->registrarAuditoria($request, 'ACTUALIZAR', 'conceptos_cobro', $concepto->id, $antes, $concepto->fresh()->getAttributes());
        });
        return back()->with('success', 'Concepto actualizado.');
    }

    private function datosColegiatura(Request $request): array
    {
        return $request->validate([
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
        ], [
            'vigente_hasta.after_or_equal' => 'La fecha final debe ser igual o posterior a la fecha inicial.',
        ]);
    }

    private function validarColegiatura(array $data, ?int $excluirId = null): array
    {
        $concepto = ConceptoCobro::findOrFail($data['concepto_cobro_id']);
        if (!$concepto->activo || $concepto->tipo !== 'COLEGIATURA') {
            throw ValidationException::withMessages(['concepto_cobro_id' => 'Seleccione un concepto activo de colegiatura.']);
        }
        if (!empty($data['grado_id']) && !Grado::whereKey($data['grado_id'])->where('nivel_id', $data['nivel_id'])->exists()) {
            throw ValidationException::withMessages(['grado_id' => 'El grado no pertenece al nivel seleccionado.']);
        }
        $ciclo = CicloEscolar::findOrFail($data['ciclo_escolar_id']);
        $inicio = $ciclo->fecha_inicio->toDateString();
        $fin = $ciclo->fecha_fin->toDateString();
        $desde = $data['vigente_desde'] ?? $inicio;
        $hasta = $data['vigente_hasta'] ?? $fin;
        if ($desde > $hasta) {
            throw ValidationException::withMessages(['vigente_hasta' => 'La fecha final debe ser igual o posterior a la fecha inicial.']);
        }
        if ($desde < $inicio || $hasta > $fin) {
            throw ValidationException::withMessages(['vigente_desde' => "La vigencia debe estar comprendida entre {$inicio} y {$fin}, fechas del ciclo seleccionado."]);
        }
        $conflicto = ConfiguracionColegiatura::query()
            ->where('ciclo_escolar_id', $ciclo->id)
            ->where('concepto_cobro_id', $concepto->id)
            ->where('nivel_id', $data['nivel_id'])
            ->where('grado_id', $data['grado_id'] ?? null)
            ->when($excluirId, fn ($q) => $q->where('id', '!=', $excluirId))
            ->where(fn ($q) => $q->whereNull('vigente_desde')->orWhereDate('vigente_desde', '<=', $hasta))
            ->where(fn ($q) => $q->whereNull('vigente_hasta')->orWhereDate('vigente_hasta', '>=', $desde))
            ->exists();
        if ($conflicto) {
            throw ValidationException::withMessages(['vigente_desde' => 'Ya existe una configuración para el mismo concepto, nivel y grado con fechas superpuestas.']);
        }
        $data['grado_id'] = $data['grado_id'] ?? null;
        $data['vigente_desde'] = $desde;
        $data['vigente_hasta'] = $hasta;
        $data['meses_cobro'] = array_values(array_map('intval', $data['meses_cobro'] ?? []));
        return $data;
    }

    public function guardarColegiatura(Request $request): RedirectResponse
    {
        $data = $this->validarColegiatura($this->datosColegiatura($request));
        DB::transaction(function () use ($data, $request) {
            $registro = ConfiguracionColegiatura::query()->create($data);
            $this->registrarAuditoria($request, 'CREAR', 'configuraciones_colegiaturas', $registro->id, null, $registro->getAttributes());
        });
        return back()->with('success', 'Configuración de colegiatura guardada.');
    }

    public function actualizarColegiatura(Request $request, ConfiguracionColegiatura $colegiatura): RedirectResponse
    {
        $data = $this->datosColegiatura($request);
        if ((int) $data['ciclo_escolar_id'] !== (int) $colegiatura->ciclo_escolar_id) {
            throw ValidationException::withMessages(['ciclo_escolar_id' => 'No se permite trasladar una configuración a otro ciclo escolar.']);
        }
        $data = $this->validarColegiatura($data, $colegiatura->id);
        DB::transaction(function () use ($colegiatura, $data, $request) {
            $antes = $colegiatura->getAttributes();
            $colegiatura->update($data);
            $this->registrarAuditoria($request, 'ACTUALIZAR', 'configuraciones_colegiaturas', $colegiatura->id, $antes, $colegiatura->fresh()->getAttributes());
        });
        return back()->with('success', 'Colegiatura actualizada. Las obligaciones ya emitidas no fueron modificadas.');
    }

    private function datosMora(Request $request): array
    {
        return $request->validate([
            'ciclo_escolar_id' => ['required', 'integer', 'exists:ciclos_escolares,id'],
            'concepto_cobro_id' => ['required', 'integer', 'exists:conceptos_cobro,id'],
            'tipo' => ['required', Rule::in(['FIJO', 'PORCENTAJE'])],
            'valor' => ['required', 'numeric', 'min:0', 'max:9999999999'],
            'dias_gracia' => ['required', 'integer', 'between:0,65535'],
            'periodicidad' => ['required', Rule::in(['UNICA', 'DIARIA', 'MENSUAL'])],
            'vigente_desde' => ['required', 'date'],
            'vigente_hasta' => ['nullable', 'date', 'after_or_equal:vigente_desde'],
        ], ['vigente_hasta.after_or_equal' => 'La fecha final debe ser igual o posterior a la inicial.']);
    }

    private function validarMora(array $data, ?int $excluirId = null): array
    {
        if ($data['tipo'] === 'PORCENTAJE' && (float) $data['valor'] > 100) {
            throw ValidationException::withMessages(['valor' => 'El porcentaje no puede superar 100.']);
        }
        $ciclo = CicloEscolar::findOrFail($data['ciclo_escolar_id']);
        $inicio = $ciclo->fecha_inicio->toDateString();
        $finCiclo = $ciclo->fecha_fin->toDateString();
        $desde = $data['vigente_desde'];
        $hasta = $data['vigente_hasta'] ?? $finCiclo;
        if ($desde > $hasta) {
            throw ValidationException::withMessages(['vigente_hasta' => 'La fecha final debe ser igual o posterior a la inicial.']);
        }
        if ($desde < $inicio || $hasta > $finCiclo) {
            throw ValidationException::withMessages(['vigente_desde' => "La vigencia de mora debe estar comprendida entre {$inicio} y {$finCiclo}."]);
        }
        if (!ConceptoCobro::whereKey($data['concepto_cobro_id'])->where('activo', true)->exists()) {
            throw ValidationException::withMessages(['concepto_cobro_id' => 'Seleccione un concepto de cobro activo.']);
        }
        $conflicto = ConfiguracionMora::query()
            ->where('ciclo_escolar_id', $data['ciclo_escolar_id'])
            ->where('concepto_cobro_id', $data['concepto_cobro_id'])
            ->when($excluirId, fn ($q) => $q->where('id', '!=', $excluirId))
            ->whereDate('vigente_desde', '<=', $hasta)
            ->where(fn ($q) => $q->whereNull('vigente_hasta')->orWhereDate('vigente_hasta', '>=', $desde))
            ->exists();
        if ($conflicto) {
            throw ValidationException::withMessages(['vigente_desde' => 'Ya existe una regla de mora superpuesta para este concepto.']);
        }
        return $data;
    }

    public function guardarMora(Request $request): RedirectResponse
    {
        $data = $this->validarMora($this->datosMora($request));
        $data['aprobado_por'] = auth::id();
        DB::transaction(function () use ($data, $request) {
            $registro = ConfiguracionMora::query()->create($data);
            $this->registrarAuditoria($request, 'CREAR', 'configuraciones_mora', $registro->id, null, $registro->getAttributes());
        });
        return back()->with('success', 'Regla de mora registrada. No se generaron recargos retroactivos.');
    }

    public function actualizarMora(Request $request, ConfiguracionMora $mora): RedirectResponse
    {
        $data = $this->datosMora($request);
        if ((int) $data['ciclo_escolar_id'] !== (int) $mora->ciclo_escolar_id) {
            throw ValidationException::withMessages(['ciclo_escolar_id' => 'No se permite trasladar una regla de mora a otro ciclo.']);
        }
        $data = $this->validarMora($data, $mora->id);
        $data['aprobado_por'] = auth::id();
        DB::transaction(function () use ($mora, $data, $request) {
            $antes = $mora->getAttributes();
            $mora->update($data);
            $this->registrarAuditoria($request, 'ACTUALIZAR', 'configuraciones_mora', $mora->id, $antes, $mora->fresh()->getAttributes());
        });
        return back()->with('success', 'Regla de mora actualizada. Los recargos existentes no fueron modificados.');
    }
}
