@extends('adminlte::page')

@section('title', 'Reportes y Estadísticas')

@section('content_header')
    <h1>Reportes y Estadísticas</h1>
@stop

@section('content')
    <div class="row">
        <!-- Estadísticas Generales -->
        <div class="col-md-12 mb-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Estadísticas Generales</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-info"><i class="fas fa-paw"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Especies</span>
                                    <span class="info-box-number">{{ $totalSpecies }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-success"><i class="fas fa-book"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Publicaciones</span>
                                    <span class="info-box-number">{{ $totalPublications }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-warning"><i class="fas fa-exclamation-triangle"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Especies en Peligro</span>
                                    <span class="info-box-number">{{ $endangeredSpecies }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-danger"><i class="fas fa-calendar"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Publicaciones este Mes</span>
                                    <span class="info-box-number">{{ $publicationsThisMonth }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reporte de Biodiversidad -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Reporte de Biodiversidad</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.reports.biodiversity') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Reino</label>
                            <select class="form-control" name="kingdom">
                                <option value="">Todos</option>
                                @foreach($kingdoms as $kingdom)
                                    <option value="{{ $kingdom }}">{{ $kingdom }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Estado de Conservación</label>
                            <select class="form-control" name="conservation_status">
                                <option value="">Todos</option>
                                @foreach($conservationStatuses as $status)
                                    <option value="{{ $status }}">{{ $status }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Formato</label>
                            <div class="custom-control custom-radio">
                                <input type="radio" id="format_pdf" name="format" value="pdf" class="custom-control-input" checked>
                                <label class="custom-control-label" for="format_pdf">PDF</label>
                            </div>
                            <div class="custom-control custom-radio">
                                <input type="radio" id="format_excel" name="format" value="excel" class="custom-control-input">
                                <label class="custom-control-label" for="format_excel">Excel</label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-download"></i> Generar Reporte
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Reporte de Publicaciones -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Reporte de Publicaciones</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.reports.publications') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Rango de Fechas</label>
                            <div class="input-group">
                                <input type="date" class="form-control" name="start_date">
                                <div class="input-group-append input-group-prepend">
                                    <span class="input-group-text">hasta</span>
                                </div>
                                <input type="date" class="form-control" name="end_date">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Autor</label>
                            <select class="form-control" name="author">
                                <option value="">Todos</option>
                                @foreach($authors as $author)
                                    <option value="{{ $author->id }}">{{ $author->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Formato</label>
                            <div class="custom-control custom-radio">
                                <input type="radio" id="pub_format_pdf" name="format" value="pdf" class="custom-control-input" checked>
                                <label class="custom-control-label" for="pub_format_pdf">PDF</label>
                            </div>
                            <div class="custom-control custom-radio">
                                <input type="radio" id="pub_format_excel" name="format" value="excel" class="custom-control-input">
                                <label class="custom-control-label" for="pub_format_excel">Excel</label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-download"></i> Generar Reporte
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráficos Estadísticos -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Distribución por Reino</h3>
                </div>
                <div class="card-body">
                    <canvas id="kingdomChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Publicaciones por Mes</h3>
                </div>
                <div class="card-body">
                    <canvas id="publicationsChart"></canvas>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Gráfico de distribución por reino
        new Chart(document.getElementById('kingdomChart'), {
            type: 'pie',
            data: {
                labels: {!! json_encode($kingdomStats->pluck('kingdom')) !!},
                datasets: [{
                    data: {!! json_encode($kingdomStats->pluck('count')) !!},
                    backgroundColor: ['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc']
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Gráfico de publicaciones por mes
        new Chart(document.getElementById('publicationsChart'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($publicationStats->pluck('month')) !!},
                datasets: [{
                    label: 'Publicaciones',
                    data: {!! json_encode($publicationStats->pluck('count')) !!},
                    backgroundColor: '#3c8dbc'
                }]
            },
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

        // Mostrar mensajes de éxito/error
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: '{{ session("success") }}',
                showConfirmButton: false,
                timer: 1500
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '{{ session("error") }}'
            });
        @endif
    </script>
@stop