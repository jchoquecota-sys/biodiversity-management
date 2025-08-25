@extends('adminlte::page')

@section('title', 'Detalles de la Imagen del Slider')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Detalles de la Imagen del Slider</h1>
        <div>
            <a href="{{ route('admin.hero-slider.edit', $heroSlider) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Editar
            </a>
            <a href="{{ route('admin.hero-slider.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Información de la Imagen</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Título:</strong>
                            <p class="text-muted">{{ $heroSlider->title ?? 'Sin título' }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Texto Alternativo:</strong>
                            <p class="text-muted">{{ $heroSlider->alt_text ?? 'Sin texto alternativo' }}</p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <strong>Descripción:</strong>
                            <p class="text-muted">{{ $heroSlider->description ?? 'Sin descripción' }}</p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <strong>Texto del Botón:</strong>
                            <p class="text-muted">{{ $heroSlider->button_text ?? 'Sin botón' }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>URL del Botón:</strong>
                            @if($heroSlider->button_url)
                                <p class="text-muted">
                                    <a href="{{ $heroSlider->button_url }}" target="_blank" rel="noopener">
                                        {{ $heroSlider->button_url }}
                                        <i class="fas fa-external-link-alt ml-1"></i>
                                    </a>
                                </p>
                            @else
                                <p class="text-muted">Sin URL</p>
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <strong>Orden de Visualización:</strong>
                            <p class="text-muted">
                                <span class="badge badge-secondary">{{ $heroSlider->sort_order }}</span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <strong>Estado:</strong>
                            <p class="text-muted">
                                @if($heroSlider->is_active)
                                    <span class="badge badge-success">Activo</span>
                                @else
                                    <span class="badge badge-danger">Inactivo</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <strong>Fecha de Creación:</strong>
                            <p class="text-muted">{{ $heroSlider->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Última Actualización:</strong>
                            <p class="text-muted">{{ $heroSlider->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            @if($heroSlider->has_overlay_image)
                <div class="card mt-3">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-layer-group"></i> Imagen Superpuesta
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <strong>Posición:</strong>
                                <p class="text-muted">
                                    <span class="badge badge-info">{{ ucfirst($heroSlider->overlay_position) }}</span>
                                </p>
                            </div>
                            <div class="col-md-4">
                                <strong>Tamaño:</strong>
                                <p class="text-muted">
                                    <span class="badge badge-secondary">{{ $heroSlider->overlay_width ?? 300 }}x{{ $heroSlider->overlay_height ?? 200 }}px</span>
                                </p>
                            </div>
                            <div class="col-md-4">
                                <strong>Texto Alternativo:</strong>
                                <p class="text-muted">{{ $heroSlider->overlay_alt_text ?? 'Sin texto alternativo' }}</p>
                            </div>
                        </div>

                        @if($heroSlider->overlay_description)
                            <div class="row">
                                <div class="col-12">
                                    <strong>Descripción:</strong>
                                    <p class="text-muted">{{ $heroSlider->overlay_description }}</p>
                                </div>
                            </div>
                        @endif

                        @if($heroSlider->overlay_button_text || $heroSlider->overlay_button_url)
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Texto del Botón:</strong>
                                    <p class="text-muted">{{ $heroSlider->overlay_button_text ?? 'Sin botón' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <strong>URL del Botón:</strong>
                                    @if($heroSlider->overlay_button_url)
                                        <p class="text-muted">
                                            <a href="{{ $heroSlider->overlay_button_url }}" target="_blank" rel="noopener">
                                                {{ $heroSlider->overlay_button_url }}
                                                <i class="fas fa-external-link-alt ml-1"></i>
                                            </a>
                                        </p>
                                    @else
                                        <p class="text-muted">Sin URL</p>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Imagen</h3>
                </div>
                <div class="card-body text-center">
                    @if($heroSlider->hasMedia('hero_images'))
                        <div class="mb-3">
                            <img src="{{ $heroSlider->getImageUrl() }}" 
                                 alt="{{ $heroSlider->alt_text }}" 
                                 class="img-fluid rounded shadow">
                        </div>
                        
                        <div class="btn-group" role="group">
                            <a href="{{ $heroSlider->getImageUrl() }}" 
                               target="_blank" 
                               class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i> Ver Original
                            </a>
                            <a href="{{ $heroSlider->getImageUrl('hero') }}" 
                               target="_blank" 
                               class="btn btn-sm btn-primary">
                                <i class="fas fa-expand"></i> Ver Hero
                            </a>
                        </div>
                        
                        <div class="mt-3">
                            <small class="text-muted">
                                @if($heroSlider->getMedia('hero_images')->first())
                                    @php
                                        $media = $heroSlider->getMedia('hero_images')->first();
                                        $sizeInMB = round($media->size / 1024 / 1024, 2);
                                    @endphp
                                    <strong>Archivo:</strong> {{ $media->file_name }}<br>
                                    <strong>Tamaño:</strong> {{ $sizeInMB }} MB<br>
                                    <strong>Tipo:</strong> {{ $media->mime_type }}
                                @endif
                            </small>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-image fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No hay imagen asociada</p>
                        </div>
                    @endif
                </div>
            </div>

            @if($heroSlider->has_overlay_image && $heroSlider->hasMedia('overlay_images'))
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-layer-group"></i> Imagen Superpuesta
                        </h3>
                    </div>
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <img src="{{ $heroSlider->getOverlayImageUrl() }}" 
                                 alt="{{ $heroSlider->overlay_alt_text }}" 
                                 class="img-fluid rounded shadow">
                        </div>
                        
                        <div class="btn-group" role="group">
                            <a href="{{ $heroSlider->getOverlayImageUrl() }}" 
                               target="_blank" 
                               class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i> Ver Original
                            </a>
                            <a href="{{ $heroSlider->getOverlayImageUrl('overlay') }}" 
                               target="_blank" 
                               class="btn btn-sm btn-primary">
                                <i class="fas fa-expand"></i> Ver Overlay
                            </a>
                        </div>
                        
                        <div class="mt-3">
                            <small class="text-muted">
                                @if($heroSlider->getMedia('overlay_images')->first())
                                    @php
                                        $overlayMedia = $heroSlider->getMedia('overlay_images')->first();
                                        $overlaySizeInMB = round($overlayMedia->size / 1024 / 1024, 2);
                                    @endphp
                                    <strong>Archivo:</strong> {{ $overlayMedia->file_name }}<br>
                                    <strong>Tamaño:</strong> {{ $overlaySizeInMB }} MB<br>
                                    <strong>Tipo:</strong> {{ $overlayMedia->mime_type }}
                                @endif
                            </small>
                        </div>
                    </div>
                </div>
            @endif

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Acciones</h3>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.hero-slider.edit', $heroSlider) }}" 
                           class="btn btn-warning btn-block">
                            <i class="fas fa-edit"></i> Editar Imagen
                        </a>
                        
                        @if($heroSlider->button_url)
                            <a href="{{ $heroSlider->button_url }}" 
                               target="_blank" 
                               rel="noopener"
                               class="btn btn-info btn-block">
                                <i class="fas fa-external-link-alt"></i> Probar Enlace
                            </a>
                        @endif
                        
                        <form action="{{ route('admin.hero-slider.destroy', $heroSlider) }}" 
                              method="POST" 
                              onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta imagen del slider?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-block">
                                <i class="fas fa-trash"></i> Eliminar Imagen
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .img-fluid {
            max-height: 400px;
            object-fit: cover;
        }
        
        .d-grid {
            display: grid;
        }
        
        .gap-2 {
            gap: 0.5rem;
        }
        
        .btn-block {
            width: 100%;
            margin-bottom: 0.5rem;
        }
    </style>
@stop