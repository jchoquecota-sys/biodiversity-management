@props([
    'showUnique' => false,
    'showIcon' => true,
    'class' => 'text-sm text-gray-600',
    'url' => null
])

@php
    use App\Helpers\VisitCounterHelper;
    
    $visits = $url ? VisitCounterHelper::getPageVisits($url) : VisitCounterHelper::getCurrentPageVisits();
    $uniqueVisits = $showUnique ? ($url ? VisitCounterHelper::getPageUniqueVisits($url) : VisitCounterHelper::getCurrentPageUniqueVisits()) : 0;
@endphp

<div {{ $attributes->merge(['class' => $class]) }}>
    @if($showIcon)
        <i class="fas fa-eye me-1"></i>
    @endif
    
    <span>{{ number_format($visits) }} {{ $visits === 1 ? 'visita' : 'visitas' }}</span>
    
    @if($showUnique)
        <span class="ms-2">
            <i class="fas fa-users me-1"></i>
            {{ number_format($uniqueVisits) }} {{ $uniqueVisits === 1 ? 'visitante único' : 'visitantes únicos' }}
        </span>
    @endif
</div>