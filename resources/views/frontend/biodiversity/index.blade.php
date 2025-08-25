@extends('layouts.frontend')

@section('title', 'Categorías de Biodiversidad - Biodiversidad ')

@section('content')


    <!-- Page Header -->
    <section class="py-5" style="background: linear-gradient(135deg, var(--primary-green), var(--secondary-green)); color: white; margin-top: 76px;">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    <h1 class="display-4 fw-bold mb-3">
                        <i class="fas fa-paw me-3"></i>Categorías de Biodiversidad
                    </h1>
                    <p class="lead">Explora nuestra extensa colección de especies </p>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb justify-content-center bg-transparent">
                            <li class="breadcrumb-item"><a href="/" class="text-warning">Inicio</a></li>
                            <li class="breadcrumb-item active text-white" aria-current="page">Biodiversidad</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </section>

    <div class="container my-5">
        <!-- Filtros -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0">
                        <h3 class="card-title fw-bold text-dark">
                            <i class="fas fa-filter me-2 text-primary"></i>Filtros de Búsqueda
                        </h3>
                    </div>
                    <div class="card-body">
                        <form action="/biodiversity" method="GET" id="filter-form">
                            <div class="row g-3">
                                <!-- Búsqueda General -->
                                <div class="col-lg-6 col-md-12">
                                    <div class="form-group">
                                        <label for="search" class="form-label fw-semibold">
                                            <i class="fas fa-search text-primary me-2"></i>Búsqueda General
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0">
                                                <i class="fas fa-magnifying-glass text-muted"></i>
                                            </span>
                                            <input type="text" name="search" id="search" class="form-control border-start-0 ps-0" 
                                                   placeholder="🔍 Buscar por nombre común o científico..." 
                                                   value="{{ request('search') }}">
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Reino -->
                                <div class="col-lg-3 col-md-6">
                                    <div class="form-group">
                                        <label for="kingdom" class="form-label fw-semibold">
                                            <i class="fas fa-crown text-warning me-2"></i>Reino
                                        </label>
                                        <select name="kingdom" id="kingdom" class="form-select">
                                            <option value="">🌍 Todos los reinos</option>
                                            @foreach($kingdoms as $kingdom)
                                                @php
                                                    $kingdomIcons = [
                                                        'Animalia' => '🐾',
                                                        'Plantae' => '🌱',
                                                        'Fungi' => '🍄',
                                                        'Protista' => '🦠',
                                                        'Bacteria' => '🔬',
                                                        'Archaea' => '⚛️'
                                                    ];
                                                    $icon = $kingdomIcons[$kingdom] ?? '🌿';
                                                @endphp
                                                <option value="{{ $kingdom }}" {{ request('kingdom') == $kingdom ? 'selected' : '' }}>
                                                    {{ $icon }} {{ $kingdom }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                
                                <!-- Estado de Conservación -->
                                <div class="col-lg-3 col-md-6">
                                    <div class="form-group">
                                        <label for="conservation_status" class="form-label fw-semibold">
                                            <i class="fas fa-shield-alt text-success me-2"></i>Estado de Conservación
                                        </label>
                                        <select name="conservation_status" id="conservation_status" class="form-select">
                                            <option value="">🛡️ Todos los estados</option>
                                            @foreach($conservationStatuses as $status)
                                                @php
                                                    $statusData = [
                                                        'EX' => ['icon' => '💀', 'name' => 'Extinta', 'color' => 'danger'],
                                                        'EW' => ['icon' => '🏜️', 'name' => 'Extinta en Estado Silvestre', 'color' => 'danger'],
                                                        'CR' => ['icon' => '🚨', 'name' => 'En Peligro Crítico', 'color' => 'danger'],
                                                        'EN' => ['icon' => '⚠️', 'name' => 'En Peligro', 'color' => 'warning'],
                                                        'VU' => ['icon' => '🔶', 'name' => 'Vulnerable', 'color' => 'warning'],
                                                        'NT' => ['icon' => '🔔', 'name' => 'Casi Amenazada', 'color' => 'info'],
                                                        'LC' => ['icon' => '✅', 'name' => 'Preocupación Menor', 'color' => 'success'],
                                                        'DD' => ['icon' => '❓', 'name' => 'Datos Insuficientes', 'color' => 'secondary'],
                                                        'NE' => ['icon' => '⚪', 'name' => 'No Evaluada', 'color' => 'secondary']
                                                    ];
                                                    $statusInfo = $statusData[$status] ?? ['icon' => '🔍', 'name' => $status, 'color' => 'secondary'];
                                                @endphp
                                                <option value="{{ $status }}" {{ request('conservation_status') == $status ? 'selected' : '' }}>
                                                    {{ $statusInfo['icon'] }} {{ $statusInfo['name'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Segunda fila de filtros -->
                            <div class="row g-3 mt-2">
                                <!-- Hábitat -->
                                <div class="col-lg-4 col-md-6">
                                    <div class="form-group">
                                        <label for="habitat" class="form-label fw-semibold">
                                            <i class="fas fa-tree text-success me-2"></i>Hábitat
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0">
                                                <i class="fas fa-mountain text-muted"></i>
                                            </span>
                                            <input type="text" name="habitat" id="habitat" class="form-control border-start-0 ps-0" 
                                                   placeholder="🏞️ Tipo de hábitat (ej: bosque, marino...)" 
                                                   value="{{ request('habitat') }}">
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Ordenar por -->
                                <div class="col-lg-3 col-md-6">
                                    <div class="form-group">
                                        <label for="sort_by" class="form-label fw-semibold">
                                            <i class="fas fa-sort text-info me-2"></i>Ordenar por
                                        </label>
                                        <select name="sort_by" id="sort_by" class="form-select">
                                            <option value="name" {{ request('sort_by') == 'name' ? 'selected' : '' }}>📝 Nombre</option>
                                            <option value="scientific_name" {{ request('sort_by') == 'scientific_name' ? 'selected' : '' }}>🔬 Nombre Científico</option>
                                            <option value="kingdom" {{ request('sort_by') == 'kingdom' ? 'selected' : '' }}>👑 Reino</option>
                                            <option value="conservation_status" {{ request('sort_by') == 'conservation_status' ? 'selected' : '' }}>🛡️ Estado de Conservación</option>
                                            <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>📅 Fecha de Registro</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <!-- Dirección de ordenamiento -->
                                <div class="col-lg-2 col-md-6">
                                    <div class="form-group">
                                        <label for="sort_direction" class="form-label fw-semibold">
                                            <i class="fas fa-arrows-alt-v text-info me-2"></i>Orden
                                        </label>
                                        <select name="sort_direction" id="sort_direction" class="form-select">
                                            <option value="asc" {{ request('sort_direction') == 'asc' ? 'selected' : '' }}>⬆️ Ascendente</option>
                                            <option value="desc" {{ request('sort_direction') == 'desc' ? 'selected' : '' }}>⬇️ Descendente</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <!-- Botones de acción -->
                                <div class="col-lg-3 col-md-6">
                                    <div class="form-group">
                                        <label class="form-label fw-semibold text-transparent">Acciones</label>
                                        <div class="d-flex gap-2">
                                            <button type="submit" class="btn btn-primary flex-fill">
                                                <i class="fas fa-search me-2"></i>Buscar
                                            </button>
                                            <a href="/biodiversity" class="btn btn-outline-secondary flex-fill">
                                                <i class="fas fa-broom me-2"></i>Limpiar
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Filtros rápidos -->
                            <div class="row mt-3">
                                <div class="col-12">
                                    <div class="d-flex flex-wrap gap-2 align-items-center">
                                        <small class="text-muted fw-semibold me-2">🚀 Filtros rápidos:</small>
                                        <button type="button" class="btn btn-outline-danger btn-sm quick-filter" data-filter="conservation_status" data-value="CR">
                                            🚨 En Peligro Crítico
                                        </button>
                                        <button type="button" class="btn btn-outline-warning btn-sm quick-filter" data-filter="conservation_status" data-value="EN">
                                            ⚠️ En Peligro
                                        </button>
                                        <button type="button" class="btn btn-outline-primary btn-sm quick-filter" data-filter="kingdom" data-value="Animalia">
                                            🐾 Animales
                                        </button>
                                        <button type="button" class="btn btn-outline-success btn-sm quick-filter" data-filter="kingdom" data-value="Plantae">
                                            🌱 Plantas
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Resultados -->
        <div class="row g-4">
            @if($biodiversityCategories->count() > 0)
                @foreach($biodiversityCategories as $item)
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="species-card card h-100">
                            <div class="species-image">
                                @if($item->image_path)
                                    <img src="{{ $item->getImageUrl() }}" class="w-100 h-100" style="object-fit: cover; cursor: pointer;" alt="{{ $item->name }}" onclick="showImageModal('{{ $item->getImageUrl() }}', '{{ $item->name }}')">
                                    <div class="image-overlay">
                                        <small class="text-white">Click para ampliar</small>
                                    </div>
                                @else
                                    <i class="fas fa-leaf text-white" style="font-size: 4rem;"></i>
                                @endif
                                @if($item->conservationStatus)
                                    <span class="conservation-badge bg-{{ $item->conservationStatus->color }} text-white">
                                        {{ $item->conservationStatus->name }}
                                    </span>
                                @else
                                    <span class="conservation-badge bg-secondary text-white">
                                        No Evaluado
                                    </span>
                                @endif
                            </div>
                            <div class="card-body">
                                <h5 class="card-title fw-bold">{{ $item->name }}</h5>
                                <h6 class="card-subtitle mb-3 text-muted fst-italic">{{ $item->scientific_name }}</h6>
                                <div class="mb-3">
                                    <span class="badge bg-primary">{{ $item->kingdom }}</span>
                                    @if($item->habitat)
                                        <span class="badge bg-info">{{ $item->habitat }}</span>
                                    @endif
                                </div>
                                <p class="card-text text-muted">
                                    {{ Str::limit(strip_tags($item->description), 120) }}
                                </p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <a href="/biodiversity/{{ $item->id }}" class="btn btn-outline-primary btn-sm rounded-pill">
                                        <i class="fas fa-eye me-1"></i>Ver Detalles
                                    </a>
                                    @if($item->publications->count() > 0)
                                        <small class="text-muted">
                                            <i class="fas fa-book me-1"></i>{{ $item->publications->count() }} {{ Str::plural('publicación', $item->publications->count()) }}
                                        </small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="fas fa-search fa-5x text-muted mb-4"></i>
                        <h3 class="text-muted">No se encontraron resultados</h3>
                        <p class="text-muted">No se encontraron categorías de biodiversidad que coincidan con los criterios de búsqueda.</p>
                        <a href="/biodiversity" class="btn btn-primary">
                            <i class="fas fa-sync me-2"></i>Ver Todas las Especies
                        </a>
                    </div>
                </div>
            @endif
        </div>

        <!-- Paginación -->
        <div class="row pagination-container">
            <div class="col-12 d-flex justify-content-center">
                <div class="pagination-wrapper">
                    {{ $biodiversityCategories->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para mostrar imagen en tamaño completo -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content bg-transparent border-0">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title text-white" id="imageModalLabel"></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center p-0">
                    <img id="modalImage" src="" alt="" class="img-fluid rounded" style="max-height: 80vh; object-fit: contain;">
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    /* Estilos mejorados para filtros */
    .form-label {
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
    }
    
    .form-select, .form-control {
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        transition: all 0.3s ease;
        font-size: 0.9rem;
    }
    
    .form-select:focus, .form-control:focus {
        border-color: #2c5530;
        box-shadow: 0 0 0 0.2rem rgba(44, 85, 48, 0.15);
    }
    
    .input-group-text {
        border-radius: 8px 0 0 8px;
        background-color: #f8fafc;
        border-color: #e2e8f0;
    }
    
    .form-control.border-start-0 {
        border-radius: 0 8px 8px 0;
    }
    
    /* Filtros rápidos */
    .quick-filter {
        border-radius: 20px;
        font-size: 0.8rem;
        padding: 0.4rem 0.8rem;
        transition: all 0.3s ease;
        border-width: 1px;
    }
    
    .quick-filter:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    
    .quick-filter.active {
        transform: scale(0.95);
    }
    
    /* Mejoras visuales para el card de filtros */
    .card {
        border-radius: 15px;
        overflow: hidden;
    }
    
    .card-header {
        background: linear-gradient(135deg, #f8fafc, #e2e8f0);
        border-bottom: 1px solid #e2e8f0;
    }
    
    /* Responsive improvements */
    @media (max-width: 768px) {
        .form-label {
            font-size: 0.85rem;
        }
        
        .form-select, .form-control {
            font-size: 0.85rem;
        }
        
        .quick-filter {
            font-size: 0.75rem;
            padding: 0.3rem 0.6rem;
        }
    }
    
    .species-image {
        position: relative;
        height: 200px;
        overflow: hidden;
        border-radius: 8px 8px 0 0;
        background: linear-gradient(135deg, #2c5530, #4a7c59);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .species-image img {
        transition: transform 0.3s ease;
    }
    
    .species-image:hover img {
        transform: scale(1.05);
    }
    
    .image-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(transparent, rgba(0,0,0,0.7));
        padding: 20px 10px 10px;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .species-image:hover .image-overlay {
        opacity: 1;
    }
    
    .species-card {
        border: none;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }
    
    .species-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }
    
    /* Contenedor de paginación simple */
    .pagination-container {
        margin-top: 3rem;
        margin-bottom: 2rem;
        padding: 1.5rem 0;
        background: rgba(44, 85, 48, 0.02);
        border-radius: 15px;
        position: relative;
    }
    
    .pagination-wrapper {
        position: relative;
        z-index: 1;
    }
    
    /* Estilos para paginación simple */
    .pagination {
        justify-content: center;
        margin: 0;
        gap: 1rem;
        flex-wrap: wrap;
    }
    
    .pagination .page-link {
        font-size: 1rem;
        font-weight: 500;
        padding: 0.75rem 1.5rem;
        border-radius: 25px;
        margin: 0;
        border: 2px solid #e5e7eb;
        color: #374151;
        background: white;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        position: relative;
        min-width: 120px;
        text-align: center;
        text-decoration: none;
    }
    
    .pagination .page-link:hover {
        background: #2c5530;
        border-color: #2c5530;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(44, 85, 48, 0.3);
    }
    
    .pagination .page-item.disabled .page-link {
        background: #f9fafb;
        border-color: #e5e7eb;
        color: #9ca3af;
        cursor: not-allowed;
    }
    
    .pagination .page-item.disabled .page-link:hover {
        background: #f9fafb;
        border-color: #e5e7eb;
        color: #9ca3af;
        transform: none;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }
    
    /* Responsive para dispositivos móviles */
    @media (max-width: 768px) {
        .pagination .page-link {
            padding: 0.625rem 1.25rem;
            font-size: 0.9rem;
            min-width: 100px;
        }
        
        .pagination {
            gap: 0.75rem;
        }
        
        .pagination-container {
            margin-top: 2rem;
            padding: 1rem 0;
        }
    }
    
    /* Ocultar flechas de navegación SVG */
    .pagination .page-link[rel="prev"],
    .pagination .page-link[rel="next"],
    .pagination .pagination-arrow,
    .pagination svg.w-5.h-5,
    .pagination svg[viewBox="0 0 20 20"] {
        display: none !important;
    }
    
    /* Ocultar cualquier elemento SVG de flecha en paginación */
    .pagination svg,
    .pagination .page-link svg {
        display: none !important;
    }
    
    /* Ajustar tamaño de todos los elementos de paginación */
    .pagination .page-link {
        font-size: 0.75rem;
        padding: 0.2rem 0.4rem;
        min-width: 25px;
        height: 25px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Actualizar formulario automáticamente al cambiar selects
        const kingdomSelect = document.getElementById('kingdom');
        const conservationSelect = document.getElementById('conservation_status');
        const sortBySelect = document.getElementById('sort_by');
        const sortDirectionSelect = document.getElementById('sort_direction');
        const form = document.getElementById('filter-form');
        
        // Auto-submit en cambios de filtros principales
        if (kingdomSelect) {
            kingdomSelect.addEventListener('change', function() {
                form.submit();
            });
        }
        
        if (conservationSelect) {
            conservationSelect.addEventListener('change', function() {
                form.submit();
            });
        }
        
        if (sortBySelect) {
            sortBySelect.addEventListener('change', function() {
                form.submit();
            });
        }
        
        if (sortDirectionSelect) {
            sortDirectionSelect.addEventListener('change', function() {
                form.submit();
            });
        }
        
        // Funcionalidad de filtros rápidos
        const quickFilters = document.querySelectorAll('.quick-filter');
        quickFilters.forEach(button => {
            button.addEventListener('click', function() {
                const filterType = this.getAttribute('data-filter');
                const filterValue = this.getAttribute('data-value');
                
                // Agregar efecto visual
                this.classList.add('active');
                setTimeout(() => {
                    this.classList.remove('active');
                }, 150);
                
                // Establecer el valor en el select correspondiente
                const targetSelect = document.getElementById(filterType);
                if (targetSelect) {
                    targetSelect.value = filterValue;
                    
                    // Enviar formulario
                    form.submit();
                }
            });
        });
        
        // Búsqueda en tiempo real con debounce
        const searchInput = document.getElementById('search');
        const habitatInput = document.getElementById('habitat');
        let searchTimeout;
        
        function debounceSearch(input) {
            input.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    if (this.value.length >= 3 || this.value.length === 0) {
                        form.submit();
                    }
                }, 800);
            });
        }
        
        if (searchInput) {
            debounceSearch(searchInput);
        }
        
        if (habitatInput) {
            debounceSearch(habitatInput);
        }
        
        // Indicador visual de filtros activos
        function updateActiveFilters() {
            const activeFilters = [];
            
            if (searchInput && searchInput.value) activeFilters.push('Búsqueda');
            if (kingdomSelect && kingdomSelect.value) activeFilters.push('Reino');
            if (conservationSelect && conservationSelect.value) activeFilters.push('Estado');
            if (habitatInput && habitatInput.value) activeFilters.push('Hábitat');
            
            // Mostrar contador de filtros activos
            const filterTitle = document.querySelector('.card-title');
            if (filterTitle) {
                const existingBadge = filterTitle.querySelector('.filter-count');
                if (existingBadge) existingBadge.remove();
                
                if (activeFilters.length > 0) {
                    const badge = document.createElement('span');
                    badge.className = 'badge bg-primary ms-2 filter-count';
                    badge.textContent = activeFilters.length;
                    badge.title = `Filtros activos: ${activeFilters.join(', ')}`;
                    filterTitle.appendChild(badge);
                }
            }
        }
        
        // Actualizar indicador al cargar la página
        updateActiveFilters();
        
        // Animaciones de entrada
        const cards = document.querySelectorAll('.species-card');
        cards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(30px)';
            card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            
            setTimeout(() => {
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 100);
        });
        
        // Función para mostrar imagen en modal
        window.showImageModal = function(imageUrl, speciesName) {
            document.getElementById('modalImage').src = imageUrl;
            document.getElementById('imageModalLabel').textContent = speciesName;
            new bootstrap.Modal(document.getElementById('imageModal')).show();
        };
    });
</script>
@endpush