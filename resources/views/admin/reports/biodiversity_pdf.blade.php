<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Biodiversidad</title>
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
    </style>
</head>
<body>
    <div class="header">
        <h1>Reporte de Biodiversidad</h1>
    </div>

    <div class="date">
        Fecha: {{ $date }}
    </div>

    @if(!empty($filters))
    <div class="filters">
        <h3>Filtros Aplicados:</h3>
        @if(isset($filters['kingdom']))
            <p>Reino: {{ $filters['kingdom'] }}</p>
        @endif
        @if(isset($filters['conservation_status']))
            <p>Estado de Conservación: {{ $filters['conservation_status'] }}</p>
        @endif
    </div>
    @endif

    <table>
        <thead>
            <tr>
                <th>Nombre Científico</th>
                <th>Nombre Común</th>
                <th>Reino</th>
                <th>Familia</th>
                <th>Estado de Conservación</th>
                <th>Hábitat</th>
            </tr>
        </thead>
        <tbody>
            @foreach($species as $specie)
            <tr>
                <td>{{ $specie->scientific_name }}</td>
                <td>{{ $specie->common_name }}</td>
                <td>{{ $specie->kingdom }}</td>
                <td>{{ $specie->family }}</td>
                <td>{{ $specie->conservation_status }}</td>
                <td>{{ $specie->habitat }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Total de especies: {{ $species->count() }}</p>
    </div>
</body>
</html>