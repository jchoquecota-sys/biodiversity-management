@extends('adminlte::page')

@section('title', 'Gestión del Slider del Hero')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Gestión del Slider del Hero</h1>
        <div>
            <a href="{{ route('admin.hero-slider-config') }}" class="btn btn-info mr-2">
                <i class="fas fa-cog"></i> Configuración
            </a>
            <a href="{{ route('admin.hero-slider.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Agregar Imagen
            </a>
        </div>
    </div>
@stop

@section('content')
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Imágenes del Slider</h3>
        </div>
        <div class="card-body">
            @if($sliderImages->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Imagen</th>
                                <th>Título</th>
                                <th>Descripción</th>
                                <th>Botón</th>
                                <th>Superpuesta</th>
                                <th>Orden</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sliderImages as $image)
                                <tr>
                                    <td>
                                        <img src="{{ $image->getImageUrl('thumb') }}" 
                                             alt="{{ $image->alt_text }}" 
                                             class="img-thumbnail" 
                                             style="max-width: 100px; max-height: 60px;">
                                    </td>
                                    <td>{{ $image->title ?? 'Sin título' }}</td>
                                    <td>{{ Str::limit($image->description, 50) ?? 'Sin descripción' }}</td>
                                    <td>
                                        @if($image->button_text && $image->button_url)
                                            <span class="badge badge-info">{{ $image->button_text }}</span>
                                        @else
                                            <span class="text-muted">Sin botón</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($image->has_overlay_image && $image->hasMedia('overlay_images'))
                                            <span class="badge badge-success">
                                                <i class="fas fa-layer-group"></i> {{ ucfirst($image->overlay_position) }}
                                            </span>
                                            <br>
                                            <small class="text-muted">{{ $image->overlay_width ?? 300 }}x{{ $image->overlay_height ?? 200 }}px</small>
                                        @elseif($image->has_overlay_image)
                                            <span class="badge badge-warning">
                                                <i class="fas fa-exclamation-triangle"></i> Sin imagen
                                            </span>
                                        @else
                                            <span class="text-muted">No</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-secondary">{{ $image->sort_order }}</span>
                                    </td>
                                    <td>
                                        @if($image->is_active)
                                            <span class="badge badge-success">Activo</span>
                                        @else
                                            <span class="badge badge-danger">Inactivo</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.hero-slider.show', $image) }}" 
                                               class="btn btn-sm btn-info" title="Ver">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.hero-slider.edit', $image) }}" 
                                               class="btn btn-sm btn-warning" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.hero-slider.destroy', $image) }}" 
                                                  method="POST" 
                                                  style="display: inline-block;"
                                                  onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta imagen?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Eliminar">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-images fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted">No hay imágenes en el slider</h4>
                    <p class="text-muted">Agrega la primera imagen para comenzar.</p>
                    <a href="{{ route('admin.hero-slider.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Agregar Primera Imagen
                    </a>
                </div>
            @endif
        </div>
    </div>
@stop

@section('css')
    <style>
        .img-thumbnail {
            object-fit: cover;
        }
    </style>
@stop

@section('js')
    <script>
        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 5000);
    </script>
@stop