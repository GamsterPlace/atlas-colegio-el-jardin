<?php

namespace App\Http\Controllers\Administrador;

use App\Http\Controllers\Controller;
use App\Models\BitacoraAuditoria;
use App\Models\Persona;
use App\Models\Rol;
use App\Models\Usuario;
use App\Services\GeneracionCredencialesUsuarioService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class UsuarioController extends Controller
{
    private const ROLES = ['ADMINISTRADOR', 'DIRECCION', 'SECRETARIA', 'DOCENTE', 'PADRE_FAMILIA'];

    public function index(Request $request): View
    {
        $datos = $request->validate([
            'buscar' => ['nullable', 'string', 'max:120'],
            'rol' => ['nullable', 'integer', 'exists:roles,id'],
            'estado' => ['nullable', Rule::in(['activo', 'inactivo'])],
        ]);
        $buscar = trim($datos['buscar'] ?? '');
        $usuarios = Usuario::query()->with(['persona', 'roles'])
            ->when($buscar !== '', function ($q) use ($buscar) {
                $q->where(function ($q) use ($buscar) {
                    $q->where('nombre_usuario', 'like', "%{$buscar}%")
                        ->orWhere('email', 'like', "%{$buscar}%")
                        ->orWhereHas('persona', function ($p) use ($buscar) {
                            $p->where('nombres', 'like', "%{$buscar}%")
                                ->orWhere('apellidos', 'like', "%{$buscar}%")
                                ->orWhere('cui', 'like', "%{$buscar}%")
                                ->orWhereRaw("CONCAT(nombres, ' ', apellidos) LIKE ?", ["%{$buscar}%"]);
                        });
                });
            })
            ->when(!empty($datos['rol']), fn ($q) => $q->whereHas('roles', fn ($r) => $r->where('roles.id', $datos['rol'])))
            ->when(isset($datos['estado']), fn ($q) => $q->where('activo', $datos['estado'] === 'activo'))
            ->orderBy('nombre_usuario')->paginate(15)->withQueryString();
        $roles = Rol::query()->whereIn('codigo', self::ROLES)->orderBy('nombre')->get();
        return view('administrador.usuarios.index', compact('usuarios', 'roles'));
    }

    public function create(Request $request): View
    {
        $datos = $request->validate(['cui' => ['nullable', 'digits:13']]);
        $personaExistente = !empty($datos['cui']) ? Persona::query()->where('cui', $datos['cui'])->first() : null;
        $usuarioExistente = $personaExistente ? Usuario::query()->where('persona_id', $personaExistente->id)->first() : null;
        $roles = $this->rolesDisponibles();
        return view('administrador.usuarios.create', compact('roles', 'personaExistente', 'usuarioExistente'));
    }

    public function store(Request $request, GeneracionCredencialesUsuarioService $generador): RedirectResponse
    {
        $datos = $request->validate(array_merge($this->reglasPersona(), [
            'cui' => ['required', 'digits:13'],
            'email' => ['nullable', 'email', 'max:150', Rule::unique('usuarios', 'email')],
            'roles' => ['required', 'array', 'min:1'],
            'roles.*' => ['required', 'integer', 'distinct', Rule::exists('roles', 'id')->where('activo', true)],
        ]));
        $roles = $this->validarRoles($datos['roles']);
        $temporal = $generador->passwordTemporal();
        $usuario = DB::transaction(function () use ($datos, $roles, $temporal, $request, $generador) {
            $persona = Persona::query()->where('cui', $datos['cui'])->lockForUpdate()->first();
            if ($persona) {
                if (Usuario::query()->where('persona_id', $persona->id)->exists()) {
                    throw ValidationException::withMessages(['cui' => 'La persona ya tiene cuenta. Edite sus accesos desde el listado.']);
                }
            } else {
                $persona = Persona::query()->create(collect($datos)->only(array_keys($this->reglasPersona()))->merge(['cui' => $datos['cui']])->all());
            }
            $usuario = Usuario::query()->create([
                'persona_id' => $persona->id,
                'nombre_usuario' => $generador->nombreUsuario($persona->nombres, $persona->apellidos),
                'email' => $datos['email'] ?? null,
                'password' => $temporal,
                'debe_cambiar_password' => true,
                'activo' => true,
            ]);
            $usuario->roles()->sync($roles);
            $this->auditar($request, $usuario, 'CREAR_USUARIO', null, [
                'nombre_usuario' => $usuario->nombre_usuario, 'roles_ids' => $roles, 'activo' => true,
            ]);
            return $usuario;
        }, 3);
        $this->guardarTemporal($request, $usuario, $temporal);
        return redirect()->route('administrador.usuarios.show', $usuario)
            ->with('success', 'Usuario creado. Descargue la ficha de credenciales antes de que venza.');
    }

    public function show(Usuario $usuario): View
    {
        $usuario->load(['persona', 'roles']);
        return view('administrador.usuarios.show', compact('usuario'));
    }

    public function edit(Usuario $usuario): View
    {
        $usuario->load(['persona', 'roles']);
        $roles = $this->rolesDisponibles();
        return view('administrador.usuarios.edit', compact('usuario', 'roles'));
    }

    public function update(Request $request, Usuario $usuario): RedirectResponse
    {
        $datos = $request->validate([
            'nombre_usuario' => ['required', 'string', 'min:3', 'max:100', 'regex:/^[A-Za-z0-9._-]+$/', Rule::unique('usuarios', 'nombre_usuario')->ignore($usuario->id)],
            'email' => ['nullable', 'email', 'max:150', Rule::unique('usuarios', 'email')->ignore($usuario->id)],
            'roles' => ['required', 'array', 'min:1'],
            'roles.*' => ['required', 'integer', 'distinct', Rule::exists('roles', 'id')->where('activo', true)],
            'activo' => ['required', 'boolean'],
        ]);
        $roles = $this->validarRoles($datos['roles']);
        DB::transaction(function () use ($request, $usuario, $datos, $roles) {
            $usuario = Usuario::query()->whereKey($usuario->id)->lockForUpdate()->firstOrFail();
            $antes = ['nombre_usuario' => $usuario->nombre_usuario, 'email' => $usuario->email,
                'activo' => $usuario->activo, 'roles_ids' => $usuario->roles()->pluck('roles.id')->all()];
            $adminRol = Rol::query()->where('codigo', 'ADMINISTRADOR')->value('id');
            $eraAdmin = $adminRol && in_array((int) $adminRol, array_map('intval', $antes['roles_ids']), true);
            $seraAdmin = $adminRol && in_array((int) $adminRol, array_map('intval', $roles), true);
            if ($usuario->id === (int) $request->user()->id && (!$datos['activo'] || !$seraAdmin)) {
                throw ValidationException::withMessages(['roles' => 'No puede desactivar su propia cuenta ni retirarse el rol Administrador.']);
            }
            if ($eraAdmin && $usuario->activo && (!$datos['activo'] || !$seraAdmin)) {
                $otros = Usuario::query()->where('activo', true)->whereKeyNot($usuario->id)
                    ->whereHas('roles', fn ($q) => $q->where('codigo', 'ADMINISTRADOR'))->exists();
                if (!$otros) {
                    throw ValidationException::withMessages(['roles' => 'Debe conservar al menos un Administrador activo.']);
                }
            }
            $usuario->update(['nombre_usuario' => trim($datos['nombre_usuario']),
                'email' => $datos['email'] ?? null, 'activo' => (bool) $datos['activo']]);
            $usuario->roles()->sync($roles);
            $this->auditar($request, $usuario, 'MODIFICAR_USUARIO', $antes, [
                'nombre_usuario' => $usuario->nombre_usuario, 'email' => $usuario->email,
                'activo' => $usuario->activo, 'roles_ids' => $roles,
            ]);
        }, 3);
        return redirect()->route('administrador.usuarios.show', $usuario)->with('success', 'Cuenta actualizada correctamente.');
    }

    public function resetPassword(Request $request, Usuario $usuario, GeneracionCredencialesUsuarioService $generador): RedirectResponse
    {
        $request->validate(['confirmar' => ['required', 'accepted']]);
        if (!$usuario->activo) {
            throw ValidationException::withMessages(['confirmar' => 'Active la cuenta antes de restablecer la contraseña.']);
        }
        $temporal = $generador->passwordTemporal();
        DB::transaction(function () use ($request, $usuario, $temporal) {
            $usuario->update(['password' => $temporal, 'debe_cambiar_password' => true]);
            $this->auditar($request, $usuario, 'RESTABLECER_PASSWORD_USUARIO', null,
                ['debe_cambiar_password' => true]);
        });
        $this->guardarTemporal($request, $usuario, $temporal);
        return redirect()->route('administrador.usuarios.show', $usuario)
            ->with('success', 'Contraseña temporal restablecida. Descargue la ficha antes de que venza.');
    }

    private function reglasPersona(): array
    {
        return [
            'nombres' => ['required', 'string', 'max:150'],
            'apellidos' => ['required', 'string', 'max:150'],
            'fecha_nacimiento' => ['nullable', 'date', 'before_or_equal:today'],
            'sexo' => ['nullable', Rule::in(['M', 'F'])],
            'telefono' => ['nullable', 'string', 'max:30'],
            'correo' => ['nullable', 'email', 'max:150'],
            'direccion' => ['nullable', 'string', 'max:255'],
        ];
    }

    private function rolesDisponibles()
    {
        return Rol::query()->where('activo', true)->whereIn('codigo', self::ROLES)->orderBy('nombre')->get();
    }

    private function validarRoles(array $ids): array
    {
        $permitidos = Rol::query()->whereIn('id', $ids)->whereIn('codigo', self::ROLES)
            ->where('activo', true)->pluck('id')->all();
        if (count($permitidos) !== count($ids)) {
            throw ValidationException::withMessages(['roles' => 'Hay roles no autorizados.']);
        }
        return $permitidos;
    }

    private function guardarTemporal(Request $request, Usuario $usuario, string $password): void
    {
        Cache::put('atlas:admin:credenciales:'.$request->user()->id.':'.$usuario->id,
            Crypt::encryptString($password), now()->addMinutes(10));
    }

    private function auditar(Request $request, Usuario $usuario, string $accion, ?array $antes, array $despues): void
    {
        BitacoraAuditoria::query()->create([
            'usuario_id' => $request->user()->id, 'accion' => $accion,
            'entidad' => 'usuarios', 'entidad_id' => $usuario->id,
            'datos_anteriores' => $antes, 'datos_nuevos' => $despues,
            'motivo' => 'Administración institucional de cuentas',
            'ip' => $request->ip(), 'fecha_evento' => now(),
        ]);
    }
}
