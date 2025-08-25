@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Estadísticas del sitio -->
    <div class="mb-4">
        <h3 class="mb-3">Estadísticas del Sitio</h3>
        <x-site-stats />
    </div>
    
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>{{ __('Dashboard') }}</span>
                    <x-visit-counter show-unique="true" class="text-muted small" />
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="alert alert-info">
                        <h5><i class="fas fa-chart-line me-2"></i>Sistema de Contador de Visitas Implementado</h5>
                        <p class="mb-2">El sistema de contador de visitas está ahora activo y registra automáticamente:</p>
                        <ul class="mb-0">
                            <li>Visitas totales por página</li>
                            <li>Visitantes únicos por IP</li>
                            <li>Estadísticas diarias, semanales y mensuales</li>
                            <li>Información de sesión y usuario (si está autenticado)</li>
                        </ul>
                    </div>

                    {{ __('You are logged in!') }}
                    
                    <div class="mt-3">
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            Esta página ha sido visitada <x-visit-counter :show-icon="false" class="fw-bold text-primary d-inline" />
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
