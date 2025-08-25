@extends('adminlte::page')

@section('title', 'Configuración General')

@section('content_header')
    <h1>Configuración General del Sistema</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.configuration.general.update') }}" method="POST">
                @csrf
                
                <div class="form-group">
                    <label for="site_name">Nombre del Sitio</label>
                    <input type="text" class="form-control @error('site_name') is-invalid @enderror" 
                           id="site_name" name="site_name" value="{{ old('site_name', $settings['site_name']) }}">
                    @error('site_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="site_description">Descripción del Sitio</label>
                    <textarea class="form-control @error('site_description') is-invalid @enderror" 
                              id="site_description" name="site_description" rows="3">{{ old('site_description', $settings['site_description']) }}</textarea>
                    @error('site_description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="contact_email">Correo de Contacto</label>
                    <input type="email" class="form-control @error('contact_email') is-invalid @enderror" 
                           id="contact_email" name="contact_email" value="{{ old('contact_email', $settings['contact_email']) }}">
                    @error('contact_email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="items_per_page">Elementos por Página</label>
                    <input type="number" class="form-control @error('items_per_page') is-invalid @enderror" 
                           id="items_per_page" name="items_per_page" value="{{ old('items_per_page', $settings['items_per_page']) }}">
                    @error('items_per_page')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">Número de elementos a mostrar por página en las listas</small>
                </div>

                <div class="form-group">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="maintenance_mode" 
                               name="maintenance_mode" value="1" {{ old('maintenance_mode', $settings['maintenance_mode']) ? 'checked' : '' }}>
                        <label class="custom-control-label" for="maintenance_mode">Modo Mantenimiento</label>
                    </div>
                    <small class="form-text text-muted">Activar el modo mantenimiento hará que el sitio sea inaccesible para los usuarios normales</small>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script>
        // Confirmación para activar modo mantenimiento
        document.getElementById('maintenance_mode').addEventListener('change', function(e) {
            if (this.checked) {
                if (!confirm('¿Está seguro de activar el modo mantenimiento? El sitio será inaccesible para los usuarios.')) {
                    e.preventDefault();
                    this.checked = false;
                }
            }
        });
    </script>
@stop