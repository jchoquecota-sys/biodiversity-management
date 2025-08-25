@extends('adminlte::page')

@section('title', 'Estadísticas Generales')

@section('content_header')
    <h1>Estadísticas Generales</h1>
@stop

@section('content')
<div class="container-fluid">
    <!-- Resumen de Biodiversidad -->
    <div class="row mb-4">
        <div class="col-md-6">
            <h4 class="mb-3">Biodiversidad</h4>
            <div class="row">
                <div class="col-md-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $biodiversityStats['total'] }}</h3>
                            <p>Total Especies</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-leaf"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ count($biodiversityStats['by_conservation']) }}</h3>
                            <p>Estados de Conservación</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>{{ count($biodiversityStats['by_habitat']) }}</h3>
                            <p>Hábitats Diferentes</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-tree"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Resumen de Publicaciones -->
        <div class="col-md-6">
            <h4 class="mb-3">Publicaciones</h4>
            <div class="row">
                <div class="col-md-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ $publicationStats['total'] }}</h3>
                            <p>Total Publicaciones</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-book"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="small-box bg-primary">
                        <div class="inner">
                            <h3>{{ count($publicationStats['by_journal']) }}</h3>
                            <p>Revistas Diferentes</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-newspaper"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="small-box bg-secondary">
                        <div class="inner">
                            <h3>{{ count($publicationStats['by_year']) }}</h3>
                            <p>Años con Publicaciones</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-calendar"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
                </div>
                <div class="icon">
                    <i class="fas fa-tree"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráficos Principales -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Distribución por Reino y Estado de Conservación</h3>
                </div>
                <div class="card-body">
                    <canvas id="radarChart" style="height: 300px;"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Tendencia de Publicaciones por Año</h3>
                </div>
                <div class="card-body">
                    <canvas id="lineChart" style="height: 300px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Tablas de Distribución -->
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Distribución por Hábitat</h3>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>Hábitat</th>
                                <th>Cantidad</th>
                                <th>Porcentaje</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($biodiversityStats['by_habitat'] as $habitat)
                            <tr>
                                <td>{{ $habitat->habitat }}</td>
                                <td>{{ $habitat->total }}</td>
                                <td>{{ number_format(($habitat->total / $biodiversityStats['total']) * 100, 1) }}%</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Distribución por Revistas</h3>
                </div>
                <div class="card-body">
                    <canvas id="mixedChart" style="height: 300px;"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
<style>
    .small-box .icon {
        font-size: 70px;
        opacity: 0.3;
    }
</style>
@stop

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Datos para el gráfico de radar
    const radarData = {
        labels: [
            ...{!! json_encode(array_column($biodiversityStats['by_kingdom']->toArray(), 'kingdom')) !!},
            ...{!! json_encode(array_column($biodiversityStats['by_conservation']->toArray(), 'conservation_status')) !!}
        ],
        datasets: [{
            label: 'Distribución',
            data: [
                ...{!! json_encode(array_column($biodiversityStats['by_kingdom']->toArray(), 'total')) !!},
                ...{!! json_encode(array_column($biodiversityStats['by_conservation']->toArray(), 'total')) !!}
            ],
            fill: true,
            backgroundColor: 'rgba(54, 162, 235, 0.2)',
            borderColor: 'rgba(54, 162, 235, 1)',
            pointBackgroundColor: 'rgba(54, 162, 235, 1)',
            pointBorderColor: '#fff',
            pointHoverBackgroundColor: '#fff',
            pointHoverBorderColor: 'rgba(54, 162, 235, 1)'
        }]
    };

    // Datos para el gráfico de línea
    const lineData = {
        labels: {!! json_encode(array_column($publicationStats['by_year']->toArray(), 'year')) !!},
        datasets: [{
            label: 'Publicaciones por Año',
            data: {!! json_encode(array_column($publicationStats['by_year']->toArray(), 'total')) !!},
            borderColor: 'rgba(75, 192, 192, 1)',
            tension: 0.1,
            fill: false
        }]
    };

    // Datos para el gráfico mixto
    const mixedData = {
        labels: {!! json_encode(array_column($publicationStats['by_journal']->toArray(), 'journal')) !!},
        datasets: [{
            type: 'bar',
            label: 'Publicaciones por Revista',
            data: {!! json_encode(array_column($publicationStats['by_journal']->toArray(), 'total')) !!},
            backgroundColor: 'rgba(255, 159, 64, 0.2)',
            borderColor: 'rgba(255, 159, 64, 1)',
            borderWidth: 1
        }]
    };

    // Configuración de los gráficos
document.addEventListener('DOMContentLoaded', function() {
    // Gráfico de Radar
    new Chart(document.getElementById('radarChart'), {
        type: 'radar',
        data: radarData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            elements: {
                line: {
                    borderWidth: 3
                }
            },
            scales: {
                r: {
                    angleLines: {
                        display: true
                    },
                    suggestedMin: 0
                }
            }
        }
    });

    // Gráfico de Línea
    new Chart(document.getElementById('lineChart'), {
        type: 'line',
        data: lineData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });

    // Gráfico Mixto
    new Chart(document.getElementById('mixedChart'), {
        type: 'bar',
        data: mixedData,
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
});
});
</script>
@stop