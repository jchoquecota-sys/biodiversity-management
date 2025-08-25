@extends('adminlte::page')

@section('title', 'Editar Imagen del Slider')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Editar Imagen del Slider</h1>
        <a href="{{ route('admin.hero-slider.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Editar Imagen del Slider</h3>
        </div>
        <form action="{{ route('admin.hero-slider.update', $heroSlider) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="title">Título</label>
                            <input type="text" 
                                   class="form-control @error('title') is-invalid @enderror" 
                                   id="title" 
                                   name="title" 
                                   value="{{ old('title', $heroSlider->title) }}"
                                   placeholder="Título de la imagen">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="alt_text">Texto Alternativo</label>
                            <input type="text" 
                                   class="form-control @error('alt_text') is-invalid @enderror" 
                                   id="alt_text" 
                                   name="alt_text" 
                                   value="{{ old('alt_text', $heroSlider->alt_text) }}"
                                   placeholder="Descripción para accesibilidad">
                            @error('alt_text')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="description">Descripción</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" 
                              id="description" 
                              name="description" 
                              rows="3"
                              placeholder="Descripción de la imagen">{{ old('description', $heroSlider->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="button_text">Texto del Botón</label>
                            <input type="text" 
                                   class="form-control @error('button_text') is-invalid @enderror" 
                                   id="button_text" 
                                   name="button_text" 
                                   value="{{ old('button_text', $heroSlider->button_text) }}"
                                   placeholder="Ej: Ver más, Explorar">
                            @error('button_text')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="button_url">URL del Botón</label>
                            <input type="url" 
                                   class="form-control @error('button_url') is-invalid @enderror" 
                                   id="button_url" 
                                   name="button_url" 
                                   value="{{ old('button_url', $heroSlider->button_url) }}"
                                   placeholder="https://ejemplo.com">
                            @error('button_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="sort_order">Orden de Visualización <span class="text-danger">*</span></label>
                            <input type="number" 
                                   class="form-control @error('sort_order') is-invalid @enderror" 
                                   id="sort_order" 
                                   name="sort_order" 
                                   value="{{ old('sort_order', $heroSlider->sort_order) }}"
                                   min="0"
                                   required>
                            @error('sort_order')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Número menor aparece primero</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="is_active">Estado</label>
                            <div class="custom-control custom-switch">
                                <input type="checkbox" 
                                       class="custom-control-input" 
                                       id="is_active" 
                                       name="is_active" 
                                       value="1"
                                       {{ old('is_active', $heroSlider->is_active) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_active">Activo</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="image">Imagen</label>
                    
                    @if($heroSlider->hasMedia('hero_images'))
                        <div class="mb-3">
                            <label class="form-label">Imagen Actual:</label>
                            <div>
                                <img src="{{ $heroSlider->getImageUrl('thumb') }}" 
                                     alt="{{ $heroSlider->alt_text }}" 
                                     class="img-thumbnail" 
                                     style="max-width: 300px;">
                            </div>
                        </div>
                    @endif
                    
                    <div class="custom-file">
                        <input type="file" 
                               class="custom-file-input @error('image') is-invalid @enderror" 
                               id="image" 
                               name="image" 
                               accept="image/*">
                        <label class="custom-file-label" for="image">Cambiar imagen...</label>
                    </div>
                    @error('image')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">
                        Formatos permitidos: JPEG, PNG, GIF, WebP. Tamaño máximo: 5MB.<br>
                        Resolución recomendada: 1920x800 píxeles.<br>
                        <strong>Dejar vacío para mantener la imagen actual.</strong>
                    </small>
                </div>

                <div id="image-preview" class="mt-3" style="display: none;">
                    <label class="form-label">Nueva imagen:</label>
                    <div>
                        <img id="preview-img" src="" alt="Vista previa" class="img-thumbnail" style="max-width: 300px;">
                    </div>
                </div>

                <!-- Sección de Imagen Superpuesta -->
                <hr class="my-4">
                <h5><i class="fas fa-layer-group"></i> Imagen Superpuesta (Opcional)</h5>
                
                <div class="form-group">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" 
                               class="custom-control-input" 
                               id="has_overlay_image" 
                               name="has_overlay_image" 
                               value="1"
                               {{ old('has_overlay_image', $heroSlider->has_overlay_image) ? 'checked' : '' }}>
                        <label class="custom-control-label" for="has_overlay_image">Agregar imagen superpuesta</label>
                    </div>
                    <small class="form-text text-muted">Activa esta opción para agregar una imagen superpuesta sobre el slider</small>
                </div>

                <div id="overlay-fields" style="display: {{ old('has_overlay_image', $heroSlider->has_overlay_image) ? 'block' : 'none' }};">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="overlay_position">Posición de la Imagen</label>
                                <select class="form-control @error('overlay_position') is-invalid @enderror" 
                                        id="overlay_position" 
                                        name="overlay_position">
                                    <option value="left" {{ old('overlay_position', $heroSlider->overlay_position) == 'left' ? 'selected' : '' }}>Izquierda</option>
                                    <option value="right" {{ old('overlay_position', $heroSlider->overlay_position) == 'right' ? 'selected' : '' }}>Derecha</option>
                                    <option value="center" {{ old('overlay_position', $heroSlider->overlay_position) == 'center' ? 'selected' : '' }}>Centro</option>
                                </select>
                                @error('overlay_position')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="overlay_alt_text">Texto Alternativo</label>
                                <input type="text" 
                                       class="form-control @error('overlay_alt_text') is-invalid @enderror" 
                                       id="overlay_alt_text" 
                                       name="overlay_alt_text" 
                                       value="{{ old('overlay_alt_text', $heroSlider->overlay_alt_text) }}"
                                       placeholder="Descripción de la imagen superpuesta">
                                @error('overlay_alt_text')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="overlay_description">Descripción</label>
                        <textarea class="form-control @error('overlay_description') is-invalid @enderror" 
                                  id="overlay_description" 
                                  name="overlay_description" 
                                  rows="3"
                                  placeholder="Descripción de la imagen superpuesta">{{ old('overlay_description', $heroSlider->overlay_description) }}</textarea>
                        @error('overlay_description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="overlay_button_text">Texto del Botón</label>
                                <input type="text" 
                                       class="form-control @error('overlay_button_text') is-invalid @enderror" 
                                       id="overlay_button_text" 
                                       name="overlay_button_text" 
                                       value="{{ old('overlay_button_text', $heroSlider->overlay_button_text) }}"
                                       placeholder="Ej: Ver más, Explorar">
                                @error('overlay_button_text')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="overlay_button_url">URL del Botón</label>
                                <input type="url" 
                                       class="form-control @error('overlay_button_url') is-invalid @enderror" 
                                       id="overlay_button_url" 
                                       name="overlay_button_url" 
                                       value="{{ old('overlay_button_url', $heroSlider->overlay_button_url) }}"
                                       placeholder="https://ejemplo.com">
                                @error('overlay_button_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="overlay_width">Ancho (px)</label>
                                <input type="number" 
                                       class="form-control @error('overlay_width') is-invalid @enderror" 
                                       id="overlay_width" 
                                       name="overlay_width" 
                                       value="{{ old('overlay_width', $heroSlider->overlay_width ?? 300) }}"
                                       min="50"
                                       max="800"
                                       placeholder="300">
                                @error('overlay_width')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Ancho de la imagen superpuesta (50-800px)</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="overlay_height">Alto (px)</label>
                                <input type="number" 
                                       class="form-control @error('overlay_height') is-invalid @enderror" 
                                       id="overlay_height" 
                                       name="overlay_height" 
                                       value="{{ old('overlay_height', $heroSlider->overlay_height ?? 200) }}"
                                       min="50"
                                       max="600"
                                       placeholder="200">
                                @error('overlay_height')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Alto de la imagen superpuesta (50-600px)</small>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="overlay_image">Imagen Superpuesta</label>
                        
                        @if($heroSlider->hasMedia('overlay_images'))
                            <div class="mb-3">
                                <label class="form-label">Imagen Superpuesta Actual:</label>
                                <div>
                                    <img src="{{ $heroSlider->getOverlayImageUrl('overlay_thumb') }}" 
                                         alt="{{ $heroSlider->overlay_alt_text }}" 
                                         class="img-thumbnail" 
                                         style="max-width: 200px;">
                                </div>
                            </div>
                        @endif
                        
                        <div class="custom-file">
                            <input type="file" 
                                   class="custom-file-input @error('overlay_image') is-invalid @enderror" 
                                   id="overlay_image" 
                                   name="overlay_image" 
                                   accept="image/*">
                            <label class="custom-file-label" for="overlay_image">{{ $heroSlider->hasMedia('overlay_images') ? 'Cambiar imagen superpuesta...' : 'Seleccionar imagen superpuesta...' }}</label>
                        </div>
                        @error('overlay_image')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">
                            Formatos permitidos: JPEG, PNG, GIF, WebP. Tamaño máximo: 5MB.<br>
                            Resolución recomendada: 600x400 píxeles.<br>
                            <strong>Dejar vacío para mantener la imagen actual.</strong>
                        </small>
                    </div>

                    <div id="overlay-preview" class="mt-3" style="display: none;">
                        <label class="form-label">Nueva imagen superpuesta:</label>
                        <div>
                            <img id="overlay-preview-img" src="" alt="Vista previa superpuesta" class="img-thumbnail" style="max-width: 200px;">
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Actualizar Imagen
                </button>
                <a href="{{ route('admin.hero-slider.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
@stop

@section('js')
    <script>
        $(document).ready(function() {
            // Previsualización de imagen principal
            $('#image').change(function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $('#preview-img').attr('src', e.target.result);
                        $('#image-preview').show();
                    };
                    reader.readAsDataURL(file);
                    
                    // Actualizar el label del input file
                    $(this).next('.custom-file-label').text(file.name);
                } else {
                    $('#image-preview').hide();
                }
            });

            // Previsualización de imagen superpuesta
            $('#overlay_image').change(function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $('#overlay-preview-img').attr('src', e.target.result);
                        $('#overlay-preview').show();
                    };
                    reader.readAsDataURL(file);
                    
                    // Actualizar el label del input file
                    $(this).next('.custom-file-label').text(file.name);
                } else {
                    $('#overlay-preview').hide();
                }
            });

            // Mostrar/ocultar campos de imagen superpuesta
            $('#has_overlay_image').change(function() {
                if ($(this).is(':checked')) {
                    $('#overlay-fields').slideDown();
                } else {
                    $('#overlay-fields').slideUp();
                }
            });

            // Habilitar/deshabilitar URL del botón principal basado en el texto del botón
            $('#button_text').on('input', function() {
                const buttonUrl = $('#button_url');
                if ($(this).val().trim() === '') {
                    buttonUrl.prop('disabled', true).val('');
                } else {
                    buttonUrl.prop('disabled', false);
                }
            });

            // Habilitar/deshabilitar URL del botón superpuesto basado en el texto del botón
            $('#overlay_button_text').on('input', function() {
                const buttonUrl = $('#overlay_button_url');
                if ($(this).val().trim() === '') {
                    buttonUrl.prop('disabled', true).val('');
                } else {
                    buttonUrl.prop('disabled', false);
                }
            });

            // Verificar estado inicial
            if ($('#button_text').val().trim() === '') {
                $('#button_url').prop('disabled', true);
            }
            if ($('#overlay_button_text').val().trim() === '') {
                $('#overlay_button_url').prop('disabled', true);
            }
        });
    </script>
@stop