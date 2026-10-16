
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Cambio de contraseña | ATLAS</title>

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
            padding: 24px;
            background: #f2f5f9;
            font-family: Arial, sans-serif;
            color: #1e293b;
        }

        .contenedor {
            width: 100%;
            max-width: 460px;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 32px;
            box-shadow: 0 12px 35px rgba(15, 23, 42, .07);
        }

        .marca {
            color: #163c70;
            font-size: 28px;
            font-weight: 800;
            letter-spacing: 2px;
            text-align: center;
            margin-bottom: 8px;
        }

        h1 {
            margin: 0 0 12px;
            font-size: 21px;
            text-align: center;
        }

        .descripcion {
            color: #64748b;
            font-size: 14px;
            line-height: 1.6;
            text-align: center;
            margin-bottom: 26px;
        }

        label {
            display: block;
            margin: 16px 0 7px;
            font-size: 13px;
            font-weight: 600;
        }

        input {
            width: 100%;
            padding: 12px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font-size: 14px;
        }

        input:focus {
            outline: none;
            border-color: #2563eb;
        }

        .error {
            color: #b91c1c;
            font-size: 12px;
            margin-top: 5px;
        }

        .requisitos {
            margin-top: 16px;
            padding: 12px;
            background: #f8fafc;
            border-radius: 8px;
            font-size: 12px;
            line-height: 1.7;
            color: #475569;
        }

        button {
            width: 100%;
            margin-top: 22px;
            padding: 13px;
            background: #163c70;
            color: white;
            border: 0;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
        }

        button:hover {
            background: #245795;
        }
    </style>
</head>

<body>
    <main class="contenedor">
        <div class="marca">ATLAS</div>

        <h1>Cambio obligatorio de contraseña</h1>

        <p class="descripcion">
            Por seguridad, debes establecer una contraseña
            personal antes de utilizar el sistema.
        </p>

        <form
            method="POST"
            action="{{ route('password.obligatorio.update') }}"
        >
            @csrf

            <label for="password_actual">
                Contraseña temporal o actual
            </label>

            <input
                type="password"
                id="password_actual"
                name="password_actual"
                required
                autocomplete="current-password"
            >

            @error('password_actual')
                <div class="error">{{ $message }}</div>
            @enderror

            <label for="password">
                Nueva contraseña
            </label>

            <input
                type="password"
                id="password"
                name="password"
                required
                autocomplete="new-password"
            >

            @error('password')
                <div class="error">{{ $message }}</div>
            @enderror

            <label for="password_confirmation">
                Confirmar nueva contraseña
            </label>

            <input
                type="password"
                id="password_confirmation"
                name="password_confirmation"
                required
                autocomplete="new-password"
            >

            <div class="requisitos">
                La contraseña debe tener al menos 12 caracteres,
                incluir mayúsculas, minúsculas, números y símbolos.
            </div>

            <button type="submit">
                Actualizar contraseña
            </button>
        </form>
    </main>
</body>
</html>
