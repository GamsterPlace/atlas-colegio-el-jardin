<!doctype html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <style>
            body {
                font-family: DejaVu Sans, sans-serif;
                font-size: 9px;
                color: #203954
            }

            h1 {
                font-size: 17px;
                color: #174f8e
            }

            table {
                width: 100%;
                border-collapse: collapse
            }

            th {
                background: #eaf1f8
            }

            td, th {
                padding: 6px;
                border: 1px solid #d7e1eb;
                text-align: left
            }

            .nota {
                font-size: 9px;
                color: #5e7085
            }
        </style>
    </head>
    <body>
        <h1>ATLAS · Reporte de asistencia estudiantil</h1>
        <p class="nota">
            Período: {{ $filtros['desde'] }} al {{ $filtros['hasta'] }} · Generado: {{ now('America/Guatemala')->format('d/m/Y H:i:s') }} · Máximo 3,000 registros por PDF
        </p>
        <table>
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Estudiante</th>
                    <th>Carné</th>
                    <th>Grado / Sección</th>
                    <th>Entrada</th>
                    <th>Salida</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                @forelse($registros as $r)
                    <tr>
                        <td>{{ $r->fecha?->format('d/m/Y') }}</td>
                        <td>{{ $r->inscripcion?->estudiante?->nombre_completo }}</td>
                        <td>{{ $r->inscripcion?->estudiante?->numero_carnet }}</td>
                        <td>{{ $r->inscripcion?->seccion?->grado?->nombre }} / {{ $r->inscripcion?->seccion?->nombre }}</td>
                        <td>{{ $r->hora_entrada ? \Illuminate\Support\Carbon::parse($r->hora_entrada)->format('H:i:s') : '—' }}</td>
                        <td>{{ $r->hora_salida ? \Illuminate\Support\Carbon::parse($r->hora_salida)->format('H:i:s') : '—' }}</td>
                        <td>{{ $r->estado }}{{ $r->entrada_tarde ? ' (entrada tardía)' : '' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">Sin registros.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </body>
</html>
