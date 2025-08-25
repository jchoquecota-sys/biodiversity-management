@extends('adminlte::page')

@section('title', 'Configuración del Slider del Hero')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Configuración del Slider del Hero</h1>
        <a href="{{ route('admin.home-content.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
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

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Configuraciones del Slider</h3>
                </div>
                <form action="{{ route('admin.hero-slider-config.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="form-group">
                            <label for="use_image_slider">Usar Slider de Imágenes</label>
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="use_image_slider" name="use_image_slider" value="true" {{ $useImageSlider === 'true' ? 'checked' : '' }}>
                                <label class="custom-control-label" for="use_image_slider">Activar slider de imágenes en lugar del hero estático</label>
                            </div>
                            <small class="form-text text-muted">Cuando está activado, se mostrará el slider de imágenes. Cuando está desactivado, se mostrará el contenido hero estático.</small>
                        </div>

                        <div class="form-group">
                            <label for="slider_autoplay">Reproducción Automática</label>
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="slider_autoplay" name="slider_autoplay" value="true" {{ $sliderAutoplay === 'true' ? 'checked' : '' }}>
                                <label class="custom-control-label" for="slider_autoplay">Activar reproducción automática del slider</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="slider_interval">Intervalo del Slider (milisegundos)</label>
                            <input type="number" class="form-control" id="slider_interval" name="slider_interval" value="{{ $sliderInterval }}" min="1000" max="10000" step="500">
                            <small class="form-text text-muted">Tiempo en milisegundos entre cada cambio de imagen (1000 = 1 segundo)</small>
                        </div>

                        <div class="form-group">
                            <label for="enable_icons">Mostrar Iconos en Hero Estático</label>
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="enable_icons" name="enable_icons" value="true" {{ $enableIcons === 'true' ? 'checked' : '' }}>
                                <label class="custom-control-label" for="enable_icons">Mostrar iconos decorativos en el hero estático</label>
                            </div>
                            <small class="form-text text-muted">Solo aplica cuando el slider de imágenes está desactivado.</small>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Guardar Configuración
                        </button>
                        <a href="{{ route('admin.hero-slider.index') }}" class="btn btn-info ml-2">
                            <i class="fas fa-images"></i> Gestionar Imágenes del Slider
                        </a>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Vista Previa</h3>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h6><i class="fas fa-info-circle"></i> Configuración Actual</h6>
                        <ul class="mb-0">
                            <li><strong>Slider:</strong> {{ $useImageSlider === 'true' ? 'Activado' : 'Desactivado' }}</li>
                            <li><strong>Autoplay:</strong> {{ $sliderAutoplay === 'true' ? 'Activado' : 'Desactivado' }}</li>
                            <li><strong>Intervalo:</strong> {{ $sliderInterval }}ms</li>
                            <li><strong>Iconos:</strong> {{ $enableIcons === 'true' ? 'Activados' : 'Desactivados' }}</li>
                        </ul>
                    </div>
                    
                    <div class="alert alert-warning">
                        <h6><i class="fas fa-exclamation-triangle"></i> Importante</h6>
                        <p class="mb-0">Para que el slider funcione correctamente, asegúrate de tener al menos una imagen activa en la gestión de imágenes del slider.</p>
                    </div>
                    
                    <a href="{{ url('/') }}" target="_blank" class="btn btn-outline-primary btn-block">
                        <i class="fas fa-external-link-alt"></i> Ver Sitio Web
                    </a>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .custom-control-label {
            font-weight: normal;
        }
        .alert h6 {
            margin-bottom: 10px;
        }
    </style>
@stop

@section('js')
    <script>
        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            $('.alert-success').fadeOut('slow');
        }, 5000);
    </script>
@stop