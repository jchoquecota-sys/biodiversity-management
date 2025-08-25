@props([
    'class' => 'row g-3',
    'cardClass' => 'col-md-3'
])

@php
    use App\Helpers\VisitCounterHelper;
    $stats = VisitCounterHelper::getSiteStats();
@endphp

<div {{ $attributes->merge(['class' => $class]) }}>
    <div class="{{ $cardClass }}">
        <div class="card text-center border-primary">
            <div class="card-body">
                <i class="fas fa-eye fa-2x text-primary mb-2"></i>
                <h5 class="card-title">{{ number_format($stats['total_visits']) }}</h5>
                <p class="card-text text-muted">Total de Visitas</p>
            </div>
        </div>
    </div>
    
    <div class="{{ $cardClass }}">
        <div class="card text-center border-success">
            <div class="card-body">
                <i class="fas fa-users fa-2x text-success mb-2"></i>
                <h5 class="card-title">{{ number_format($stats['unique_visitors']) }}</h5>
                <p class="card-text text-muted">Visitantes Únicos</p>
            </div>
        </div>
    </div>
    
    <div class="{{ $cardClass }}">
        <div class="card text-center border-info">
            <div class="card-body">
                <i class="fas fa-calendar-day fa-2x text-info mb-2"></i>
                <h5 class="card-title">{{ number_format($stats['today_visits']) }}</h5>
                <p class="card-text text-muted">Visitas Hoy</p>
            </div>
        </div>
    </div>
    
    <div class="{{ $cardClass }}">
        <div class="card text-center border-warning">
            <div class="card-body">
                <i class="fas fa-calendar-week fa-2x text-warning mb-2"></i>
                <h5 class="card-title">{{ number_format($stats['this_month_visits']) }}</h5>
                <p class="card-text text-muted">Visitas Este Mes</p>
            </div>
        </div>
    </div>
</div>