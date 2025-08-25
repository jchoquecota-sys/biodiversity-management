@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $totalBiodiversity }}</h3>
                    <p>Especies Registradas</p>
                </div>
                <div class="icon">
                    <i class="fas fa-leaf"></i>
                </div>
                <a href="{{ route('admin.biodiversity.index') }}" class="small-box-footer">Más información <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $totalPublications }}</h3>
                    <p>Publicaciones Científicas</p>
                </div>
                <div class="icon">
                    <i class="fas fa-book"></i>
                </div>
                <a href="{{ route('admin.publications.index') }}" class="small-box-footer">Más información <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ number_format($visitStats['total_visits']) }}</h3>
                    <p>Total de Visitas</p>
                </div>
                <div class="icon">
                    <i class="fas fa-eye"></i>
                </div>
                <div class="small-box-footer">Estadísticas del sitio</div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ number_format($visitStats['unique_visitors']) }}</h3>
                    <p>Visitantes Únicos</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="small-box-footer">Visitantes únicos por IP</div>
            </div>
        </div>
    </div>
    
    <!-- Estadísticas de visitas -->
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="info-box">
                <span class="info-box-icon bg-info"><i class="fas fa-calendar-day"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Visitas Hoy</span>
                    <span class="info-box-number">{{ number_format($visitStats['today_visits']) }}</span>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="info-box">
                <span class="info-box-icon bg-success"><i class="fas fa-calendar-week"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Esta Semana</span>
                    <span class="info-box-number">{{ number_format($visitStats['this_week_visits']) }}</span>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="info-box">
                <span class="info-box-icon bg-warning"><i class="fas fa-calendar-alt"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Este Mes</span>
                    <span class="info-box-number">{{ number_format($visitStats['this_month_visits']) }}</span>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="info-box">
                <span class="info-box-icon bg-danger"><i class="fas fa-chart-line"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Promedio Diario</span>
                    <span class="info-box-number">{{ number_format($visitStats['total_visits'] / max(1, now()->diffInDays(now()->subMonth()))) }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Distribución por Reino</h3>
                </div>
                <div class="card-body">
                    <canvas id="kingdomChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Estado de Conservación</h3>
                </div>
                <div class="card-body">
                    <canvas id="conservationChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Estadísticas de visitas -->
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Visitas Diarias (Últimos 30 días)</h3>
                </div>
                <div class="card-body">
                    <canvas id="dailyVisitsChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Páginas Más Visitadas</h3>
                </div>
                <div class="card-body">
                    @if(count($topPages) > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Página</th>
                                        <th class="text-right">Visitas</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($topPages as $page)
                                        <tr>
                                            <td>
                                                <small title="{{ $page['url'] }}">
                                                    {{ Str::limit(parse_url($page['url'], PHP_URL_PATH) ?: '/', 25) }}
                                                </small>
                                            </td>
                                            <td class="text-right">
                                                <span class="badge badge-primary">{{ number_format($page['visit_count']) }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">No hay datos de visitas disponibles.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Últimas Publicaciones</h3>
                </div>
                <div class="card-body">
                    @if($latestPublications->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Título</th>
                                        <th>Autor</th>
                                        <th>Fecha</th>
                                        <th>Especie</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($latestPublications as $publication)
                                        <tr>
                                            <td>{{ $publication->title }}</td>
                                            <td>{{ $publication->author }}</td>
                                            <td>{{ $publication->publication_date ? $publication->publication_date->format('d/m/Y') : 'N/A' }}</td>
                                            <td>{{ $publication->biodiversityCategory->scientific_name ?? 'N/A' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p>No hay publicaciones disponibles.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Gráfico de distribución por reino
    const kingdomCtx = document.getElementById('kingdomChart').getContext('2d');
    const kingdomChart = new Chart(kingdomCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($biodiversityByKingdom->pluck('kingdom')) !!},
            datasets: [{
                data: {!! json_encode($biodiversityByKingdom->pluck('total')) !!},
                backgroundColor: [
                    '#FF6384',
                    '#36A2EB',
                    '#FFCE56',
                    '#4BC0C0',
                    '#9966FF',
                    '#FF9F40'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Gráfico de estado de conservación
    const conservationCtx = document.getElementById('conservationChart').getContext('2d');
    const conservationChart = new Chart(conservationCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($biodiversityByConservationStatus->pluck('conservation_status')) !!},
            datasets: [{
                label: 'Número de Especies',
                data: {!! json_encode($biodiversityByConservationStatus->pluck('total')) !!},
                backgroundColor: [
                    '#28a745',
                    '#ffc107',
                    '#fd7e14',
                    '#dc3545',
                    '#6f42c1'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
    
    // Gráfico de visitas diarias
    const dailyVisitsCtx = document.getElementById('dailyVisitsChart').getContext('2d');
    const dailyVisitsChart = new Chart(dailyVisitsCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($dailyVisits->pluck('date')) !!},
            datasets: [{
                label: 'Visitas Diarias',
                data: {!! json_encode($dailyVisits->pluck('visits')) !!},
                borderColor: '#007bff',
                backgroundColor: 'rgba(0, 123, 255, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                },
                x: {
                    type: 'time',
                    time: {
                        parser: 'YYYY-MM-DD',
                        displayFormats: {
                            day: 'MMM DD'
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    mode: 'index',
                    intersect: false
                }
            },
            interaction: {
                mode: 'nearest',
                axis: 'x',
                intersect: false
            }
        }
    });
</script>
@stop