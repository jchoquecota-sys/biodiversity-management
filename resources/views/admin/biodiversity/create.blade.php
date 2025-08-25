@extends('adminlte::page')

@section('title', 'Crear Categoría de Biodiversidad')

@section('content_header')
    <h1>Crear Categoría de Biodiversidad</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.biodiversity.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Nombre <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                            @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="scientific_name">Nombre Científico <span class="text-danger">*</span></label>
                            <input type="text" name="scientific_name" id="scientific_name" class="form-control @error('scientific_name') is-invalid @enderror" value="{{ old('scientific_name') }}" required>
                            @error('scientific_name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="idreino">
                                <i class="fas fa-crown text-primary"></i> Reino Taxonómico
                            </label>
                            <select name="idreino" id="idreino" class="form-control select2 @error('idreino') is-invalid @enderror">
                                <option value="">🔍 Seleccione un reino</option>
                                @foreach($reinos as $reino)
                                    <option value="{{ $reino->id }}" {{ old('idreino') == $reino->id ? 'selected' : '' }}>
                                        🏛️ {{ $reino->nombre }}
                                        @if($reino->definicion)
                                            - {{ Str::limit($reino->definicion, 50) }}
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('idreino')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle"></i> El reino es la categoría taxonómica más amplia
                            </small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="clase_id">
                                <i class="fas fa-layer-group text-success"></i> Clase Taxonómica
                            </label>
                            <select id="clase_id" class="form-control select2" disabled>
                                <option value="">🔍 Seleccione una clase</option>
                            </select>
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle"></i> Primero seleccione un reino
                            </small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="orden_id">
                                <i class="fas fa-sitemap text-warning"></i> Orden Taxonómico
                            </label>
                            <select id="orden_id" class="form-control select2" disabled>
                                <option value="">🔍 Seleccione un orden</option>
                            </select>
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle"></i> Primero seleccione una clase
                            </small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="idfamilia">
                                <i class="fas fa-users text-info"></i> Familia Taxonómica
                            </label>
                            <select name="idfamilia" id="idfamilia" class="form-control select2 @error('idfamilia') is-invalid @enderror" disabled>
                                <option value="">🔍 Seleccione una familia</option>
                            </select>
                            @error('idfamilia')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle"></i> Primero seleccione un orden
                            </small>
                        </div>
                    </div>
                </div>
<div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="conservation_status_id">
                                <i class="fas fa-shield-alt text-danger"></i> Estado de Conservación <span class="text-danger">*</span>
                            </label>
                            <select name="conservation_status_id" id="conservation_status_id" class="form-control @error('conservation_status_id') is-invalid @enderror" required>
                                <option value="">🔍 Seleccione un estado de conservación</option>
                                @foreach($conservationStatuses as $status)
                                    <option value="{{ $status->id }}" {{ old('conservation_status_id') == $status->id ? 'selected' : '' }}>
                                        <span style="color: {{ $status->color }};">●</span> {{ $status->code }} - {{ $status->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('conservation_status_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle"></i> Indica el nivel de riesgo de extinción de la especie
                            </small>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="habitat">Hábitat</label>
                            <input type="text" name="habitat" id="habitat" class="form-control @error('habitat') is-invalid @enderror" value="{{ old('habitat') }}">
                            @error('habitat')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <!-- Sección de Imágenes -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="mb-3">
                                <i class="fas fa-images text-primary"></i> Gestión de Imágenes
                            </label>
                            
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <!-- Imagen Principal -->
                                        <div class="col-md-6 mb-4">
                                            <div class="border rounded p-3">
                                                <h6 class="text-primary mb-3">
                                                    <i class="fas fa-star"></i> Imagen Principal
                                                </h6>
                                                <div class="input-group mb-2">
                                                    <div class="custom-file">
                                                        <input type="file" name="image" id="image" class="custom-file-input @error('image') is-invalid @enderror" accept="image/*">
                                                        <label class="custom-file-label" for="image">Seleccionar archivo</label>
                                                    </div>
                                                </div>
                                                @error('image')
                                                    <span class="text-danger small">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        
                                        <!-- Imágenes Adicionales -->
                                        <div class="col-md-6">
                                            <h6 class="text-info mb-3">
                                                <i class="fas fa-images"></i> Imágenes Adicionales
                                            </h6>
                                            <div class="row">
                                                @for($i = 2; $i <= 4; $i++)
                                                    <div class="col-md-12 mb-3">
                                                        <div class="border rounded p-2">
                                                            <label for="image_{{ $i }}" class="small text-muted mb-1">Imagen {{ $i }}</label>
                                                            <div class="input-group input-group-sm">
                                                                <div class="custom-file">
                                                                    <input type="file" name="image_{{ $i }}" id="image_{{ $i }}" class="custom-file-input" accept="image/*">
                                                                    <label class="custom-file-label" for="image_{{ $i }}">Seleccionar</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endfor
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="alert alert-info mt-3 mb-0">
                                        <i class="fas fa-info-circle"></i> 
                                        <strong>Información:</strong> Puede subir hasta 4 imágenes por especie. La imagen principal se mostrará como imagen destacada en las listas.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="description">Descripción</label>
                    <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="5">{{ old('description') }}</textarea>
                    @error('description')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Publicaciones Relacionadas</label>
                    <select name="publications[]" id="publications" class="form-control select2" multiple>
                        @foreach($publications as $publication)
                            <option value="{{ $publication->id }}" {{ in_array($publication->id, old('publications', [])) ? 'selected' : '' }}>
                                {{ $publication->title }} ({{ $publication->publication_year }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div id="publication-details" class="d-none">
                    <h4>Detalles de Publicaciones</h4>
                    <div id="publication-fields"></div>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                    <a href="{{ route('admin.biodiversity.index') }}" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
@stop

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bs-custom-file-input/dist/bs-custom-file-input.min.js"></script>
    <script>
        $(document).ready(function() {
            // Inicializar Select2
            $('.select2').select2({
                placeholder: 'Seleccione publicaciones relacionadas',
                allowClear: true
            });

            // Inicializar Summernote
            $('#description').summernote({
                height: 200,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ]
            });

            // Inicializar el input de archivo personalizado
            bsCustomFileInput.init();

            // Manejar la selección de publicaciones
            $('#publications').on('change', function() {
                const selectedPublications = $(this).val();
                if (selectedPublications && selectedPublications.length > 0) {
                    $('#publication-details').removeClass('d-none');
                    generatePublicationFields(selectedPublications);
                } else {
                    $('#publication-details').addClass('d-none');
                    $('#publication-fields').html('');
                }
            });

            // Generar campos para detalles de publicaciones
            function generatePublicationFields(publicationIds) {
                let html = '';
                publicationIds.forEach((id, index) => {
                    const publicationTitle = $('#publications option[value="' + id + '"]').text();
                    html += `
                        <div class="card mb-3">
                            <div class="card-header">${publicationTitle}</div>
                            <div class="card-body">
                                <input type="hidden" name="publications[]" value="${id}">
                                <div class="form-group">
                                    <label>Extracto Relevante</label>
                                    <textarea name="relevant_excerpts[]" class="form-control" rows="3"></textarea>
                                </div>
                                <div class="form-group">
                                    <label>Referencia de Página</label>
                                    <input type="text" name="page_references[]" class="form-control" placeholder="ej. p. 45-47">
                                </div>
                            </div>
                        </div>
                    `;
                });
                $('#publication-fields').html(html);
            }

            // Trigger change if there are pre-selected publications
            if ($('#publications').val() && $('#publications').val().length > 0) {
                $('#publications').trigger('change');
            }

            // Manejar cambio de reino
            $('#idreino').on('change', function() {
                const reinoId = $(this).val();
                const claseSelect = $('#clase_id');
                const ordenSelect = $('#orden_id');
                const familiaSelect = $('#idfamilia');
                
                // Limpiar y deshabilitar selectores dependientes
                claseSelect.html('<option value="">🔍 Seleccione una clase</option>').prop('disabled', true);
                ordenSelect.html('<option value="">🔍 Seleccione un orden</option>').prop('disabled', true);
                familiaSelect.html('<option value="">🔍 Seleccione una familia</option>').prop('disabled', true);
                
                if (reinoId) {
                    // Cargar clases por reino
                    $.get(`{{ url('admin/api/clases/reino') }}/${reinoId}`, function(clases) {
                        if (clases.length > 0) {
                            clases.forEach(clase => {
                                const descripcion = clase.definicion ? ` - ${clase.definicion.substring(0, 50)}${clase.definicion.length > 50 ? '...' : ''}` : '';
                                claseSelect.append(`<option value="${clase.idclase}">🎯 ${clase.nombre}${descripcion}</option>`);
                            });
                            claseSelect.prop('disabled', false);
                        }
                    });
                }
            });

            // Manejar cambio de clase
            $('#clase_id').on('change', function() {
                const claseId = $(this).val();
                const ordenSelect = $('#orden_id');
                const familiaSelect = $('#idfamilia');
                
                // Limpiar y deshabilitar selectores dependientes
                ordenSelect.html('<option value="">🔍 Seleccione un orden</option>').prop('disabled', true);
                familiaSelect.html('<option value="">🔍 Seleccione una familia</option>').prop('disabled', true);
                
                if (claseId) {
                    // Cargar órdenes por clase
                    $.get(`{{ url('admin/api/ordenes/clase') }}/${claseId}`, function(ordenes) {
                        if (ordenes.length > 0) {
                            ordenes.forEach(orden => {
                                const descripcion = orden.definicion ? ` - ${orden.definicion.substring(0, 50)}${orden.definicion.length > 50 ? '...' : ''}` : '';
                                ordenSelect.append(`<option value="${orden.idorden}">📋 ${orden.nombre}${descripcion}</option>`);
                            });
                            ordenSelect.prop('disabled', false);
                        }
                    });
                }
            });

            // Manejar cambio de orden
            $('#orden_id').on('change', function() {
                const ordenId = $(this).val();
                const familiaSelect = $('#idfamilia');
                
                // Limpiar y deshabilitar selector de familia
                familiaSelect.html('<option value="">🔍 Seleccione una familia</option>').prop('disabled', true);
                
                if (ordenId) {
                    // Cargar familias por orden
                    $.get(`{{ url('admin/api/familias/orden') }}/${ordenId}`, function(familias) {
                        if (familias.length > 0) {
                            familias.forEach(familia => {
                                const descripcion = familia.definicion ? ` - ${familia.definicion.substring(0, 50)}${familia.definicion.length > 50 ? '...' : ''}` : '';
                                familiaSelect.append(`<option value="${familia.idfamilia}">👥 ${familia.nombre}${descripcion}</option>`);
                            });
                            familiaSelect.prop('disabled', false);
                        }
                    });
                }
            });
        });
    </script>
@stop