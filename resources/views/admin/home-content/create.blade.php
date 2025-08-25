@extends('adminlte::page')

@section('title', 'Crear Contenido del Home')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Crear Contenido del Home</h1>
        <a href="{{ route('admin.home-content.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Información del Contenido</h3>
                </div>
                <form action="{{ route('admin.home-content.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="section">Sección <span class="text-danger">*</span></label>
                            <select name="section" id="section" class="form-control @error('section') is-invalid @enderror" required>
                                <option value="">Seleccionar sección...</option>
                                @foreach($sections as $key => $title)
                                    <option value="{{ $key }}" {{ old('section', request('section')) == $key ? 'selected' : '' }}>
                                        {{ $title }}
                                    </option>
                                @endforeach
                            </select>
                            @error('section')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="key">Clave <span class="text-danger">*</span></label>
                            <input type="text" name="key" id="key" class="form-control @error('key') is-invalid @enderror" 
                                   value="{{ old('key') }}" placeholder="Ej: title, subtitle, description" required>
                            @error('key')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Identificador único para este contenido dentro de la sección.</small>
                        </div>

                        <div class="form-group">
                            <label for="type">Tipo de Contenido <span class="text-danger">*</span></label>
                            <select name="type" id="type" class="form-control @error('type') is-invalid @enderror" required>
                                <option value="">Seleccionar tipo...</option>
                                <option value="text" {{ old('type') == 'text' ? 'selected' : '' }}>Texto</option>
                                <option value="image" {{ old('type') == 'image' ? 'selected' : '' }}>Imagen</option>
                                <option value="url" {{ old('type') == 'url' ? 'selected' : '' }}>URL</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group" id="value-group">
                            <label for="value">Contenido <span class="text-danger">*</span></label>
                            <textarea name="value" id="value" class="form-control @error('value') is-invalid @enderror" 
                                      rows="3" placeholder="Ingrese el contenido..." required>{{ old('value') }}</textarea>
                            @error('value')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group" id="image-group" style="display: none;">
                            <label for="image">Imagen</label>
                            <input type="file" name="image" id="image" class="form-control-file @error('image') is-invalid @enderror" accept="image/*">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Formatos permitidos: JPG, PNG, GIF. Tamaño máximo: 2MB.</small>
                        </div>

                        <div class="form-group">
                            <label for="sort_order">Orden</label>
                            <input type="number" name="sort_order" id="sort_order" class="form-control @error('sort_order') is-invalid @enderror" 
                                   value="{{ old('sort_order', 0) }}" min="0">
                            @error('sort_order')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Orden de visualización (0 = primero).</small>
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_active">Contenido activo</label>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Guardar Contenido
                        </button>
                        <a href="{{ route('admin.home-content.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Ayuda</h3>
                </div>
                <div class="card-body">
                    <h6>Tipos de Contenido:</h6>
                    <ul class="list-unstyled">
                        <li><strong>Texto:</strong> Contenido textual como títulos, descripciones, etc.</li>
                        <li><strong>Imagen:</strong> Archivos de imagen para banners, iconos, etc.</li>
                        <li><strong>URL:</strong> Enlaces a páginas internas o externas.</li>
                    </ul>
                    
                    <h6>Claves Comunes:</h6>
                    <ul class="list-unstyled">
                        <li><code>title</code> - Título principal</li>
                        <li><code>subtitle</code> - Subtítulo</li>
                        <li><code>description</code> - Descripción</li>
                        <li><code>button_text</code> - Texto del botón</li>
                        <li><code>button_url</code> - URL del botón</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script>
        document.getElementById('type').addEventListener('change', function() {
            const type = this.value;
            const valueGroup = document.getElementById('value-group');
            const imageGroup = document.getElementById('image-group');
            const valueField = document.getElementById('value');
            
            if (type === 'image') {
                valueGroup.style.display = 'none';
                imageGroup.style.display = 'block';
                valueField.required = false;
            } else {
                valueGroup.style.display = 'block';
                imageGroup.style.display = 'none';
                valueField.required = true;
            }
        });
        
        // Trigger change event on page load
        document.getElementById('type').dispatchEvent(new Event('change'));
    </script>
@stop