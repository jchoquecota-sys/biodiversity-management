@extends('adminlte::page')

@section('title', 'Editar Categoría de Biodiversidad')

@section('content_header')
    <h1>Editar Categoría de Biodiversidad</h1>
<!-- Modal para visualizar imagen en tamaño completo -->
<div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">Imagen de Biodiversidad</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" alt="" class="img-fluid" style="max-height: 500px;">
            </div>
        </div>
    </div>
</div>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.biodiversity.update', $biodiversity->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Nombre <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $biodiversity->name) }}" required>
                            @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="scientific_name">Nombre Científico <span class="text-danger">*</span></label>
                            <input type="text" name="scientific_name" id="scientific_name" class="form-control @error('scientific_name') is-invalid @enderror" value="{{ old('scientific_name', $biodiversity->scientific_name) }}" required>
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
                                    <option value="{{ $reino->id }}" {{ old('idreino', $biodiversity->idreino) == $reino->id ? 'selected' : '' }}>
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
                            <select id="clase_id" class="form-control select2">
                                <option value="">🔍 Seleccione una clase</option>
                                @foreach($clases as $clase)
                                    <option value="{{ $clase->idclase }}" {{ $biodiversity->familia && $biodiversity->familia->orden->clase->idclase == $clase->idclase ? 'selected' : '' }}>
                                        🎯 {{ $clase->nombre }}
                                        @if($clase->definicion)
                                            - {{ Str::limit($clase->definicion, 50) }}
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle"></i> Subdivisión del reino taxonómico
                            </small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="orden_id">
                                <i class="fas fa-sitemap text-warning"></i> Orden Taxonómico
                            </label>
                            <select id="orden_id" class="form-control select2">
                                <option value="">🔍 Seleccione un orden</option>
                                @if($biodiversity->familia)
                                    @foreach($ordenes->where('idclase', $biodiversity->familia->orden->clase->idclase) as $orden)
                                        <option value="{{ $orden->idorden }}" {{ $biodiversity->familia->orden->idorden == $orden->idorden ? 'selected' : '' }}>
                                            📋 {{ $orden->nombre }}
                                            @if($orden->definicion)
                                                - {{ Str::limit($orden->definicion, 50) }}
                                            @endif
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle"></i> Subdivisión de la clase taxonómica
                            </small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="idfamilia">
                                <i class="fas fa-users text-info"></i> Familia Taxonómica
                            </label>
                            <select name="idfamilia" id="idfamilia" class="form-control select2 @error('idfamilia') is-invalid @enderror">
                                <option value="">🔍 Seleccione una familia</option>
                                @if($biodiversity->familia)
                                    @foreach($familias->where('idorden', $biodiversity->familia->orden->idorden) as $familia)
                                        <option value="{{ $familia->idfamilia }}" {{ $biodiversity->idfamilia == $familia->idfamilia ? 'selected' : '' }}>
                                            👥 {{ $familia->nombre }}
                                            @if($familia->definicion)
                                                - {{ Str::limit($familia->definicion, 50) }}
                                            @endif
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            @error('idfamilia')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle"></i> Subdivisión del orden taxonómico
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
                                    <option value="{{ $status->id }}" {{ old('conservation_status_id', $biodiversity->conservation_status_id) == $status->id ? 'selected' : '' }}>
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
                            <input type="text" name="habitat" id="habitat" class="form-control @error('habitat') is-invalid @enderror" value="{{ old('habitat', $biodiversity->habitat) }}">
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
                                @if($biodiversity->getImageCount() > 1)
                                    <span class="badge badge-info ml-2">{{ $biodiversity->getImageCount() }} imágenes</span>
                                @endif
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
                                                @if($biodiversity->image_path)
                                                    <div class="text-center mt-2">
                                                        <img src="{{ $biodiversity->getImageUrl() }}" alt="{{ $biodiversity->name }}" class="img-thumbnail cursor-pointer" style="max-height: 120px; max-width: 100%;" onclick="showImageModal('{{ $biodiversity->getImageUrl() }}', '{{ addslashes($biodiversity->name) }} - Imagen Principal')">
                                                        <small class="text-muted d-block mt-1">Imagen Principal Actual</small>
                                                    </div>
                                                @endif
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
                                                            @php
                                                                $imageField = 'image_path_' . $i;
                                                                $imageUrl = $biodiversity->$imageField ? (str_starts_with($biodiversity->$imageField, 'images/') ? asset($biodiversity->$imageField) : Storage::disk('public')->url($biodiversity->$imageField)) : null;
                                                            @endphp
                                                            @if($imageUrl)
                                                                <div class="text-center mt-2">
                                                                    <img src="{{ $imageUrl }}" alt="{{ $biodiversity->name }} - Imagen {{ $i }}" class="img-thumbnail cursor-pointer" style="max-height: 50px;" onclick="showImageModal('{{ $imageUrl }}', '{{ addslashes($biodiversity->name) }} - Imagen {{ $i }}')">
                                                                </div>
                                                            @endif
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
                    <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="5">{{ old('description', $biodiversity->description) }}</textarea>
                    @error('description')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Publicaciones Relacionadas</label>
                    <select name="publications[]" id="publications" class="form-control select2" multiple>
                        @foreach($publications as $publication)
                            <option value="{{ $publication->id }}" {{ in_array($publication->id, old('publications', $biodiversity->publications->pluck('id')->toArray())) ? 'selected' : '' }}>
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
                    <button type="submit" class="btn btn-primary">Actualizar</button>
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
                const existingPublications = {!! json_encode($biodiversity->publications->keyBy('id')->toArray()) !!};
                
                publicationIds.forEach((id, index) => {
                    const publicationTitle = $('#publications option[value="' + id + '"]').text();
                    const publication = existingPublications[id] || {};
                    const pivot = publication.pivot || {};
                    
                    html += `
                        <div class="card mb-3">
                            <div class="card-header">${publicationTitle}</div>
                            <div class="card-body">
                                <input type="hidden" name="publications[]" value="${id}">
                                <div class="form-group">
                                    <label>Extracto Relevante</label>
                                    <textarea name="relevant_excerpts[]" class="form-control" rows="3">${pivot.relevant_excerpt || ''}</textarea>
                                </div>
                                <div class="form-group">
                                    <label>Referencia de Página</label>
                                    <input type="text" name="page_references[]" class="form-control" placeholder="ej. p. 45-47" value="${pivot.page_reference || ''}">
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

            // Valores actuales para preselección
            const currentReino = '{{ $biodiversityCategory->idreino ?? "" }}';
            const currentClase = '{{ $biodiversityCategory->familia->orden->clase->idclase ?? "" }}';
            const currentOrden = '{{ $biodiversityCategory->familia->orden->idorden ?? "" }}';
            const currentFamilia = '{{ $biodiversityCategory->idfamilia ?? "" }}';

            // Función para cargar y preseleccionar clases
            function loadClases(reinoId, selectedClase = null) {
                const claseSelect = $('#clase_id');
                claseSelect.html('<option value="">🔍 Seleccione una clase</option>');
                
                if (reinoId) {
                    $.get(`{{ url('admin/api/clases/reino') }}/${reinoId}`, function(clases) {
                        clases.forEach(clase => {
                            const selected = selectedClase && clase.idclase == selectedClase ? 'selected' : '';
                            const descripcion = clase.definicion ? ` - ${clase.definicion.substring(0, 50)}${clase.definicion.length > 50 ? '...' : ''}` : '';
                            claseSelect.append(`<option value="${clase.idclase}" ${selected}>🎯 ${clase.nombre}${descripcion}</option>`);
                        });
                        if (selectedClase) {
                            claseSelect.trigger('change');
                        }
                    });
                }
            }

            // Función para cargar y preseleccionar órdenes
            function loadOrdenes(claseId, selectedOrden = null) {
                const ordenSelect = $('#orden_id');
                ordenSelect.html('<option value="">🔍 Seleccione un orden</option>');
                
                if (claseId) {
                    $.get(`{{ url('admin/api/ordenes/clase') }}/${claseId}`, function(ordenes) {
                        ordenes.forEach(orden => {
                            const selected = selectedOrden && orden.idorden == selectedOrden ? 'selected' : '';
                            const descripcion = orden.definicion ? ` - ${orden.definicion.substring(0, 50)}${orden.definicion.length > 50 ? '...' : ''}` : '';
                            ordenSelect.append(`<option value="${orden.idorden}" ${selected}>📋 ${orden.nombre}${descripcion}</option>`);
                        });
                        if (selectedOrden) {
                            ordenSelect.trigger('change');
                        }
                    });
                }
            }

            // Función para cargar y preseleccionar familias
            function loadFamilias(ordenId, selectedFamilia = null) {
                const familiaSelect = $('#idfamilia');
                familiaSelect.html('<option value="">🔍 Seleccione una familia</option>');
                
                if (ordenId) {
                    $.get(`{{ url('admin/api/familias/orden') }}/${ordenId}`, function(familias) {
                        familias.forEach(familia => {
                            const selected = selectedFamilia && familia.idfamilia == selectedFamilia ? 'selected' : '';
                            const descripcion = familia.definicion ? ` - ${familia.definicion.substring(0, 50)}${familia.definicion.length > 50 ? '...' : ''}` : '';
                            familiaSelect.append(`<option value="${familia.idfamilia}" ${selected}>👥 ${familia.nombre}${descripcion}</option>`);
                        });
                    });
                }
            }

            // Cargar datos iniciales
            if (currentReino) {
                loadClases(currentReino, currentClase);
            }

            // Manejar cambio de reino
            $('#idreino').on('change', function() {
                const reinoId = $(this).val();
                $('#clase_id').html('<option value="">🔍 Seleccione una clase</option>');
                $('#orden_id').html('<option value="">🔍 Seleccione un orden</option>');
                $('#idfamilia').html('<option value="">🔍 Seleccione una familia</option>');
                
                if (reinoId) {
                    loadClases(reinoId);
                }
            });

            // Manejar cambio de clase
            $('#clase_id').on('change', function() {
                const claseId = $(this).val();
                $('#orden_id').html('<option value="">🔍 Seleccione un orden</option>');
                $('#idfamilia').html('<option value="">🔍 Seleccione una familia</option>');
                
                if (claseId) {
                    loadOrdenes(claseId, claseId == currentClase ? currentOrden : null);
                }
            });

            // Manejar cambio de orden
            $('#orden_id').on('change', function() {
                const ordenId = $(this).val();
                $('#idfamilia').html('<option value="">🔍 Seleccione una familia</option>');
                
                if (ordenId) {
                    loadFamilias(ordenId, ordenId == currentOrden ? currentFamilia : null);
                }
            });

            // Función para mostrar el modal de imagen
            function showImageModal(imageUrl, imageName) {
                $('#modalImage').attr('src', imageUrl);
                $('#modalImage').attr('alt', imageName);
                $('#imageModalLabel').text('Imagen de ' + imageName);
                $('#imageModal').modal('show');
            }

            // Hacer la función global
            window.showImageModal = showImageModal;
        });
    </script>
@stop