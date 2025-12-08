<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Reporte de Rankings - {{ $event->name }}</title>
    <style>
        body {
            font-family: sans-serif;
        }

        h1 {
            text-align: center;
            color: #2c3e50;
        }

        h2 {
            text-align: center;
            color: #7f8c8d;
            font-size: 14px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            font-size: 12px;
        }

        th {
            background-color: #f2f2f2;
            color: #333;
        }

        .pos {
            text-align: center;
            font-weight: bold;
            width: 50px;
        }

        .score {
            text-align: center;
            font-weight: bold;
            width: 80px;
        }

        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 10px;
            color: #999;
        }
    </style>
</head>

<body>
    <h1>{{ $event->name }}</h1>
    <h2>Reporte Oficial de Resultados</h2>

    <p><strong>Fecha de generación:</strong> {{ now()->format('d/m/Y H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th class="pos">Pos</th>
                <th>Proyecto</th>
                <th>Categoría</th>
                <th>Líder del Equipo</th>
                <th>Asesor</th>
                <th class="score">Puntaje</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rankings as $project)
                <tr>
                    <td class="pos">{{ $project->ranking_position }}</td>
                    <td>{{ $project->title }}</td>
                    <td>{{ $project->category }}</td>
                    <td>{{ $project->author->name }}</td>
                    <td>{{ $project->advisor ? $project->advisor->name : 'N/A' }}</td>
                    <td class="score">{{ number_format($project->average_score, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Generado por WebAtoon - Sistema de Gestión de Eventos
    </div>
</body>

</html>