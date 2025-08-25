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
</script>
@stop