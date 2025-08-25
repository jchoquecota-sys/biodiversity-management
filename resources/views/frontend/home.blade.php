@extends('layouts.frontend')

@section('title', 'Inicio - Biodiversidad')

@section('content')
    <!-- Hero Section -->
    <section class="hero-section {{ $useImageSlider && $heroSliderImages->count() > 0 ? 'hero-with-slider' : '' }}">
        @if($useImageSlider && $heroSliderImages->count() > 0)
            <!-- Hero Slider -->
            <div id="heroCarousel" class="carousel slide" data-bs-ride="{{ $sliderAutoplay ? 'carousel' : 'false' }}" data-bs-interval="{{ $sliderInterval }}">
                <div class="carousel-inner">
                    @foreach($heroSliderImages as $index => $sliderImage)
                        <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                            <div class="hero-slide" style="background-image: url('{{ $sliderImage->getImageUrl('hero') }}');">
                                <div class="container">
                                    <div class="row align-items-center min-vh-75">
                                        <div class="col-lg-8">
                                            <div class="hero-content text-white">
                                                <h1 class="display-4 fw-bold mb-4">
                                                    {{ $sliderImage->title ?? $heroContent['title']->value ?? 'Descubre la Riqueza de la Biodiversidad' }}
                                                </h1>
                                                <p class="lead mb-4">
                                                    {{ $sliderImage->description ?? $heroContent['subtitle']->value ?? 'Explora nuestra extensa base de datos de especies, ecosistemas y publicaciones científicas.' }}
                                                </p>
                                                <div class="d-flex gap-3 flex-wrap">
                                                    @if($sliderImage->button_text && $sliderImage->button_url)
                                                        <a href="{{ $sliderImage->button_url }}" class="btn btn-warning btn-lg">
                                                            {{ $sliderImage->button_text }}
                                                        </a>
                                                    @else
                                                        <a href="{{ $heroContent['button_primary_url']->value ?? '/biodiversity' }}" class="btn btn-warning btn-lg">
                                                            @if($enableIcons)<i class="fas fa-search me-2"></i>@endif{{ $heroContent['button_primary_text']->value ?? 'Explorar Especies' }}
                                                        </a>
                                                        <a href="{{ $heroContent['button_secondary_url']->value ?? '/publications' }}" class="btn btn-outline-light btn-lg">
                                                            @if($enableIcons)<i class="fas fa-book me-2"></i>@endif{{ $heroContent['button_secondary_text']->value ?? 'Publicaciones' }}
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        @if($sliderImage->has_overlay_image && $sliderImage->hasMedia('overlay_images'))
                                            <div class="col-lg-4">
                                                <div class="overlay-image-container overlay-{{ $sliderImage->overlay_position ?? 'right' }}">
                                                    <div class="overlay-image-wrapper">
                                                        <img src="{{ $sliderImage->getOverlayImageUrl('overlay') }}" 
                                             alt="{{ $sliderImage->overlay_alt_text ?? $sliderImage->alt_text }}" 
                                             class="overlay-image img-fluid rounded shadow-lg"
                                             style="max-width: {{ $sliderImage->overlay_width ?? 300 }}px; max-height: {{ $sliderImage->overlay_height ?? 200 }}px; width: 100%; height: auto; object-fit: contain;">
                                                        @if($sliderImage->overlay_description || ($sliderImage->overlay_button_text && $sliderImage->overlay_button_url))
                                                            <div class="overlay-content mt-3">
                                                                @if($sliderImage->overlay_description)
                                                                    <p class="text-white mb-2">{{ $sliderImage->overlay_description }}</p>
                                                                @endif
                                                                @if($sliderImage->overlay_button_text && $sliderImage->overlay_button_url)
                                                                    <a href="{{ $sliderImage->overlay_button_url }}" class="btn btn-outline-light btn-sm">
                                                                        {{ $sliderImage->overlay_button_text }}
                                                                    </a>
                                                                @endif
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                @if($heroSliderImages->count() > 1)
                    <!-- Carousel Controls -->
                    <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Anterior</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Siguiente</span>
                    </button>
                    
                    <!-- Carousel Indicators -->
                    <div class="carousel-indicators">
                        @foreach($heroSliderImages as $index => $sliderImage)
                            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="{{ $index }}" 
                                    class="{{ $index === 0 ? 'active' : '' }}" 
                                    aria-current="{{ $index === 0 ? 'true' : 'false' }}" 
                                    aria-label="Slide {{ $index + 1 }}"></button>
                        @endforeach
                    </div>
                @endif
            </div>
        @else
            <!-- Default Hero Content -->
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <div class="hero-content">
                            <h1 class="display-4 fw-bold mb-4">
                                {{ $heroContent['title']->value ?? 'Descubre la Riqueza de la Biodiversidad' }}
                            </h1>
                            <p class="lead mb-4">
                                {{ $heroContent['subtitle']->value ?? 'Explora nuestra extensa base de datos de especies, ecosistemas y publicaciones científicas.' }}
                            </p>
                            <div class="d-flex gap-3 flex-wrap">
                                <a href="{{ $heroContent['button_primary_url']->value ?? '/biodiversity' }}" class="btn btn-warning btn-lg">
                                    @if($enableIcons)<i class="fas fa-search me-2"></i>@endif{{ $heroContent['button_primary_text']->value ?? 'Explorar Especies' }}
                                </a>
                                <a href="{{ $heroContent['button_secondary_url']->value ?? '/publications' }}" class="btn btn-outline-light btn-lg">
                                    @if($enableIcons)<i class="fas fa-book me-2"></i>@endif{{ $heroContent['button_secondary_text']->value ?? 'Publicaciones' }}
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 text-center">
                        <div class="hero-image">
                            @if($enableIcons)
                                @if(isset($heroContent['hero_image']) && $heroContent['hero_image']->value)
                                    <i class="{{ $heroContent['hero_image']->value }}" style="font-size: 12rem; opacity: 0.3;"></i>
                                @else
                                    <i class="fas fa-globe-americas" style="font-size: 12rem; opacity: 0.3;"></i>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </section>

    <!-- Search Section -->
    <section class="search-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="text-center mb-5">
                        <h2 class="fw-bold">{{ $searchContent['title']->value ?? '¿Qué especie buscas?' }}</h2>
                        <p class="text-muted">{{ $searchContent['subtitle']->value ?? 'Busca entre miles de especies registradas en nuestro sistema' }}</p>
                    </div>
                    <form action="/biodiversity" method="GET" class="d-flex gap-3">
                        <input type="text" name="search" class="form-control search-box" 
                               placeholder="{{ $searchContent['placeholder']->value ?? 'Buscar por nombre común o científico...' }}" 
                               value="{{ request('search') }}">
                        <button type="submit" class="btn search-btn">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Statistics Section -->
    <section class="py-5">
        <div class="container">
            <h2 class="section-title">{{ $statsContent['title']->value ?? 'Nuestra Biodiversidad en Números' }}</h2>
            <div class="row g-4">
                <div class="col-lg-3 col-md-6">
                    <div class="stats-card">
                        <div class="stats-icon" style="background: linear-gradient(135deg, #3498db, #2980b9);">
                            <i class="fas fa-paw"></i>
                        </div>
                        <div class="stats-number">{{ $totalBiodiversity }}</div>
                        <h5>{{ $statsContent['categories_title']->value ?? 'Categorías de Especies' }}</h5>
                        <p class="text-muted">{{ $statsContent['categories_description']->value ?? 'Diferentes grupos taxonómicos registrados' }}</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="stats-card">
                        <div class="stats-icon" style="background: linear-gradient(135deg, #27ae60, #229954);">
                            <i class="fas fa-book-open"></i>
                        </div>
                        <div class="stats-number">{{ $totalPublications }}</div>
                        <h5>{{ $statsContent['publications_title']->value ?? 'Publicaciones Científicas' }}</h5>
                        <p class="text-muted">{{ $statsContent['publications_description']->value ?? 'Investigaciones y estudios disponibles' }}</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="stats-card">
                        <div class="stats-icon" style="background: linear-gradient(135deg, #f39c12, #e67e22);">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div class="stats-number">{{ $endangeredCount }}</div>
                        <h5>{{ $statsContent['endangered_title']->value ?? 'Especies en Peligro' }}</h5>
                        <p class="text-muted">{{ $statsContent['endangered_description']->value ?? 'Requieren protección especial' }}</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="stats-card">
                        <div class="stats-icon" style="background: linear-gradient(135deg, #e74c3c, #c0392b);">
                            <i class="fas fa-skull-crossbones"></i>
                        </div>
                        <div class="stats-number">{{ $criticallyEndangeredCount }}</div>
                        <h5>{{ $statsContent['critical_title']->value ?? 'En Peligro Crítico' }}</h5>
                        <p class="text-muted">{{ $statsContent['critical_description']->value ?? 'Situación de conservación crítica' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Species Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <h2 class="section-title">{{ $featuredContent['title']->value ?? 'Especies Destacadas' }}</h2>
            <div class="row g-4">
                @foreach($featuredBiodiversity as $item)
                    <div class="col-lg-4 col-md-6">
                        <div class="species-card card">
                            <div class="species-image">
                                @if($item->image_path)
                                    <img src="{{ $item->getImageUrl() }}" 
                                         class="w-100 h-100" 
                                         style="object-fit: cover; cursor: pointer;" 
                                         alt="{{ $item->name }}"
                                         onclick="showImageModal('{{ $item->getImageUrl() }}', '{{ $item->name }}')">
                                    <div class="image-overlay">
                                        <small class="text-white">Click para ampliar</small>
                                    </div>
                                @else
                                    <i class="fas fa-leaf text-white" style="font-size: 4rem;"></i>
                                @endif
                                
                                @php
                                    $statusColors = [
                                        'EX' => 'danger',
                                        'EW' => 'danger', 
                                        'CR' => 'danger',
                                        'EN' => 'warning',
                                        'VU' => 'warning',
                                        'NT' => 'info',
                                        'LC' => 'success',
                                        'DD' => 'secondary',
                                        'NE' => 'secondary',
                                    ];
                                    $statusColor = $statusColors[$item->conservation_status] ?? 'secondary';
                                    $conservationStatuses = [
                                        'EX' => 'Extinta',
                                        'EW' => 'Extinta en Estado Silvestre',
                                        'CR' => 'En Peligro Crítico',
                                        'EN' => 'En Peligro',
                                        'VU' => 'Vulnerable',
                                        'NT' => 'Casi Amenazada',
                                        'LC' => 'Preocupación Menor',
                                        'DD' => 'Datos Insuficientes',
                                        'NE' => 'No Evaluada',
                                    ];
                                @endphp
                                
                                <span class="conservation-badge bg-{{ $statusColor }} text-white">
                                    {{ $conservationStatuses[$item->conservation_status] ?? $item->conservation_status }}
                                </span>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title fw-bold">{{ $item->name }}</h5>
                                <h6 class="card-subtitle mb-3 text-muted fst-italic">{{ $item->scientific_name }}</h6>
                                <p class="card-text text-muted">
                                    {{ Str::limit(strip_tags($item->description), 120) }}
                                </p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <a href="/biodiversity/{{ $item->id }}" class="btn btn-outline-primary btn-sm rounded-pill">
                                        <i class="fas fa-eye me-1"></i>Ver Detalles
                                    </a>
                                    <small class="text-muted">
                                        <i class="fas fa-map-marker-alt me-1"></i>{{ $item->habitat ?? 'Varios hábitats' }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="text-center mt-5">
                <a href="/biodiversity" class="btn btn-outline-primary btn-lg">
                    <i class="fas fa-th-large me-2"></i>{{ $featuredContent['view_all_text']->value ?? 'Ver Todas las Especies' }}
                </a>
            </div>
        </div>
    </section>

    <!-- Recent Publications Section -->
    <section class="py-5">
        <div class="container">
            <h2 class="section-title">{{ $publicationsContent['title']->value ?? 'Publicaciones Científicas Recientes' }}</h2>
            <div class="row">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="fw-bold">Título</th>
                                            <th class="fw-bold">Autor</th>
                                            <th class="fw-bold">Año</th>
                                            <th class="fw-bold">Revista</th>
                                            <th class="fw-bold">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($recentPublications as $publication)
                                            <tr>
                                                <td>
                                                    <strong>{{ Str::limit($publication->title, 60) }}</strong>
                                                </td>
                                                <td>{{ $publication->author }}</td>
                                                <td>
                                                    <span class="badge bg-secondary">{{ $publication->publication_year }}</span>
                                                </td>
                                                <td>{{ $publication->journal ?? 'N/A' }}</td>
                                                <td>
                                                    <a href="/publications/{{ $publication->id }}" 
                                                       class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-eye"></i> Ver
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer bg-white text-center">
                            <a href="/publications" class="btn btn-primary">
                                <i class="fas fa-book me-2"></i>{{ $publicationsContent['view_all_text']->value ?? 'Ver Todas las Publicaciones' }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action Section -->
    <section class="py-5" style="background: linear-gradient(135deg, var(--primary-green), var(--secondary-green)); color: white;">
        <div class="container text-center">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <h2 class="fw-bold mb-4">{{ $ctaContent['title']->value ?? 'Contribuye a la Conservación' }}</h2>
                    <p class="lead mb-4">
                        {{ $ctaContent['description']->value ?? 'La biodiversidad es un tesoro que debemos proteger. Únete a nuestros esfuerzos de conservación y investigación.' }}
                    </p>
                    <div class="d-flex gap-3 justify-content-center flex-wrap">
                        <a href="{{ $ctaContent['button_primary_url']->value ?? '#' }}" class="btn btn-warning btn-lg">
                            <i class="fas fa-hands-helping me-2"></i>{{ $ctaContent['button_primary_text']->value ?? 'Colaborar' }}
                        </a>
                        <a href="{{ $ctaContent['button_secondary_url']->value ?? '#' }}" class="btn btn-outline-light btn-lg">
                            <i class="fas fa-download me-2"></i>{{ $ctaContent['button_secondary_text']->value ?? 'Descargar Datos' }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
    
    // Add animation on scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);
    
    // Observe stats cards
    document.querySelectorAll('.stats-card, .species-card').forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(30px)';
        card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(card);
    });
    
    // Función para mostrar imagen en modal
    window.showImageModal = function(imageUrl, speciesName) {
        document.getElementById('modalImage').src = imageUrl;
        document.getElementById('imageModalLabel').textContent = speciesName;
        new bootstrap.Modal(document.getElementById('imageModal')).show();
    };
</script>
@endpush

@push('styles')
<style>
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
    
    /* Hero Slider Styles */
    .hero-slider {
        position: relative;
        height: 60vh;
        overflow: hidden;
    }
    
    .hero-slide {
        position: relative;
        height: 60vh;
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .hero-slide::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.4);
        z-index: 1;
    }
    
    .hero-slide-content {
        position: relative;
        z-index: 2;
        text-align: center;
        color: white;
        max-width: 800px;
        padding: 0 20px;
    }
    
    .hero-slide h1 {
        font-size: 3.5rem;
        font-weight: 700;
        margin-bottom: 1rem;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
    }
    
    .hero-slide p {
        font-size: 1.25rem;
        margin-bottom: 2rem;
        text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
    }
    
    .carousel-control-prev,
    .carousel-control-next {
        width: 5%;
        opacity: 0.7;
        transition: opacity 0.3s ease;
    }
    
    .carousel-control-prev:hover,
    .carousel-control-next:hover {
        opacity: 1;
    }
    
    .carousel-indicators {
        bottom: 30px;
    }
    
    .carousel-indicators [data-bs-target] {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        margin: 0 5px;
        background-color: rgba(255, 255, 255, 0.5);
        border: 2px solid white;
    }
    
    .carousel-indicators .active {
        background-color: white;
    }
    
    /* Overlay Image Styles */
    .overlay-image-container {
        position: relative;
        z-index: 2;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
    }
    
    .overlay-image-wrapper {
        position: relative;
        animation: fadeInUp 0.8s ease-out;
        display: flex;
        flex-direction: column;
        align-items: center;
        width: 100%;
    }
    
    .overlay-image {
        max-width: 100%;
        height: auto;
        border: 3px solid rgba(255, 255, 255, 0.2);
        transition: all 0.3s ease;
        display: block;
        margin: 0 auto;
    }
    
    .overlay-image:hover {
        transform: scale(1.05);
        border-color: rgba(255, 255, 255, 0.4);
    }
    
    .overlay-content {
        text-align: center;
        padding: 10px;
        font-size: calc(0.8rem + 0.5vw);
        line-height: 1.4;
    }
    
    .overlay-content p {
        margin-bottom: 8px;
        font-size: inherit;
    }
    
    .overlay-content .btn {
        font-size: calc(0.7rem + 0.3vw);
        padding: 6px 12px;
    }
    
    .overlay-left {
        text-align: left;
    }
    
    .overlay-right {
        text-align: right;
    }
    
    .overlay-center {
        text-align: center;
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @media (max-width: 768px) {
        .hero-slide h1 {
            font-size: 2.5rem;
        }
        
        .hero-slide p {
            font-size: 1rem;
        }
        
        .overlay-image-container {
            margin-top: 2rem;
            text-align: center;
        }
        
        .overlay-left,
        .overlay-right,
        .overlay-center {
            text-align: center;
        }
        
        .overlay-content {
            font-size: calc(0.7rem + 0.3vw);
            padding: 8px;
        }
        
        .overlay-content .btn {
            font-size: calc(0.6rem + 0.2vw);
            padding: 4px 8px;
        }
        
        .carousel-control-prev,
        .carousel-control-next {
            width: 10%;
        }
    }
</style>
@endpush

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