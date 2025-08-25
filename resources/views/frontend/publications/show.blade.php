@extends('layouts.frontend')

@section('title', $publication->title)

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h1 class="page-title">{{ Str::limit($publication->title, 60) }}</h1>
                    <p class="page-subtitle">Publicación científica sobre biodiversidad</p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/">Inicio</a></li>
                            <li class="breadcrumb-item"><a href="/publications">Publicaciones</a></li>
                            <li class="breadcrumb-item active">{{ Str::limit($publication->title, 30) }}</li>
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
                <div class="publication-detail-card">
                    <div class="publication-header">
                        <h2 class="publication-title">{{ $publication->title }}</h2>
                        <div class="publication-meta">
                            <div class="meta-item">
                                <i class="fas fa-user"></i>
                                <span><strong>Autor:</strong> {{ $publication->author }}</span>
                            </div>
                            <div class="meta-item">
                                <i class="fas fa-calendar"></i>
                                <span><strong>Año:</strong> {{ $publication->publication_year }}</span>
                            </div>
                            @if($publication->journal)
                                <div class="meta-item">
                                    <i class="fas fa-book"></i>
                                    <span><strong>Revista:</strong> {{ $publication->journal }}</span>
                                </div>
                            @endif
                            @if($publication->doi)
                                <div class="meta-item">
                                    <i class="fas fa-link"></i>
                                    <span><strong>DOI:</strong> <a href="https://doi.org/{{ $publication->doi }}" target="_blank" class="doi-link">{{ $publication->doi }}</a></span>
                                </div>
                            @endif
                        </div>
                        
                        <div class="publication-actions">
                            @if($publication->pdf_path && Storage::disk('public')->exists($publication->pdf_path))
                    <a href="{{ Storage::disk('public')->url($publication->pdf_path) }}" class="btn btn-primary" target="_blank">
                        <i class="fas fa-file-pdf me-2"></i>Descargar PDF
                    </a>
                @endif
                            @if($publication->doi)
                                <a href="https://doi.org/{{ $publication->doi }}" class="btn btn-outline-primary" target="_blank">
                                    <i class="fas fa-external-link-alt me-2"></i>Ver en DOI
                                </a>
                            @endif
                        </div>
                    </div>
                    
                    @if($publication->abstract)
                        <div class="publication-abstract">
                            <h4><i class="fas fa-file-alt me-2"></i>Resumen</h4>
                            <div class="abstract-content">
                                {!! $publication->abstract !!}
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Categorías de biodiversidad relacionadas -->
                @if($publication->biodiversityCategories->count() > 0)
                    <div class="related-species-section">
                        <h4><i class="fas fa-leaf me-2"></i>Especies de Biodiversidad Relacionadas</h4>
                        <div class="species-grid">
                            @foreach($publication->biodiversityCategories as $biodiversity)
                                <div class="species-card" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                                    <div class="species-image">
                                        @if($biodiversity->getFirstMedia('images'))
                                            <img src="{{ $biodiversity->getFirstMediaUrl('images') }}" alt="{{ $biodiversity->name }}">
                                        @else
                                            <div class="no-image">
                                                <i class="fas fa-leaf"></i>
                                            </div>
                                        @endif
                                        <div class="conservation-badge">
                                            @php
                                                $conservationStatuses = [
                                                    'EX' => 'Extinto',
                                                    'EW' => 'Extinto en Estado Silvestre',
                                                    'CR' => 'En Peligro Crítico',
                                                    'EN' => 'En Peligro',
                                                    'VU' => 'Vulnerable',
                                                    'NT' => 'Casi Amenazado',
                                                    'LC' => 'Preocupación Menor',
                                                    'DD' => 'Datos Insuficientes',
                                                    'NE' => 'No Evaluado',
                                                ];
                                                $statusText = $conservationStatuses[$biodiversity->conservation_status] ?? $biodiversity->conservation_status;
                                                $statusClass = strtolower(str_replace(' ', '-', $statusText));
                                            @endphp
                                            <span class="badge conservation-{{ $statusClass }}">
                                                {{ $statusText }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="species-content">
                                        <h5 class="species-name">{{ $biodiversity->name }}</h5>
                                        <p class="scientific-name"><em>{{ $biodiversity->scientific_name }}</em></p>
                                        <div class="species-meta">
                                            @php
                                                $kingdoms = [
                                                    'animalia' => 'Animalia',
                                                    'plantae' => 'Plantae',
                                                    'fungi' => 'Fungi',
                                                    'protista' => 'Protista',
                                                    'monera' => 'Monera',
                                                ];
                                            @endphp
                                            <span class="kingdom"><i class="fas fa-crown me-1"></i>{{ $kingdoms[$biodiversity->kingdom] ?? $biodiversity->kingdom }}</span>
                                        </div>
                                        
                                        @if($biodiversity->pivot->relevant_excerpt)
                                            <div class="excerpt-section">
                                                <button type="button" class="btn btn-outline-info btn-sm" data-toggle="modal" data-target="#excerptModal{{ $biodiversity->id }}">
                                                    <i class="fas fa-quote-left me-1"></i>Ver extracto
                                                </button>
                                                
                                                <!-- Modal para mostrar el extracto -->
                                                <div class="modal fade" id="excerptModal{{ $biodiversity->id }}" tabindex="-1" role="dialog" aria-labelledby="excerptModalLabel{{ $biodiversity->id }}" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="excerptModalLabel{{ $biodiversity->id }}">Extracto sobre "{{ $biodiversity->name }}"</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <p>{{ $biodiversity->pivot->relevant_excerpt }}</p>
                                                                @if($biodiversity->pivot->page_reference)
                                                                    <p class="text-muted">Referencia: {{ $biodiversity->pivot->page_reference }}</p>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        
                                        <a href="/biodiversity/{{ $biodiversity->id }}" class="btn btn-outline-primary btn-sm rounded-pill">
                                            <i class="fas fa-eye me-1"></i>Ver Detalles
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="no-species-section">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>No hay categorías de biodiversidad relacionadas con esta publicación.
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Información adicional -->
                <div class="sidebar-card">
                    <div class="sidebar-header">
                        <h4><i class="fas fa-info-circle me-2"></i>Información Adicional</h4>
                    </div>
                    <div class="sidebar-content">
                        <div class="info-grid">
                            <div class="info-item">
                                <div class="info-label"><i class="fas fa-calendar me-2"></i>Año de Publicación</div>
                                <div class="info-value">{{ $publication->publication_year }}</div>
                            </div>
                            
                            @if($publication->journal)
                                <div class="info-item">
                                    <div class="info-label"><i class="fas fa-book me-2"></i>Revista</div>
                                    <div class="info-value">{{ $publication->journal }}</div>
                                </div>
                            @endif
                            
                            <div class="info-item">
                                <div class="info-label"><i class="fas fa-leaf me-2"></i>Especies Relacionadas</div>
                                <div class="info-value">{{ $publication->biodiversityCategories->count() }} especies</div>
                            </div>
                            
                            @if($publication->pdf_path && Storage::disk('public')->exists($publication->pdf_path))
                            <div class="info-item">
                                <div class="info-label"><i class="fas fa-file-pdf me-2"></i>Documento</div>
                                <div class="info-value">
                                    <a href="{{ Storage::disk('public')->url($publication->pdf_path) }}" class="download-link" target="_blank">
                                        Disponible en PDF
                                    </a>
                                </div>
                            </div>
                        @endif
                        </div>
                    </div>
                </div>

                <!-- Publicaciones similares -->
                @if($similarPublications->count() > 0)
                    <div class="sidebar-card">
                        <div class="sidebar-header">
                            <h4><i class="fas fa-book-open me-2"></i>Publicaciones Similares</h4>
                        </div>
                        <div class="sidebar-content">
                            <div class="similar-publications">
                                @foreach($similarPublications as $similar)
                                    <div class="similar-publication-item">
                                        <h6 class="similar-title">
                                            <a href="/publications/{{ $similar }}">{{ Str::limit($similar->title, 60) }}</a>
                                        </h6>
                                        <div class="similar-meta">
                                            <span class="author"><i class="fas fa-user me-1"></i>{{ $similar->author }}</span>
                                            <span class="year"><i class="fas fa-calendar me-1"></i>{{ $similar->publication_year }}</span>
                                            @if($similar->journal)
                                                <span class="journal"><i class="fas fa-book me-1"></i>{{ Str::limit($similar->journal, 30) }}</span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @else
                    <div class="sidebar-card">
                        <div class="sidebar-header">
                            <h4><i class="fas fa-book-open me-2"></i>Publicaciones Similares</h4>
                        </div>
                        <div class="sidebar-content">
                            <div class="alert alert-info mb-0">
                                <i class="fas fa-info-circle me-2"></i>No se encontraron publicaciones similares.
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@stop

@section('js')
<script>
    // Initialize AOS (Animate On Scroll)
    AOS.init({
        duration: 800,
        easing: 'ease-in-out',
        once: true,
        offset: 100
    });
    
    $(document).ready(function() {
        // Smooth scrolling for anchor links
        $('a[href^="#"]').on('click', function(event) {
            var target = $(this.getAttribute('href'));
            if( target.length ) {
                event.preventDefault();
                $('html, body').stop().animate({
                    scrollTop: target.offset().top - 100
                }, 1000);
            }
        });
        
        // Enhanced modal functionality
        $('.modal').on('show.bs.modal', function() {
            $('body').addClass('modal-open-custom');
        });
        
        $('.modal').on('hidden.bs.modal', function() {
            $('body').removeClass('modal-open-custom');
        });
        
        // Copy DOI to clipboard functionality
        $('.doi-link').on('click', function(e) {
            if (e.ctrlKey || e.metaKey) {
                e.preventDefault();
                const doi = $(this).text();
                navigator.clipboard.writeText(doi).then(function() {
                    // Show success message
                    const originalText = $(e.target).text();
                    $(e.target).text('¡DOI copiado!');
                    setTimeout(() => {
                        $(e.target).text(originalText);
                    }, 2000);
                });
            }
        });
        
        // Lazy loading for images
        if ('IntersectionObserver' in window) {
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
            
            document.querySelectorAll('img[data-src]').forEach(img => {
                imageObserver.observe(img);
            });
        }
        
        // Add entrance animations to cards
        $('.species-card, .sidebar-card').each(function(index) {
            $(this).css('animation-delay', (index * 0.1) + 's');
        });
        
        // Enhanced hover effects
        $('.species-card').hover(
            function() {
                $(this).find('.species-image img').addClass('hovered');
            },
            function() {
                $(this).find('.species-image img').removeClass('hovered');
            }
        );
    });
</script>

<style>
    .modal-open-custom {
        overflow: hidden;
    }
    
    .species-image img.hovered {
        transform: scale(1.05);
    }
    
    .lazy {
        opacity: 0;
        transition: opacity 0.3s;
    }
    
    .lazy.loaded {
        opacity: 1;
    }
</style>
@endsection

@section('css')
<style>
    /* Publication Detail Styles */
    .publication-detail-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        overflow: hidden;
        margin-bottom: 2rem;
    }
    
    .publication-header {
        padding: 2rem;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    
    .publication-title {
        font-size: 1.8rem;
        font-weight: 700;
        margin-bottom: 1.5rem;
        line-height: 1.3;
    }
    
    .publication-meta {
        display: grid;
        gap: 1rem;
        margin-bottom: 2rem;
    }
    
    .meta-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.95rem;
    }
    
    .meta-item i {
        width: 20px;
        opacity: 0.8;
    }
    
    .doi-link {
        color: #ffd700;
        text-decoration: none;
        font-weight: 500;
    }
    
    .doi-link:hover {
        color: #ffed4e;
        text-decoration: underline;
    }
    
    .publication-actions {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }
    
    .publication-abstract {
        padding: 2rem;
    }
    
    .publication-abstract h4 {
        color: #2d3748;
        margin-bottom: 1.5rem;
        font-weight: 600;
    }
    
    .abstract-content {
        background: #f8fafc;
        padding: 1.5rem;
        border-radius: 10px;
        border-left: 4px solid #667eea;
        line-height: 1.7;
        color: #4a5568;
    }
    
    /* Related Species Styles */
    .related-species-section {
        margin-top: 2rem;
        padding: 2rem;
        background: white;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }
    
    .related-species-section h4 {
        color: #2d3748;
        margin-bottom: 2rem;
        font-weight: 600;
    }
    
    .species-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1.5rem;
    }
    
    .species-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        border: 1px solid #e2e8f0;
    }
    
    .species-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.15);
    }
    
    .species-image {
        position: relative;
        height: 200px;
        overflow: hidden;
    }
    
    .species-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }
    
    .species-card:hover .species-image img {
        transform: scale(1.05);
    }
    
    .no-image {
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #667eea;
        font-size: 3rem;
    }
    
    .conservation-badge {
        position: absolute;
        top: 10px;
        right: 10px;
    }
    
    .conservation-badge .badge {
        font-size: 0.7rem;
        padding: 0.4rem 0.8rem;
        border-radius: 20px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .conservation-extinto { background: #dc2626; }
    .conservation-en-peligro-crítico { background: #dc2626; }
    .conservation-en-peligro { background: #ea580c; }
    .conservation-vulnerable { background: #d97706; }
    .conservation-casi-amenazado { background: #0891b2; }
    .conservation-preocupación-menor { background: #059669; }
    .conservation-datos-insuficientes { background: #6b7280; }
    .conservation-no-evaluado { background: #6b7280; }
    
    .species-content {
        padding: 1.5rem;
    }
    
    .species-name {
        font-size: 1.1rem;
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 0.5rem;
    }
    
    .scientific-name {
        color: #667eea;
        font-size: 0.9rem;
        margin-bottom: 1rem;
    }
    
    .species-meta {
        margin-bottom: 1rem;
    }
    
    .kingdom {
        background: #f1f5f9;
        color: #475569;
        padding: 0.3rem 0.8rem;
        border-radius: 15px;
        font-size: 0.8rem;
        font-weight: 500;
    }
    
    .excerpt-section {
        margin-bottom: 1rem;
    }
    
    /* Sidebar Styles */
    .sidebar-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        margin-bottom: 2rem;
        overflow: hidden;
    }
    
    .sidebar-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 1.5rem;
    }
    
    .sidebar-header h4 {
        margin: 0;
        font-weight: 600;
        font-size: 1.1rem;
    }
    
    .sidebar-content {
        padding: 1.5rem;
    }
    
    .info-grid {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }
    
    .info-item {
        border-bottom: 1px solid #e2e8f0;
        padding-bottom: 1rem;
    }
    
    .info-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }
    
    .info-label {
        font-size: 0.85rem;
        color: #64748b;
        margin-bottom: 0.5rem;
        font-weight: 500;
    }
    
    .info-value {
        font-weight: 600;
        color: #2d3748;
    }
    
    .download-link {
        color: #667eea;
        text-decoration: none;
        font-weight: 500;
    }
    
    .download-link:hover {
        color: #5a67d8;
        text-decoration: underline;
    }
    
    /* Similar Publications */
    .similar-publications {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }
    
    .similar-publication-item {
        padding-bottom: 1.5rem;
        border-bottom: 1px solid #e2e8f0;
    }
    
    .similar-publication-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }
    
    .similar-title {
        margin-bottom: 0.8rem;
    }
    
    .similar-title a {
        color: #2d3748;
        text-decoration: none;
        font-weight: 600;
        line-height: 1.4;
    }
    
    .similar-title a:hover {
        color: #667eea;
    }
    
    .similar-meta {
        display: flex;
        flex-direction: column;
        gap: 0.3rem;
    }
    
    .similar-meta span {
        font-size: 0.8rem;
        color: #64748b;
        display: flex;
        align-items: center;
        gap: 0.3rem;
    }
    
    .no-species-section {
        margin-top: 2rem;
    }
    
    /* Responsive Design */
    @media (max-width: 768px) {
        .publication-header {
            padding: 1.5rem;
        }
        
        .publication-title {
            font-size: 1.5rem;
        }
        
        .publication-actions {
            flex-direction: column;
        }
        
        .species-grid {
            grid-template-columns: 1fr;
        }
        
        .publication-abstract,
        .related-species-section {
            padding: 1.5rem;
        }
    }
</style>
@stop