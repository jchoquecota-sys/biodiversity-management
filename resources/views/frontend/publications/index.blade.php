@extends('layouts.frontend')

@section('title', 'Publicaciones Científicas')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h1 class="page-title">Publicaciones Científicas</h1>
                    <p class="page-subtitle">Explora nuestra colección de investigaciones científicas sobre biodiversidad</p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/">Inicio</a></li>
                            <li class="breadcrumb-item active">Publicaciones</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <div class="container my-5">
        <div class="row">
            <!-- Filtros -->
            <div class="col-lg-3 mb-4">
                <div class="filter-card">
                    <div class="filter-header">
                        <h5><i class="fas fa-filter me-2"></i>Filtros de Búsqueda</h5>
                    </div>
                    <div class="filter-body">
                        <form id="filter-form" action="/publications" method="GET">
                            <!-- Búsqueda por texto -->
                            <div class="mb-3">
                                <label for="search" class="form-label">Buscar</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                                    <input type="text" class="form-control" id="search" name="search" value="{{ request('search') }}" placeholder="Título, autor, revista...">
                                </div>
                            </div>
                            
                            <!-- Filtro por año -->
                            <div class="mb-3">
                                <label for="year" class="form-label">Año de Publicación</label>
                                <select class="form-select" id="year" name="year">
                                    <option value="">Todos los años</option>
                                    @foreach($years as $year)
                                        <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <!-- Filtro por autor -->
                            <div class="mb-3">
                                <label for="author" class="form-label">Autor</label>
                                <select class="form-select" id="author" name="author">
                                    <option value="">Todos los autores</option>
                                    @foreach($authors as $author)
                                        <option value="{{ $author }}" {{ request('author') == $author ? 'selected' : '' }}>{{ $author }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <!-- Filtro por revista/journal -->
                            <div class="mb-3">
                                <label for="journal" class="form-label">Revista/Journal</label>
                                <select class="form-select" id="journal" name="journal">
                                    <option value="">Todas las revistas</option>
                                    @foreach($journals as $journal)
                                        <option value="{{ $journal }}" {{ request('journal') == $journal ? 'selected' : '' }}>{{ $journal }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <!-- Filtro por biodiversidad relacionada -->
                            <div class="mb-3">
                                <label for="biodiversity" class="form-label">Categoría de Biodiversidad</label>
                                <select class="form-select" id="biodiversity" name="biodiversity">
                                    <option value="">Todas las categorías</option>
                                    @foreach($biodiversityCategories as $category)
                                        <option value="{{ $category->id }}" {{ request('biodiversity') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary"><i class="fas fa-search me-2"></i>Aplicar Filtros</button>
                                <a href="/publications" class="btn btn-outline-secondary"><i class="fas fa-times me-2"></i>Limpiar Filtros</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Lista de publicaciones -->
            <div class="col-lg-9">
                <div class="publications-header mb-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="section-title">Publicaciones Científicas</h3>
                        <span class="badge bg-primary fs-6">{{ $publications->total() }} publicaciones encontradas</span>
                    </div>
                    
                    <!-- Filtros activos -->
                    @if(request()->hasAny(['search', 'year', 'author', 'journal', 'biodiversity']))
                        <div class="active-filters mt-3">
                            <h6 class="mb-2"><i class="fas fa-filter me-2"></i>Filtros activos:</h6>
                            <div class="filter-tags">
                                @if(request('search'))
                                    <span class="filter-tag">
                                        <i class="fas fa-search me-1"></i>Búsqueda: "{{ request('search') }}"
                                        <a href="{{ request()->fullUrlWithQuery(['search' => null]) }}" class="remove-filter">×</a>
                                    </span>
                                @endif
                                @if(request('year'))
                                    <span class="filter-tag">
                                        <i class="fas fa-calendar me-1"></i>Año: {{ request('year') }}
                                        <a href="{{ request()->fullUrlWithQuery(['year' => null]) }}" class="remove-filter">×</a>
                                    </span>
                                @endif
                                @if(request('author'))
                                    <span class="filter-tag">
                                        <i class="fas fa-user me-1"></i>Autor: {{ request('author') }}
                                        <a href="{{ request()->fullUrlWithQuery(['author' => null]) }}" class="remove-filter">×</a>
                                    </span>
                                @endif
                                @if(request('journal'))
                                    <span class="filter-tag">
                                        <i class="fas fa-book me-1"></i>Revista: {{ request('journal') }}
                                        <a href="{{ request()->fullUrlWithQuery(['journal' => null]) }}" class="remove-filter">×</a>
                                    </span>
                                @endif
                                @if(request('biodiversity'))
                                    @php
                                        $selectedCategory = $biodiversityCategories->find(request('biodiversity'));
                                    @endphp
                                    @if($selectedCategory)
                                        <span class="filter-tag">
                                            <i class="fas fa-leaf me-1"></i>Categoría: {{ $selectedCategory->name }}
                                            <a href="{{ request()->fullUrlWithQuery(['biodiversity' => null]) }}" class="remove-filter">×</a>
                                        </span>
                                    @endif
                                @endif
                                <a href="/publications" class="btn btn-sm btn-outline-secondary ms-2">
                                    <i class="fas fa-times me-1"></i>Limpiar todos
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
                
                @if($publications->count() > 0)
                    <div class="publications-grid">
                        @foreach($publications as $publication)
                            <div class="publication-card" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                                <div class="publication-content">
                                    <div class="publication-header">
                                        <h5 class="publication-title">{{ $publication->title }}</h5>
                                        <div class="publication-meta">
                                            <span class="author"><i class="fas fa-user me-1"></i>{{ $publication->author }}</span>
                                            <span class="year"><i class="fas fa-calendar me-1"></i>{{ $publication->publication_year }}</span>
                                        </div>
                                    </div>
                                    
                                    <div class="publication-details">
                                        @if($publication->journal)
                                            <div class="detail-item">
                                                <i class="fas fa-book me-2"></i>
                                                <strong>Revista:</strong> {{ $publication->journal }}
                                            </div>
                                        @endif
                                        
                                        @if($publication->doi)
                                            <div class="detail-item">
                                                <i class="fas fa-link me-2"></i>
                                                <strong>DOI:</strong> 
                                                <a href="https://doi.org/{{ $publication->doi }}" target="_blank" class="doi-link">{{ $publication->doi }}</a>
                                            </div>
                                        @endif
                                        
                                        @if($publication->biodiversityCategories->count() > 0)
                                            <div class="detail-item">
                                                <i class="fas fa-tags me-2"></i>
                                                <strong>Categorías:</strong>
                                                <div class="category-tags mt-1">
                                                    @foreach($publication->biodiversityCategories as $category)
                                                        <span class="category-tag">{{ $category->name }}</span>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <div class="publication-actions">
                                        <a href="/publications/{{ $publication->id }}" class="btn btn-outline-primary btn-sm rounded-pill">
                                            <i class="fas fa-eye me-1"></i>Ver Detalles
                                        </a>
                                        @if($publication->hasPdfFile())
                                    <a href="{{ $publication->getPdfUrl() }}" class="btn btn-outline-danger" target="_blank">
                                        <i class="fas fa-file-pdf me-2"></i>Descargar PDF
                                    </a>
                                @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <!-- Paginación -->
                    <div class="pagination-wrapper mt-5">
                        {{ $publications->appends(request()->query())->links() }}
                    </div>
                @else
                    <div class="no-results">
                        <div class="no-results-icon">
                            <i class="fas fa-search"></i>
                        </div>
                        <h4>No se encontraron publicaciones</h4>
                        <p>No hay publicaciones científicas que coincidan con los criterios de búsqueda.</p>
                        <a href="/publications" class="btn btn-primary">Ver todas las publicaciones</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@stop

@push('styles')
<style>
    .page-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        color: white;
        padding: 4rem 0 2rem;
        margin-bottom: 0;
    }
    
    .page-title {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }
    
    .page-subtitle {
        font-size: 1.1rem;
        opacity: 0.9;
        margin-bottom: 0;
    }
    
    .breadcrumb {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 25px;
        padding: 0.5rem 1rem;
    }
    
    .breadcrumb-item a {
        color: white;
        text-decoration: none;
    }
    
    .breadcrumb-item.active {
        color: rgba(255, 255, 255, 0.8);
    }
    
    .filter-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        position: sticky;
        top: 2rem;
    }
    
    .filter-header {
        background: var(--primary-color);
        color: white;
        padding: 1rem 1.5rem;
    }
    
    .filter-header h5 {
        margin: 0;
        font-weight: 600;
    }
    
    .filter-body {
        padding: 1.5rem;
    }
    
    .form-label {
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 0.5rem;
    }
    
    .form-control, .form-select {
        border: 2px solid #e9ecef;
        border-radius: 8px;
        padding: 0.75rem;
        transition: all 0.3s ease;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
    }
    
    .input-group-text {
        background: var(--primary-color);
        color: white;
        border: 2px solid var(--primary-color);
    }
    
    .publications-header {
        border-bottom: 3px solid var(--primary-color);
        padding-bottom: 1rem;
    }
    
    .section-title {
        color: var(--text-dark);
        font-weight: 700;
        margin: 0;
    }
    
    .publications-grid {
        display: grid;
        gap: 2rem;
    }
    
    .publication-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        transition: all 0.3s ease;
        border: 1px solid #e9ecef;
    }
    
    .publication-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    }
    
    .publication-content {
        padding: 1.5rem;
    }
    
    .publication-header {
        margin-bottom: 1rem;
    }
    
    .publication-title {
        color: var(--text-dark);
        font-weight: 700;
        font-size: 1.2rem;
        margin-bottom: 0.5rem;
        line-height: 1.4;
    }
    
    .publication-meta {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }
    
    .publication-meta span {
        color: #6c757d;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
    }
    
    .publication-details {
        margin-bottom: 1.5rem;
    }
    
    .detail-item {
        margin-bottom: 0.75rem;
        color: var(--text-dark);
        font-size: 0.95rem;
        display: flex;
        align-items: flex-start;
        gap: 0.5rem;
    }
    
    .detail-item i {
        color: var(--primary-color);
        margin-top: 0.1rem;
    }
    
    .doi-link {
        color: var(--primary-color);
        text-decoration: none;
        word-break: break-all;
    }
    
    .doi-link:hover {
        text-decoration: underline;
    }
    
    .category-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }
    
    .category-tag {
        background: var(--accent-color);
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 15px;
        font-size: 0.8rem;
        font-weight: 500;
    }
    
    .publication-actions {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
    }
    
    .btn {
        border-radius: 25px;
        padding: 0.5rem 1.25rem;
        font-weight: 600;
        transition: all 0.3s ease;
        border: none;
    }
    
    .btn-primary {
        background: var(--primary-color);
        color: white;
    }
    
    .btn-primary:hover {
        background: var(--secondary-color);
        transform: translateY(-2px);
    }
    
    .btn-outline-danger {
        border: 2px solid #dc3545;
        color: #dc3545;
        background: transparent;
    }
    
    .btn-outline-danger:hover {
        background: #dc3545;
        color: white;
        transform: translateY(-2px);
    }
    
    .btn-outline-secondary {
        border: 2px solid #6c757d;
        color: #6c757d;
        background: transparent;
    }
    
    .btn-outline-secondary:hover {
        background: #6c757d;
        color: white;
        transform: translateY(-2px);
    }
    
    .no-results {
        text-align: center;
        padding: 4rem 2rem;
        background: white;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }
    
    .no-results-icon {
        font-size: 4rem;
        color: #dee2e6;
        margin-bottom: 1rem;
    }
    
    .no-results h4 {
        color: var(--text-dark);
        margin-bottom: 1rem;
    }
    
    .no-results p {
        color: #6c757d;
        margin-bottom: 2rem;
    }
    
    .pagination-wrapper {
        display: flex;
        justify-content: center;
    }
    
    .pagination .page-link {
        border-radius: 8px;
        margin: 0 0.25rem;
        border: 2px solid #e9ecef;
        color: var(--primary-color);
    }
    
    .pagination .page-link:hover {
        background: var(--primary-color);
        border-color: var(--primary-color);
        color: white;
    }
    
    .pagination .page-item.active .page-link {
        background: var(--primary-color);
        border-color: var(--primary-color);
    }
    
    /* Estilos para filtros activos */
    .active-filters {
        background: #f8f9fa;
        padding: 1rem;
        border-radius: 10px;
        border-left: 4px solid var(--primary-color);
    }
    
    .filter-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        align-items: center;
    }
    
    .filter-tag {
        background: var(--primary-color);
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.875rem;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
    }
    
    .remove-filter {
        color: white;
        text-decoration: none;
        margin-left: 0.5rem;
        font-weight: bold;
        opacity: 0.8;
        transition: opacity 0.2s;
    }
    
    .remove-filter:hover {
        opacity: 1;
        color: white;
    }
    
    @media (max-width: 768px) {
        .page-title {
            font-size: 2rem;
        }
        
        .publication-meta {
            flex-direction: column;
            gap: 0.5rem;
        }
        
        .filter-tags {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .publication-actions {
            flex-direction: column;
        }
        
        .filter-card {
            position: static;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-submit form on select change
        const selects = document.querySelectorAll('#filter-form select');
        selects.forEach(select => {
            select.addEventListener('change', function() {
                document.getElementById('filter-form').submit();
            });
        });
        
        // Initialize AOS if available
        if (typeof AOS !== 'undefined') {
            AOS.init({
                duration: 600,
                easing: 'ease-in-out',
                once: true
            });
        }
        
        // Add entry animations to publication cards
        const cards = document.querySelectorAll('.publication-card');
        cards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                card.style.transition = 'all 0.6s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 100);
        });
    });
</script>
@endpush