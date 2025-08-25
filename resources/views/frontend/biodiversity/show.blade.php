@extends('layouts.frontend')

@section('title', $biodiversity->name)

@section('content')

    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h1 class="page-title">{{ $biodiversity->name }}</h1>
                    <p class="page-subtitle">Información detallada sobre esta especie de biodiversidad</p>
                </div>
                <div class="col-lg-4">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/"><i class="fas fa-home"></i> Inicio</a></li>
                            <li class="breadcrumb-item"><a href="/biodiversity">Biodiversidad</a></li>
                            <li class="breadcrumb-item active">{{ $biodiversity->name }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <div class="container my-5">
        <div class="row">
            <!-- Información principal -->
            <div class="col-lg-8">
                <div class="species-detail-card">
                    <div class="species-hero">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <div class="species-image-container">
                                    @php
                                        $allImages = $biodiversity->getAllImageUrls();
                                    @endphp
                                    
                                    @if(count($allImages) > 0)
                                        <!-- Imagen principal -->
                                        <div class="main-image-wrapper">
                                            <img src="{{ $allImages[0] }}" alt="{{ $biodiversity->name }}" class="species-main-image" id="mainImage" style="cursor: pointer; width: 300px !important; height: 200px !important; object-fit: cover; border-radius: 8px;" onclick="showImageModal('{{ $allImages[0] }}', '{{ $biodiversity->name }}')" title="Haz clic para ampliar">
                                        </div>
                                        
                                        <!-- Galería de miniaturas horizontal -->
                                        @if(count($allImages) > 1)
                                            <div class="image-gallery-thumbnails mt-3" style="display: flex; gap: 10px; flex-wrap: wrap; justify-content: flex-start;">
                                                @foreach($allImages as $index => $imageUrl)
                                                    <div class="thumbnail-wrapper {{ $index === 0 ? 'active' : '' }}" onclick="changeMainImage('{{ $imageUrl }}', {{ $index }})" style="cursor: pointer; border: 2px solid {{ $index === 0 ? '#007bff' : 'transparent' }}; border-radius: 8px; transition: all 0.3s ease;">
                                                        <img src="{{ $imageUrl }}" alt="{{ $biodiversity->name }} - Imagen {{ $index + 1 }}" class="thumbnail-image" style="width: 80px; height: 60px; object-fit: cover; border-radius: 6px; display: block;">
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    @else
                                        <div class="no-image-placeholder">
                                            <i class="fas fa-leaf"></i>
                                            <span>Sin imagen disponible</span>
                                        </div>
                                    @endif
                                    
                                    @if($biodiversity->conservationStatus)
                                        @php
                                            $colorMap = [
                                                'danger' => '#dc3545',
                                                'warning' => '#ffc107',
                                                'success' => '#28a745',
                                                'info' => '#17a2b8',
                                                'secondary' => '#6c757d',
                                                'primary' => '#007bff'
                                            ];
                                            $bgColor = $colorMap[$biodiversity->conservationStatus->color] ?? '#6c757d';
                                        @endphp
                                        <div class="conservation-overlay">
                                            <span class="badge" style="background-color: {{ $bgColor }}; color: white; border: 2px solid {{ $bgColor }}; text-shadow: 1px 1px 2px rgba(0,0,0,0.7); font-weight: bold;">
                                                {{ $biodiversity->conservationStatus->code }} - {{ $biodiversity->conservationStatus->name }}
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="species-info">
                                    <h2 class="species-title">{{ $biodiversity->name }}</h2>
                                    <p class="scientific-name"><em>{{ $biodiversity->scientific_name }}</em></p>
                                    
                                    <div class="species-meta-grid">
                                        <div class="meta-item">
                                            <div class="meta-label"><i class="fas fa-crown me-2"></i>Reino</div>
                                            <div class="meta-value">
                                                @php
                                                    $kingdoms = [
                                                        'animalia' => 'Animalia',
                                                        'plantae' => 'Plantae',
                                                        'fungi' => 'Fungi',
                                                        'protista' => 'Protista',
                                                        'monera' => 'Monera',
                                                    ];
                                                @endphp
                                                {{ $kingdoms[$biodiversity->kingdom] ?? $biodiversity->kingdom }}
                                            </div>
                                        </div>
                                        
                                        @if($biodiversity->habitat)
                                            <div class="meta-item">
                                                <div class="meta-label"><i class="fas fa-map-marker-alt me-2"></i>Hábitat</div>
                                                <div class="meta-value">{{ $biodiversity->habitat }}</div>
                                            </div>
                                        @endif
                                        
                                        @if($biodiversity->conservationStatus)
                                            @php
                                                $colorMap = [
                                                    'danger' => '#dc3545',
                                                    'warning' => '#ffc107',
                                                    'success' => '#28a745',
                                                    'info' => '#17a2b8',
                                                    'secondary' => '#6c757d',
                                                    'primary' => '#007bff'
                                                ];
                                                $bgColor = $colorMap[$biodiversity->conservationStatus->color] ?? '#6c757d';
                                            @endphp
                                            <div class="meta-item">
                                                <div class="meta-label"><i class="fas fa-shield-alt me-2"></i>Estado de Conservación</div>
                                                <div class="meta-value">
                                                    <span class="badge" style="background-color: {{ $bgColor }}; color: white; border: 2px solid {{ $bgColor }}; text-shadow: 1px 1px 2px rgba(0,0,0,0.7); font-weight: bold;">
                                                        {{ $biodiversity->conservationStatus->code }} - {{ $biodiversity->conservationStatus->name }}
                                                    </span>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    @if($biodiversity->description)
                        <div class="species-description">
                            <h4><i class="fas fa-info-circle me-2"></i>Descripción</h4>
                            <div class="description-content">
                                {!! $biodiversity->description !!}
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Publicaciones relacionadas -->
                @if($biodiversity->publications->count() > 0)
                    <div class="related-publications-section">
                        <h4><i class="fas fa-book-open me-2"></i>Publicaciones Científicas Relacionadas</h4>
                        <div class="publications-grid">
                            @foreach($biodiversity->publications as $publication)
                                <div class="publication-card" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                                    <div class="publication-header">
                                        <h5 class="publication-title">
                                            <a href="/publications/{{ $publication->id }}">{{ Str::limit($publication->title, 80) }}</a>
                                        </h5>
                                        <div class="publication-meta">
                                            <span class="author"><i class="fas fa-user me-1"></i>{{ $publication->author }}</span>
                                            <span class="year"><i class="fas fa-calendar me-1"></i>{{ $publication->publication_year }}</span>
                                            @if($publication->journal)
                                                <span class="journal"><i class="fas fa-book me-1"></i>{{ $publication->journal }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    @if($publication->pivot->relevant_excerpt)
                                        <div class="excerpt-preview">
                                            <p class="excerpt-text">{{ Str::limit($publication->pivot->relevant_excerpt, 150) }}</p>
                                            <button type="button" class="btn btn-outline-info btn-sm" data-bs-toggle="modal" data-bs-target="#excerptModal{{ $publication->id }}">
                                                <i class="fas fa-quote-left me-1"></i>Ver extracto completo
                                            </button>
                                            
                                            <!-- Modal para mostrar el extracto -->
                                            <div class="modal fade" id="excerptModal{{ $publication->id }}" tabindex="-1" aria-labelledby="excerptModalLabel{{ $publication->id }}" aria-hidden="true">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="excerptModalLabel{{ $publication->id }}">Extracto Relevante</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <h6 class="text-primary">{{ $publication->title }}</h6>
                                                            <p class="text-muted mb-3">{{ $publication->author }} ({{ $publication->publication_year }})</p>
                                                            <div class="excerpt-content">
                                                                <p>{{ $publication->pivot->relevant_excerpt }}</p>
                                                                @if($publication->pivot->page_reference)
                                                                    <p class="text-muted"><small><i class="fas fa-bookmark me-1"></i>Referencia: {{ $publication->pivot->page_reference }}</small></p>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <a href="/publications/{{ $publication->id }}" class="btn btn-primary">
                                                                <i class="fas fa-external-link-alt me-1"></i>Ver publicación completa
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    
                                    <div class="publication-actions">
                                        <a href="/publications/{{ $publication->id }}" class="btn btn-outline-primary btn-sm rounded-pill">
                                            <i class="fas fa-eye me-1"></i>Ver Detalles
                                        </a>
                                        @if($publication->doi)
                                            <a href="https://doi.org/{{ $publication->doi }}" class="btn btn-outline-secondary btn-sm" target="_blank">
                                                <i class="fas fa-external-link-alt me-1"></i>DOI
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="no-publications-section">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>No hay publicaciones científicas relacionadas con esta especie.
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Estado de Conservación -->
                <div class="sidebar-card" data-aos="fade-left">
                    <div class="sidebar-header">
                        <h5><i class="fas fa-shield-alt me-2"></i>Estado de Conservación</h5>
                    </div>
                    <div class="sidebar-content">
                        @if($biodiversity->conservationStatus)
                             @php
                                 $colorMap = [
                                     'danger' => '#dc3545',
                                     'warning' => '#ffc107',
                                     'success' => '#28a745',
                                     'info' => '#17a2b8',
                                     'secondary' => '#6c757d',
                                     'primary' => '#007bff'
                                 ];
                                 $bgColor = $colorMap[$biodiversity->conservationStatus->color] ?? '#6c757d';
                             @endphp
                             <div class="conservation-status-card">
                                 <div class="status-badge" style="background-color: {{ $bgColor }}; color: white; border: 2px solid {{ $bgColor }}; text-shadow: 1px 1px 2px rgba(0,0,0,0.7); font-weight: bold; padding: 10px 15px; border-radius: 8px;">
                                     <i class="fas fa-shield-alt me-2"></i>
                                     {{ $biodiversity->conservationStatus->code }} - {{ $biodiversity->conservationStatus->name }}
                                 </div>
                                 @if($biodiversity->conservationStatus->description)
                                     <p class="status-description">{{ $biodiversity->conservationStatus->description }}</p>
                                 @endif
                             </div>
                         @else
                            <div class="conservation-status-card">
                                <div class="status-badge status-secondary">
                                    <i class="fas fa-question-circle me-2"></i>
                                    No Evaluado
                                </div>
                                <p class="status-description">No se ha evaluado el estado de conservación de esta especie.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Información Adicional -->
                <div class="sidebar-card" data-aos="fade-left" data-aos-delay="100">
                    <div class="sidebar-header">
                        <h5><i class="fas fa-info-circle me-2"></i>Información Adicional</h5>
                    </div>
                    <div class="sidebar-content">
                        <div class="info-grid">
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="fas fa-crown me-2"></i>Reino
                                </div>
                                <div class="info-value">{{ $kingdoms[$biodiversity->kingdom] ?? $biodiversity->kingdom }}</div>
                            </div>
                            @if($biodiversity->habitat)
                                <div class="info-item">
                                    <div class="info-label">
                                        <i class="fas fa-tree me-2"></i>Hábitat
                                    </div>
                                    <div class="info-value">{{ $biodiversity->habitat }}</div>
                                </div>
                            @endif
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="fas fa-book me-2"></i>Publicaciones
                                </div>
                                <div class="info-value">{{ $biodiversity->publications->count() }} relacionadas</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Especies Similares -->
                @if(isset($similarBiodiversity) && $similarBiodiversity->count() > 0)
                    <div class="sidebar-card" data-aos="fade-left" data-aos-delay="200">
                        <div class="sidebar-header">
                            <h5><i class="fas fa-sitemap me-2"></i>Especies Similares</h5>
                        </div>
                        <div class="sidebar-content">
                            <div class="similar-species-list">
                                @foreach($similarBiodiversity as $similar)
                                    <div class="similar-species-item">
                                        <div class="species-thumbnail">
                                            @if($similar->image_path)
                                    <img src="{{ $similar->getImageUrl() }}" alt="{{ $similar->name }}" class="species-thumb-img" style="cursor: pointer; width: 100px !important; height: 100px !important; border-radius: 50% !important; object-fit: cover !important;" onclick="showImageModal('{{ $similar->getImageUrl() }}', '{{ $similar->name }}')">
                                    <div class="image-overlay-small">
                                        <small class="text-white">Click para ampliar</small>
                                    </div>
                                            @else
                                                <div class="species-thumb-placeholder" style="width: 100px !important; height: 100px !important; font-size: 40px !important; border-radius: 50% !important;">
                                                    <i class="fas fa-leaf"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="species-info">
                                            <h6 class="species-name">{{ Str::limit($similar->name, 25) }}</h6>
                                            <p class="species-scientific"><em>{{ Str::limit($similar->scientific_name, 30) }}</em></p>
                                            <div class="species-badges">
                                                <span class="badge badge-outline-primary">{{ $kingdoms[$similar->kingdom] ?? $similar->kingdom }}</span>
                                                @if($similar->conservationStatus)
                                                     @php
                                                         $colorMap = [
                                                             'danger' => '#dc3545',
                                                             'warning' => '#ffc107',
                                                             'success' => '#28a745',
                                                             'info' => '#17a2b8',
                                                             'secondary' => '#6c757d',
                                                             'primary' => '#007bff'
                                                         ];
                                                         $bgColor = $colorMap[$similar->conservationStatus->color] ?? '#6c757d';
                                                     @endphp
                                                     <span class="badge" style="background-color: {{ $bgColor }}; color: white; border: 2px solid {{ $bgColor }}; text-shadow: 1px 1px 2px rgba(0,0,0,0.7); font-weight: bold;">
                                                         {{ $similar->conservationStatus->code }}
                                                     </span>
                                                 @else
                                                     <span class="badge badge-secondary">
                                                         NE
                                                     </span>
                                                 @endif
                                            </div>
                                        </div>
                                        <div class="species-action">
                                            <a href="/biodiversity/{{ $similar->id }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@stop

@push('scripts')
<script>
    window.showImageModal = function(imageUrl, speciesName) {
        // Verificar directamente si los elementos existen
        const modalImage = document.getElementById('showModalImage');
        const modalLabel = document.getElementById('showImageModalLabel');
        const modalElement = document.getElementById('showImageModal');
        
        console.log('Modal elements found:', {
            modalImage: !!modalImage,
            modalLabel: !!modalLabel,
            modalElement: !!modalElement
        });
        
        if (!modalImage || !modalLabel || !modalElement) {
            console.error('Elementos del modal no encontrados:', {
                modalImage: !!modalImage,
                modalLabel: !!modalLabel,
                modalElement: !!modalElement
            });
            return;
        }
        
        try {
                
                if (typeof bootstrap === 'undefined') {
                    console.error('Bootstrap no está disponible');
                    return;
                }
                
            modalImage.src = imageUrl;
            modalLabel.textContent = speciesName;
            new bootstrap.Modal(modalElement).show();
            
        } catch (error) {
            console.error('Error al mostrar modal:', error);
        }
    };
    
    window.changeMainImage = function(imageUrl, index) {
        try {
            const mainImage = document.getElementById('mainImage');
            if (mainImage) {
                mainImage.src = imageUrl;
                mainImage.onclick = function() { showImageModal(imageUrl, '{{ $biodiversity->name }}'); };
            }
            
            const thumbnails = document.querySelectorAll('.thumbnail-wrapper');
            thumbnails.forEach((thumb, i) => {
                if (i === index) {
                    thumb.classList.add('active');
                    thumb.style.border = '2px solid #007bff';
                } else {
                    thumb.classList.remove('active');
                    thumb.style.border = '2px solid transparent';
                }
            });
        } catch (error) {
            console.error('Error al cambiar imagen:', error);
        }
    };

    document.addEventListener('DOMContentLoaded', function() {
        // Initialize AOS (Animate On Scroll)
        if (typeof AOS !== 'undefined') {
            AOS.init({
                duration: 800,
                easing: 'ease-in-out',
                once: true,
                offset: 100
            });
        }
        
        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
        
        // Enhanced modal functionality
        const modals = document.querySelectorAll('.modal');
        modals.forEach(modal => {
            modal.addEventListener('shown.bs.modal', function () {
                // Focus on modal content when opened
                const modalBody = this.querySelector('.modal-body');
                if (modalBody) {
                    modalBody.focus();
                }
            });
        });
        
        // Lazy loading for images
        const images = document.querySelectorAll('img[data-src]');
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.classList.remove('lazy');
                    imageObserver.unobserve(img);
                }
            });
        });
        
        images.forEach(img => imageObserver.observe(img));
        
        // Add entrance animations to cards
        const cards = document.querySelectorAll('.publication-card, .sidebar-card');
        const cardObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, {
            threshold: 0.1
        });
        
        cards.forEach(card => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            cardObserver.observe(card);
        });
        
        // Enhanced hover effects for species cards
        const speciesCards = document.querySelectorAll('.similar-species-item');
        speciesCards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateX(10px) scale(1.02)';
                this.style.boxShadow = '0 8px 25px rgba(0,0,0,0.15)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateX(0) scale(1)';
                this.style.boxShadow = 'none';
            });
        });
        
        // Image zoom effect
        const mainImage = document.querySelector('.species-main-image');
        if (mainImage) {
            mainImage.addEventListener('click', function() {
                // Create modal for image zoom
                const modal = document.createElement('div');
                modal.className = 'modal fade';
                modal.innerHTML = `
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Imagen de ${document.querySelector('.species-title').textContent}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body text-center">
                                <img src="${this.src}" class="img-fluid" alt="${this.alt}">
                            </div>
                        </div>
                    </div>
                `;
                document.body.appendChild(modal);
                
                const bsModal = new bootstrap.Modal(modal);
                bsModal.show();
                
                modal.addEventListener('hidden.bs.modal', function() {
                    document.body.removeChild(modal);
                });
            });
            
            mainImage.style.cursor = 'pointer';
            mainImage.title = 'Haz clic para ampliar';
        }
        
        // Copy scientific name functionality
        const scientificName = document.querySelector('.scientific-name');
        if (scientificName) {
            scientificName.addEventListener('click', function() {
                navigator.clipboard.writeText(this.textContent).then(() => {
                    // Show toast notification
                    const toast = document.createElement('div');
                    toast.className = 'toast-notification';
                    toast.textContent = 'Nombre científico copiado al portapapeles';
                    toast.style.cssText = `
                        position: fixed;
                        top: 20px;
                        right: 20px;
                        background: #28a745;
                        color: white;
                        padding: 1rem;
                        border-radius: 8px;
                        z-index: 9999;
                        opacity: 0;
                        transition: opacity 0.3s ease;
                    `;
                    document.body.appendChild(toast);
                    
                    setTimeout(() => toast.style.opacity = '1', 100);
                    setTimeout(() => {
                        toast.style.opacity = '0';
                        setTimeout(() => document.body.removeChild(toast), 300);
                    }, 3000);
                });
            });
            
            scientificName.style.cursor = 'pointer';
            scientificName.title = 'Haz clic para copiar';
        }
    });
</script>
@endpush

@section('css')
<style>
    /* Page Header Styles */
    .page-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 3rem 0;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
    }
    
    .page-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="%23ffffff" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>') repeat;
        opacity: 0.3;
    }
    
    .page-header .container {
        position: relative;
        z-index: 2;
    }
    
    .page-title {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
    }
    
    .page-subtitle {
        font-size: 1.2rem;
        opacity: 0.9;
        margin-bottom: 1rem;
    }
    
    .breadcrumb {
        background: rgba(255,255,255,0.1);
        border-radius: 25px;
        padding: 0.5rem 1rem;
        backdrop-filter: blur(10px);
    }
    
    .breadcrumb-item a {
        color: rgba(255,255,255,0.8);
        text-decoration: none;
    }
    
    .breadcrumb-item.active {
        color: white;
    }
    
    /* Species Detail Card */
    .species-detail-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        overflow: hidden;
        margin-bottom: 2rem;
    }
    
    .species-hero {
        padding: 2rem;
    }
    
    .species-image-container {
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .species-main-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }
    
    .species-main-image:hover {
        transform: scale(1.05);
    }
    
    .no-image-placeholder {
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: #6c757d;
        font-size: 1.2rem;
    }
    
    .no-image-placeholder i {
        font-size: 3rem;
        margin-bottom: 0.5rem;
    }
    
    .conservation-overlay {
        position: absolute;
        top: 15px;
        right: 15px;
        z-index: 10;
    }
    
    .conservation-extinto,
    .conservation-extinto-en-estado-silvestre,
    .conservation-en-peligro-crítico {
        background: rgba(220, 53, 69, 0.9);
        color: white;
    }
    
    .conservation-en-peligro,
    .conservation-vulnerable {
        background: rgba(255, 193, 7, 0.9);
        color: #212529;
    }
    
    .conservation-casi-amenazado {
        background: rgba(23, 162, 184, 0.9);
        color: white;
    }
    
    .conservation-preocupación-menor {
        background: rgba(40, 167, 69, 0.9);
        color: white;
    }
    
    .conservation-datos-insuficientes,
    .conservation-no-evaluado {
        background: rgba(108, 117, 125, 0.9);
        color: white;
    }
    
    .species-info {
        padding: 1.5rem 0;
    }
    
    .species-title {
        font-size: 2.2rem;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 0.5rem;
    }
    
    .scientific-name {
        font-size: 1.3rem;
        color: #6c757d;
        margin-bottom: 1.5rem;
    }
    
    .species-meta-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }
    
    .meta-item {
        background: #f8f9fa;
        padding: 1rem;
        border-radius: 10px;
        transition: transform 0.2s ease;
    }
    
    .meta-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    
    .meta-label {
        font-size: 0.9rem;
        color: #6c757d;
        margin-bottom: 0.5rem;
        font-weight: 600;
        display: flex;
        align-items: center;
    }
    
    .meta-value {
        font-size: 1rem;
        font-weight: 600;
        color: #2c3e50;
    }
    
    .species-description {
        background: #f8f9fa;
        padding: 2rem;
        border-radius: 15px;
        margin-top: 2rem;
    }
    
    .species-description h4 {
        color: #2c3e50;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
    }
    
    .description-content {
        line-height: 1.8;
        color: #495057;
    }
    
    /* Related Publications */
    .related-publications-section {
        margin-top: 3rem;
    }
    
    .related-publications-section h4 {
        color: #2c3e50;
        margin-bottom: 2rem;
        font-weight: 700;
        display: flex;
        align-items: center;
    }
    
    .publications-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 1.5rem;
    }
    
    .publication-card {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        border: 1px solid #e9ecef;
    }
    
    .publication-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.15);
    }
    
    .publication-header {
        margin-bottom: 1rem;
    }
    
    .publication-title a {
        color: #2c3e50;
        text-decoration: none;
        font-weight: 600;
        line-height: 1.4;
    }
    
    .publication-title a:hover {
        color: #667eea;
    }
    
    .publication-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        margin-top: 0.5rem;
    }
    
    .publication-meta span {
        font-size: 0.85rem;
        color: #6c757d;
        display: flex;
        align-items: center;
    }
    
    .excerpt-preview {
        background: #f8f9fa;
        padding: 1rem;
        border-radius: 10px;
        margin-bottom: 1rem;
    }
    
    .excerpt-text {
        font-size: 0.9rem;
        line-height: 1.6;
        color: #495057;
        margin-bottom: 0.5rem;
    }
    
    .publication-actions {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }
    
    .no-publications-section {
        text-align: center;
        padding: 3rem 0;
    }
    
    /* Sidebar Styles */
    .sidebar-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        margin-bottom: 2rem;
        overflow: hidden;
        border: 1px solid #e9ecef;
    }
    
    .sidebar-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 1rem 1.5rem;
        font-weight: 600;
    }
    
    .sidebar-header h5 {
        margin: 0;
        display: flex;
        align-items: center;
    }
    
    .sidebar-content {
        padding: 1.5rem;
    }
    
    .conservation-status-card {
        text-align: center;
    }
    
    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.75rem 1.5rem;
        border-radius: 25px;
        font-weight: 600;
        font-size: 1rem;
        margin-bottom: 1rem;
        border: 2px solid transparent;
    }
    
    .status-badge.status-success {
        background: #d4edda;
        color: #155724;
        border-color: #c3e6cb;
    }
    
    .status-badge.status-warning {
        background: #fff3cd;
        color: #856404;
        border-color: #ffeaa7;
    }
    
    .status-badge.status-danger {
        background: #f8d7da;
        color: #721c24;
        border-color: #f5c6cb;
    }
    
    .status-badge.status-info {
        background: #d1ecf1;
        color: #0c5460;
        border-color: #bee5eb;
    }
    
    .status-badge.status-secondary {
        background: #e2e3e5;
        color: #383d41;
        border-color: #d6d8db;
    }
    
    .status-description {
        font-size: 0.9rem;
        line-height: 1.6;
        color: #495057;
        margin-bottom: 0;
    }
    
    .info-grid {
        display: grid;
        gap: 1rem;
    }
    
    .info-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem;
        background: #f8f9fa;
        border-radius: 8px;
    }
    
    .info-label {
        font-weight: 600;
        color: #495057;
        display: flex;
        align-items: center;
    }
    
    .info-value {
        font-weight: 500;
        color: #2c3e50;
    }
    
    .similar-species-list {
        display: grid;
        gap: 1rem;
    }
    
    .similar-species-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        background: #f8f9fa;
        border-radius: 10px;
        transition: all 0.2s ease;
    }
    
    .similar-species-item:hover {
        background: #e9ecef;
        transform: translateX(5px);
    }
    
    .species-thumbnail {
        flex-shrink: 0;
    }
    
    .species-thumb-img {
        width: 15px !important;
        height: 15px !important;
        border-radius: 50%;
        object-fit: cover;
    }
    
    .species-thumb-placeholder {
        width: 15px;
        height: 15px;
        border-radius: 50%;
        background: #dee2e6;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #6c757d;
        font-size: 8px;
    }
    
    .species-info {
        flex-grow: 1;
        min-width: 0;
    }
    
    .species-name {
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 0.25rem;
    }
    
    .species-scientific {
        font-size: 0.85rem;
        color: #6c757d;
        margin-bottom: 0.5rem;
    }
    
    .species-badges {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }
    
    .badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
        border-radius: 12px;
    }
    
    .badge-outline-primary {
        color: #667eea;
        border: 1px solid #667eea;
        background: transparent;
    }
    
    .species-action {
        flex-shrink: 0;
    }
    
    /* Responsive Design */
    @media (max-width: 768px) {
        .page-title {
            font-size: 2rem;
        }
        
        .species-title {
            font-size: 1.8rem;
        }
        
        .species-meta-grid {
            grid-template-columns: 1fr;
        }
        
        .publications-grid {
            grid-template-columns: 1fr;
        }
        
        .publication-meta {
            flex-direction: column;
            gap: 0.5rem;
        }
        
        .similar-species-item {
            flex-direction: column;
            text-align: center;
        }
        
        .species-image-container {
            max-width: 100%;
        }
        
        .thumbnail-wrapper {
            width: 50px;
            height: 50px;
        }
        
        .species-thumb-img {
            width: 15px !important;
            height: 15px !important;
        }
        
        .species-thumb-placeholder {
            width: 15px !important;
            height: 15px !important;
            font-size: 8px !important;
        }
        
        .image-gallery-thumbnails {
            gap: 8px;
        }
    }
    
    .species-main-image {
        transition: transform 0.3s ease;
    }
    
    .species-thumbnail {
        position: relative;
        overflow: hidden;
    }
    
    .image-overlay-small {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(transparent, rgba(0,0,0,0.7));
        padding: 10px 5px 5px;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .species-thumbnail:hover .image-overlay-small {
        opacity: 1;
    }
    
    /* Estilos para galería de múltiples imágenes */
    .image-gallery-thumbnails {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        justify-content: center;
    }
    
    .thumbnail-wrapper {
        width: 60px;
        height: 60px;
        border-radius: 8px;
        overflow: hidden;
        cursor: pointer;
        border: 2px solid transparent;
        transition: all 0.3s ease;
        position: relative;
    }
    
    .thumbnail-wrapper:hover {
        border-color: #007bff;
        transform: scale(1.05);
    }
    
    .thumbnail-wrapper.active {
        border-color: #28a745;
        box-shadow: 0 0 10px rgba(40, 167, 69, 0.5);
    }
    
    .thumbnail-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }
    
    .thumbnail-wrapper:hover .thumbnail-image {
        transform: scale(1.1);
    }
    
    .main-image-wrapper {
        position: relative;
    }
</style>

<!-- Modal para mostrar imagen en tamaño completo -->
<div class="modal fade" id="showImageModal" tabindex="-1" aria-labelledby="showImageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content bg-transparent border-0">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title text-white" id="showImageModalLabel"></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-0">
                <img id="showModalImage" src="" alt="" class="img-fluid rounded" style="max-height: 80vh; object-fit: contain;">
            </div>
        </div>
    </div>
</div>



@stop