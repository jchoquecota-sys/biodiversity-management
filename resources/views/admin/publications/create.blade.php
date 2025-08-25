@extends('adminlte::page')

@section('title', 'Crear Nueva Publicación')

@section('content_header')
    <h1>Crear Nueva Publicación</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.publications.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="title">Título <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                            @error('title')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="author">Autor <span class="text-danger">*</span></label>
                            <input type="text" name="author" id="author" class="form-control @error('author') is-invalid @enderror" value="{{ old('author') }}" required>
                            @error('author')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="publication_year">Año de Publicación <span class="text-danger">*</span></label>
                            <input type="number" name="publication_year" id="publication_year" class="form-control @error('publication_year') is-invalid @enderror" value="{{ old('publication_year') }}" min="1800" max="{{ date('Y') }}" required>
                            @error('publication_year')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="journal">Revista/Journal</label>
                            <input type="text" name="journal" id="journal" class="form-control @error('journal') is-invalid @enderror" value="{{ old('journal') }}">
                            @error('journal')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="doi">DOI</label>
                            <input type="text" name="doi" id="doi" class="form-control @error('doi') is-invalid @enderror" value="{{ old('doi') }}" placeholder="10.xxxx/xxxxx">
                            @error('doi')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="abstract">Resumen/Abstract</label>
                    <textarea name="abstract" id="abstract" class="form-control @error('abstract') is-invalid @enderror" rows="5">{{ old('abstract') }}</textarea>
                    @error('abstract')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="pdf">Archivo PDF</label>
                    <div class="input-group">
                        <div class="custom-file">
                            <input type="file" name="pdf" id="pdf" class="custom-file-input @error('pdf') is-invalid @enderror" accept=".pdf">
                            <label class="custom-file-label" for="pdf">Seleccionar archivo</label>
                        </div>
                    </div>
                    @error('pdf')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Categorías de Biodiversidad Relacionadas</label>
                    <select name="biodiversity_categories[]" id="biodiversity_categories" class="form-control select2" multiple>
                        @foreach($biodiversityCategories as $category)
                            <option value="{{ $category->id }}" {{ in_array($category->id, old('biodiversity_categories', [])) ? 'selected' : '' }}>
                                {{ $category->name }} ({{ $category->scientific_name }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div id="category-details" class="d-none">
                    <h4>Detalles de Categorías</h4>
                    <div id="category-fields"></div>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                    <a href="{{ route('admin.publications.index') }}" class="btn btn-secondary">Cancelar</a>
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
                placeholder: 'Seleccione categorías relacionadas',
                allowClear: true
            });

            // Inicializar Summernote
            $('#abstract').summernote({
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

            // Manejar la selección de categorías (usando evento Select2)
            $('#biodiversity_categories').on('select2:change', function() {
                const selectedCategories = $(this).val();
                console.log('Select2 change event triggered, selected categories:', selectedCategories);
                if (selectedCategories && selectedCategories.length > 0) {
                    $('#category-details').removeClass('d-none');
                    generateCategoryFields(selectedCategories);
                } else {
                    $('#category-details').addClass('d-none');
                    $('#category-fields').html('');
                }
            });

            // También manejar el evento change normal como respaldo
            $('#biodiversity_categories').on('change', function() {
                const selectedCategories = $(this).val();
                console.log('Regular change event triggered, selected categories:', selectedCategories);
                if (selectedCategories && selectedCategories.length > 0) {
                    $('#category-details').removeClass('d-none');
                    generateCategoryFields(selectedCategories);
                } else {
                    $('#category-details').addClass('d-none');
                    $('#category-fields').html('');
                }
            });

            // Generar campos para detalles de categorías
            function generateCategoryFields(categoryIds) {
                let html = '';
                console.log('Generating fields for categories:', categoryIds);
                
                categoryIds.forEach((id, index) => {
                    const categoryName = $('#biodiversity_categories option[value="' + id + '"]').text();
                    console.log(`Category ${index}: ID=${id}, Name=${categoryName}`);
                    
                    html += `
                        <div class="card mb-3">
                            <div class="card-header">${categoryName}</div>
                            <div class="card-body">
                                <input type="hidden" name="biodiversity_categories[]" value="${id}">
                                <div class="form-group">
                                    <label>Extracto Relevante</label>
                                    <textarea name="relevant_excerpts[${index}]" class="form-control" rows="3"></textarea>
                                </div>
                                <div class="form-group">
                                    <label>Referencia de Página</label>
                                    <input type="text" name="page_references[${index}]" class="form-control" placeholder="ej. p. 45-47">
                                </div>
                            </div>
                        </div>
                    `;
                });
                console.log('Generated HTML:', html);
                $('#category-fields').html(html);
            }

            // Debug form submission
            $('form').on('submit', function(e) {
                console.log('Form being submitted');
                const formData = new FormData(this);
                console.log('Form data:');
                for (let pair of formData.entries()) {
                    console.log(pair[0] + ': ' + pair[1]);
                }
            });
            
            // Trigger change if there are pre-selected categories
            if ($('#biodiversity_categories').val() && $('#biodiversity_categories').val().length > 0) {
                $('#biodiversity_categories').trigger('change');
            }
        });
    </script>
@stop