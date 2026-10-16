
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Seleccionar rol | ATLAS</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f2f5fa;
            font-family: Arial, sans-serif;
            color: #182c49;
        }

        .contenedor {
            width: min(94%, 520px);
            background: #fff;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 12px 40px rgba(15, 35, 65, .10);
            text-align: center;
        }

        .marca {
            font-size: 32px;
            font-weight: 800;
            letter-spacing: 3px;
            color: #143c71;
        }

        h1 {
            font-size: 23px;
            margin: 24px 0 12px;
        }

        .descripcion {
            font-size: 14px;
            line-height: 1.7;
            color: #65748a;
            margin-bottom: 28px;
        }

        .roles {
            display: grid;
            gap: 12px;
        }

        .boton-rol {
            width: 100%;
            padding: 17px;
            border: 1px solid #dbe5f1;
            background: #f8faff;
            border-radius: 10px;
            color: #143c71;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: .2s;
        }

        .boton-rol:hover {
            background: #143c71;
            color: white;
            border-color: #143c71;
        }

        .cerrar {
            margin-top: 25px;
            background: none;
            border: none;
            color: #a63838;
            font-size: 14px;
            cursor: pointer;
        }

        .pie {
            margin-top: 25px;
            color: #8995a7;
            font-size: 12px;
        }
    </style>
</head>

<body>
    <main class="contenedor">

        <div class="marca">ATLAS</div>

        <h1>Seleccionar rol de acceso</h1>

        <p class="descripcion">
            Su cuenta tiene varios roles asignados.
            Seleccione el rol con el que desea ingresar
            al sistema.
        </p>

        <div class="roles">
            @foreach ($roles as $rol)
                <form method="POST"
                      action="{{ route('roles.activar') }}">
                    @csrf

                    <input
                        type="hidden"
                        name="rol_id"
                        value="{{ $rol->id }}"
                    >

                    <button type="submit" class="boton-rol">
                        {{ $rol->nombre }}
                    </button>
                </form>
            @endforeach
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit" class="cerrar">
                Cerrar sesión
            </button>
        </form>

        <div class="pie">
            Colegio Mixto El Jardín
        </div>

    </main>
</body>
</html>
