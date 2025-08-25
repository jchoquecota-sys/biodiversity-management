<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Publicaciones</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .date {
            text-align: right;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        .filters {
            margin-bottom: 20px;
        }
        .summary {
            margin-bottom: 30px;
        }
        .chart-container {
            margin-bottom: 30px;
            page-break-inside: avoid;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Reporte de Publicaciones</h1>
    </div>

    <div class="date">
        Fecha: {{ $date }}
    </div>

    @if(!empty($filters))
    <div class="filters">
        <h3>Filtros Aplicados:</h3>
        @if(isset($filters['year']))
            <p>Año: {{ $filters['year'] }}</p>
        @endif
        @if(isset($filters['journal']))
            <p>Revista: {{ $filters['journal'] }}</p>
        @endif
    </div>
    @endif

    <div class="summary">
        <h3>Resumen</h3>
        <table>
            <tr>
                <th>Total de Publicaciones</th>
                <td>{{ $publicationStats['total'] }}</td>
            </tr>
            <tr>
                <th>Revistas Diferentes</th>
                <td>{{ count($publicationStats['by_journal']) }}</td>
            </tr>
        </table>
    </div>

    <div class="publications">
        <h3>Lista de Publicaciones</h3>
        <table>
            <thead>
                <tr>
                    <th>Título</th>
                    <th>Año</th>
                    <th>Revista</th>
                    <th>DOI</th>
                </tr>
            </thead>
            <tbody>
                @foreach($publications as $publication)
                <tr>
                    <td>{{ $publication->title }}</td>
                    <td>{{ $publication->publication_year }}</td>
                    <td>{{ $publication->journal }}</td>
                    <td>{{ $publication->doi }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>