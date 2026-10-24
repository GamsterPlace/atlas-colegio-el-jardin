<?php

namespace App\Http\Controllers\Direccion;

use App\Http\Controllers\Controller;
use App\Models\CategoriaUniforme;
use App\Models\Estudiante;
use App\Models\ExistenciaUniforme;
use App\Models\MovimientoInventario;
use App\Models\PedidoUniforme;
use App\Models\ProductoUniforme;
use App\Models\Uniforme;
use App\Services\TiendaUniformesService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class UniformeController extends Controller
{
    public function __construct(private TiendaUniformesService $tienda) {}

    public function index()
    {
        $this->tienda->expirar();
       $uniformes = Uniforme::query()->with(['categoria_uniforme', 'tallas.existenciaUniforme'])->orderBy('nombre')->get();
        $productos = $uniformes->flatMap(fn ($u) => $u->tallas);
        $disponibles = $productos->mapWithKeys(fn ($p) => [$p->id => $this->tienda->disponibles($p->id)]);
        return view('direccion.inventario.uniformes.index', [
            'productos' => $productos,
            'uniformes' => $uniformes,
            'datosEdicion' => $uniformes->mapWithKeys(fn ($u) => [$u->id => ['categoria' => $u->categoria_uniforme_id, 'nombre' => $u->nombre, 'color' => $u->color, 'descripcion' => $u->descripcion, 'activo' => $u->activo, 'tallas' => $u->tallas->map(fn ($t) => ['id' => $t->id, 'talla' => $t->talla, 'precio' => $t->precio_referencia, 'sku' => $t->sku, 'activo' => $t->activo])->values()]]),
            'disponibles' => $disponibles,
            'categorias' => CategoriaUniforme::query()->orderBy('nombre')->get(),
            'estudiantes' => Estudiante::query()->where('estado', 'ACTIVO')->orderBy('apellidos')->orderBy('nombres')->get(),
            'responsables' => DB::table('estudiante_responsables as er')->join('responsables as r', 'r.id', '=', 'er.responsable_id')
                ->join('personas as p', 'p.id', '=', 'r.persona_id')
                ->select('er.estudiante_id', 'r.id', 'p.nombres', 'p.apellidos')
                ->whereNull('er.fecha_fin')->get(),
            'pedidos' => PedidoUniforme::query()->with(['estudiante', 'responsable.persona', 'detalles.producto', 'pago'])
                ->whereIn('estado', ['PENDIENTE', 'EN_PROCESO', 'PREPARADO'])->orderBy('created_at')->orderBy('id')->get(),
        ]);
    }

    private function validarUniforme(Request $request): array
    {
        return $request->validate([
            'categoria_uniforme_id' => ['required', 'exists:categorias_uniformes,id'],
            'nombre' => ['required', 'string', 'max:180'],
            'descripcion' => ['nullable', 'string', 'max:4000'],
            'color' => ['nullable', 'string', 'max:80'],
            'foto' => ['nullable', 'image', 'mimes:jpeg,png,webp', 'max:4096'],
            'tallas' => ['required', 'array', 'min:1', 'max:60'],
            'tallas.*.id' => ['nullable', 'integer'],
            'tallas.*.talla' => ['required', 'string', 'max:40'],
            'tallas.*.precio' => ['required', 'numeric', 'min:0', 'max:9999999'],
            'tallas.*.stock_inicial' => ['nullable', 'integer', 'min:0', 'max:1000000'],
        ]);
    }

    private function comprobarTallasUnicas(array $tallas): void
    {
        $normalizadas = array_map(fn ($t) => mb_strtoupper(trim($t['talla'])), $tallas);
        if (count($normalizadas) !== count(array_unique($normalizadas))) {
            throw ValidationException::withMessages(['tallas' => 'No puedes registrar una misma talla dos veces para el mismo uniforme.']);
        }
    }

    private function skuAutomatico(): string
    {
        do {
            $sku = 'ATLAS-UNI-'.strtoupper(Str::random(12));
        } while (ProductoUniforme::query()->where('sku', $sku)->exists());
        return $sku;
    }

    private function crearTalla(Uniforme $uniforme, array $talla, int $stockInicial = 0): void
    {
        $variante = ProductoUniforme::query()->create([
            'uniforme_id' => $uniforme->id,
            'categoria_uniforme_id' => $uniforme->categoria_uniforme_id,
            'sku' => $this->skuAutomatico(),
            'nombre' => $uniforme->nombre,
            'talla' => trim($talla['talla']),
            'color' => $uniforme->color,
            'precio_referencia' => $talla['precio'],
            'activo' => $uniforme->activo,
        ]);
        ExistenciaUniforme::query()->create([
            'producto_uniforme_id' => $variante->id,
            'cantidad' => $stockInicial,
            'stock_minimo' => 2,
        ]);
        if ($stockInicial > 0) $this->movimiento($variante->id, $stockInicial, 'Stock inicial de presentación');
    }

    private function guardarFoto(Request $request): ?string
    {
        if (!$request->hasFile('foto')) return null;
        $dir = public_path('inventario/uniformes');
        File::ensureDirectoryExists($dir);
        $file = $request->file('foto');
        $nombre = Str::uuid().'.'.$file->extension();
        $file->move($dir, $nombre);
        return 'inventario/uniformes/'.$nombre;
    }

    public function crear(Request $request): RedirectResponse
    {
        $data = $this->validarUniforme($request);
        $this->comprobarTallasUnicas($data['tallas']);
        $path = $this->guardarFoto($request);
        try {
            DB::transaction(function () use ($data, $path) {
                $uniforme = Uniforme::query()->create([
                    'categoria_uniforme_id' => $data['categoria_uniforme_id'],
                    'nombre' => $data['nombre'],
                    'descripcion' => $data['descripcion'] ?? null,
                    'color' => $data['color'] ?? null,
                    'foto_path' => $path,
                    'activo' => true,
                ]);
                foreach ($data['tallas'] as $talla) {
                    $this->crearTalla($uniforme, $talla, (int) ($talla['stock_inicial'] ?? 0));
                }
            });
        } catch (\Throwable $e) {
            if ($path) File::delete(public_path($path));
            throw $e;
        }
        return back()->with('success', 'Uniforme registrado con todas sus tallas y SKU automáticos.');
    }

    public function actualizar(Request $request, Uniforme $uniforme): RedirectResponse
    {
        $data = $this->validarUniforme($request);
        $this->comprobarTallasUnicas($data['tallas']);
        $actuales = $uniforme->tallas()->get()->keyBy('id');
        $idsEnviados = collect($data['tallas'])->pluck('id')->filter()->map(fn ($id) => (int) $id);
        if ($idsEnviados->count() !== $idsEnviados->unique()->count() ||
            $idsEnviados->diff($actuales->keys())->isNotEmpty() ||
            $actuales->keys()->diff($idsEnviados)->isNotEmpty()) {
            throw ValidationException::withMessages(['tallas' => 'No se permite eliminar, duplicar ni sustituir tallas existentes. Puedes agregar nuevas tallas.']);
        }
        $path = $this->guardarFoto($request);
        $anterior = $uniforme->foto_path;
        try {
            DB::transaction(function () use ($data, $uniforme, $path, $actuales) {
                $uniforme->update([
                    'categoria_uniforme_id' => $data['categoria_uniforme_id'],
                    'nombre' => $data['nombre'],
                    'descripcion' => $data['descripcion'] ?? null,
                    'color' => $data['color'] ?? null,
                    'foto_path' => $path ?: $uniforme->foto_path,
                ]);
                foreach ($data['tallas'] as $talla) {
                    if (!empty($talla['id'])) {
                        $variante = $actuales->get((int) $talla['id']);
                        $variante->update([
                            'categoria_uniforme_id' => $uniforme->categoria_uniforme_id,
                            'nombre' => $uniforme->nombre,
                            'color' => $uniforme->color,
                            'talla' => trim($talla['talla']),
                            'precio_referencia' => $talla['precio'],
                        ]);
                    } else {
                        // En edición, el stock de una nueva talla inicia en cero.
                        // Los ingresos se realizan únicamente desde su módulo.
                        $this->crearTalla($uniforme, $talla);
                    }
                }
            });
        } catch (\Throwable $e) {
            if ($path) File::delete(public_path($path));
            throw $e;
        }
        if ($path && $anterior && str_starts_with($anterior, 'inventario/uniformes/')) File::delete(public_path($anterior));
        return back()->with('success', 'Uniforme y tallas actualizados sin modificar existencias ni SKU.');
    }

    public function estado(Uniforme $uniforme): RedirectResponse
    {
        DB::transaction(function () use ($uniforme) {
            $nuevoEstado = !$uniforme->activo;
            $uniforme->update(['activo' => $nuevoEstado]);
            $uniforme->tallas()->update(['activo' => $nuevoEstado]);
        });
        return back()->with('success', $uniforme->activo ? 'Uniforme reactivado.' : 'Uniforme descontinuado.');
    }

    public function ingreso(Request $request): RedirectResponse
    {
        $data = $request->validate(['cantidades' => ['required', 'array'], 'cantidades.*' => ['nullable', 'integer', 'min:0', 'max:1000000']]);
        $cantidades = collect($data['cantidades'])->filter(fn ($n) => (int)$n > 0);
        if ($cantidades->isEmpty()) throw ValidationException::withMessages(['cantidades' => 'Ingresa al menos una cantidad mayor a cero.']);
        DB::transaction(function () use ($cantidades) {
            foreach ($cantidades as $id => $cantidad) {
                $producto = ProductoUniforme::query()->findOrFail((int)$id);
                $existencia = ExistenciaUniforme::query()->where('producto_uniforme_id', $producto->id)->lockForUpdate()->first();
                if (!$existencia) $existencia = ExistenciaUniforme::query()->create(['producto_uniforme_id' => $producto->id, 'cantidad' => 0, 'stock_minimo' => 2]);
                $existencia->increment('cantidad', (int)$cantidad);
                $this->movimiento($producto->id, (int)$cantidad, 'Ingreso administrativo de uniformes');
            }
        });
        return back()->with('success', 'Ingresos registrados y existencias actualizadas.');
    }

    private function movimiento(int $id, int $cantidad, string $motivo, ?string $referencia = null, string $tipo = 'ENTRADA'): void
    {
        MovimientoInventario::query()->create(['producto_uniforme_id' => $id, 'tipo' => $tipo, 'cantidad' => $cantidad,
            'motivo' => $motivo, 'referencia' => $referencia, 'registrado_por' => Auth::id(), 'fecha_movimiento' => now()]);
    }

    public function pedido(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'estudiante_id' => ['required', 'exists:estudiantes,id'],
            'responsable_id' => ['required', 'exists:responsables,id'],
            'cantidades' => ['required', 'array'], 'cantidades.*' => ['nullable', 'integer', 'min:0', 'max:1000'],
            'observaciones' => ['nullable', 'string', 'max:2000'],
        ]);
        $valido = DB::table('estudiante_responsables')->where('estudiante_id', $data['estudiante_id'])
            ->where('responsable_id', $data['responsable_id'])->whereNull('fecha_fin')->exists();
        if (!$valido) throw ValidationException::withMessages(['responsable_id' => 'El responsable no está vinculado actualmente al estudiante.']);
        $cantidades = collect($data['cantidades'])->filter(fn ($n) => (int)$n > 0);
        if ($cantidades->isEmpty()) throw ValidationException::withMessages(['cantidades' => 'Selecciona al menos una prenda.']);
        DB::transaction(function () use ($data, $cantidades) {
            $productos = ProductoUniforme::query()->whereIn('id', $cantidades->keys()->map(fn ($v) => (int)$v))->orderBy('id')->lockForUpdate()->get()->keyBy('id');
            $existencias = ExistenciaUniforme::query()->whereIn('producto_uniforme_id', $productos->keys())->orderBy('producto_uniforme_id')->lockForUpdate()->get();
            $detalles = []; $total = 0;
            foreach ($cantidades as $id => $cantidad) {
                $p = $productos->get((int)$id);
                if (!$p || !$p->activo) throw ValidationException::withMessages(['cantidades' => 'Se seleccionó un uniforme no disponible.']);
                $this->tienda->comprobarStock($p->id, (int)$cantidad);
                $precio = (float)$p->precio_referencia;
                $subtotal = round($precio * (int)$cantidad, 2);
                $detalles[] = ['producto_uniforme_id' => $p->id, 'cantidad' => (int)$cantidad, 'precio_unitario' => $precio, 'subtotal' => $subtotal];
                $total += $subtotal;
            }
            $pedido = PedidoUniforme::query()->create([
                'numero' => 'UNI-'.strtoupper((string)Str::ulid()), 'estudiante_id' => $data['estudiante_id'],
                'responsable_id' => $data['responsable_id'], 'creado_por' => Auth::id(), 'origen' => 'DIRECCION',
                'estado' => 'PENDIENTE', 'estado_pago' => 'PENDIENTE', 'total' => round($total, 2),
                'observaciones' => $data['observaciones'] ?? null, 'vence_at' => now()->addHours(72),
            ]);
            $pedido->detalles()->createMany($detalles);
            $this->tienda->evento($pedido, 'CREADO', 'Pedido registrado desde Dirección.', Auth::id());
        });
        return back()->with('success', 'Pedido registrado y reservado por 72 horas.');
    }

    public function procesar(PedidoUniforme $pedido): RedirectResponse
    {
        DB::transaction(function () use ($pedido) {
            $pedido = PedidoUniforme::query()->lockForUpdate()->findOrFail($pedido->id);
            $this->vigente($pedido, 'PENDIENTE');
            $pedido->update(['estado' => 'EN_PROCESO', 'procesado_por' => Auth::id(), 'procesado_at' => now()]);
            $this->tienda->evento($pedido, 'EN_PROCESO', null, Auth::id());
        });
        return back()->with('success', 'Pedido en proceso.')->with('abrir_pedido', $pedido->id);
    }

    public function preparado(PedidoUniforme $pedido): \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
    {
        DB::transaction(function () use ($pedido) {
            $pedido = PedidoUniforme::query()->lockForUpdate()->findOrFail($pedido->id);
            $this->vigente($pedido, 'EN_PROCESO');
            $pedido->update(['estado' => 'PREPARADO', 'preparado_por' => Auth::id(), 'preparado_at' => now()]);
            $this->tienda->evento($pedido, 'PREPARADO', 'Nota media carta disponible para impresión.', Auth::id());
        });
        if (request()->expectsJson()) {
            return response()->json([
                'message' => 'Pedido preparado correctamente.',
                'etiqueta_url' => route('direccion.inventario.uniformes.etiqueta', $pedido->id),
            ]);
        }
        return back()->with('success', 'Pedido preparado correctamente. La etiqueta está disponible para imprimir.');
    }

    public function pago(Request $request, PedidoUniforme $pedido): RedirectResponse
    {
        $data = $request->validate(['metodo' => ['required', Rule::in(['EFECTIVO', 'DEPOSITO'])],
            'referencia_deposito' => ['required_if:metodo,DEPOSITO', 'nullable', 'string', 'max:120'],
            'fecha_deposito' => ['required_if:metodo,DEPOSITO', 'nullable', 'date', 'before_or_equal:today']]);
        DB::transaction(function () use ($pedido, $data) {
            $pedido = PedidoUniforme::query()->lockForUpdate()->findOrFail($pedido->id);
            if ($pedido->estado === 'ANULADO' || $pedido->estado === 'ENTREGADO' || $pedido->estado_pago !== 'PENDIENTE')
                throw ValidationException::withMessages(['pago' => 'Este pedido no admite un nuevo pago.']);
            if ($pedido->vence_at->isPast()) throw ValidationException::withMessages(['pago' => 'Pedido vencido. Requiere revisión de Dirección.']);
            DB::table('pagos_uniformes')->insert(['pedido_uniforme_id' => $pedido->id, 'metodo' => $data['metodo'],
                'monto' => $pedido->total, 'referencia_deposito' => $data['metodo'] === 'DEPOSITO' ? $data['referencia_deposito'] : null,
                'fecha_deposito' => $data['metodo'] === 'DEPOSITO' ? $data['fecha_deposito'] : null,
                'registrado_por' => Auth::id(), 'created_at' => now(), 'updated_at' => now()]);
            $pedido->update(['estado_pago' => 'PAGADO']);
            $this->tienda->evento($pedido, 'PAGO_REGISTRADO', $data['metodo'], Auth::id());
        });
        return back()->with('success', 'Pago registrado correctamente.');
    }

    public function entregar(Request $request, PedidoUniforme $pedido): RedirectResponse
    {
        $data = $request->validate(['recibido_por' => ['required', 'string', 'max:180']]);
        DB::transaction(function () use ($pedido, $data) {
            $pedido = PedidoUniforme::query()->lockForUpdate()->with('detalles')->findOrFail($pedido->id);
            $this->vigente($pedido, 'PREPARADO');
            if ($pedido->estado_pago !== 'PAGADO' || !$pedido->pago()->exists())
                throw ValidationException::withMessages(['pago' => 'Primero registra el pago en efectivo o verifica la boleta original de depósito.']);
            foreach ($pedido->detalles->sortBy('producto_uniforme_id') as $detalle) {
                $stock = ExistenciaUniforme::query()->where('producto_uniforme_id', $detalle->producto_uniforme_id)->lockForUpdate()->first();
                if (!$stock || $stock->cantidad < $detalle->cantidad)
                    throw ValidationException::withMessages(['stock' => 'Inventario físico insuficiente. No se puede entregar.']);
                $stock->decrement('cantidad', $detalle->cantidad);
                $this->movimiento($detalle->producto_uniforme_id, $detalle->cantidad, 'Entrega de pedido de uniformes', $pedido->numero, 'SALIDA');
            }
            $pedido->update(['estado' => 'ENTREGADO', 'entregado_por' => Auth::id(), 'entregado_at' => now(), 'recibido_por' => $data['recibido_por']]);
            $this->tienda->evento($pedido, 'ENTREGADO', 'Recibido por: '.$data['recibido_por'], Auth::id());
        });
        return back()->with('success', 'Pedido entregado y retirado de la lista activa.');
    }

    public function anular(Request $request, PedidoUniforme $pedido): RedirectResponse
    {
        $data = $request->validate(['motivo' => ['required', 'string', 'max:255']]);
        DB::transaction(function () use ($pedido, $data) {
            $pedido = PedidoUniforme::query()->lockForUpdate()->findOrFail($pedido->id);
            if (!in_array($pedido->estado, ['PENDIENTE', 'EN_PROCESO', 'PREPARADO'], true))
                throw ValidationException::withMessages(['pedido' => 'Este pedido no se puede anular.']);
            $pedido->update(['estado' => 'ANULADO', 'motivo_anulacion' => $data['motivo'], 'anulado_por' => Auth::id(), 'anulado_at' => now()]);
            $this->tienda->evento($pedido, 'ANULADO_MANUAL', $data['motivo'], Auth::id());
        });
        return back()->with('success', 'Pedido anulado. Si estaba preparado, devolver las prendas al inventario disponible.');
    }

    private function vigente(PedidoUniforme $pedido, string $estado): void
    {
        if ($pedido->estado !== $estado || $pedido->vence_at->lessThanOrEqualTo(now()))
            throw ValidationException::withMessages(['pedido' => 'El pedido cambió de estado o venció. Actualiza la pantalla.']);
    }

    public function etiqueta(PedidoUniforme $pedido)
    {
        if (!in_array($pedido->estado, ['PREPARADO', 'ENTREGADO'], true)) abort(404);
        $pedido->load(['estudiante', 'responsable.persona', 'detalles.producto']);
        return Pdf::loadView('direccion.inventario.uniformes.pdf.etiqueta', compact('pedido'))
            ->setPaper([0, 0, 612, 396], 'landscape')->stream('identificacion-'.$pedido->numero.'.pdf');
    }

    public function comprobante(PedidoUniforme $pedido)
    {
        $pedido->load(['estudiante', 'responsable.persona', 'detalles.producto']);
        return Pdf::loadView('direccion.inventario.uniformes.pdf.comprobante', compact('pedido'))
            ->setPaper('letter')->stream('pedido-'.$pedido->numero.'.pdf');
    }
}
