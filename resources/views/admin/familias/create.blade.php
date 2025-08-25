@extends('adminlte::page')

@section('title', 'Crear Familia Taxonómica')

@section('content_header')
    <h1>Crear Familia Taxonómica</h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-plus me-2"></i>
                        Nueva Familia Taxonómica
                    </h3>
                </div>
                
                <form action="{{ route('admin.familias.store') }}" method="POST">
                    @csrf
                    
                    <div class="card-body">
                        <!-- Display validation errors -->
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <h6><i class="fas fa-exclamation-triangle me-1"></i> Por favor corrige los siguientes errores:</h6>
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="idreino" class="form-label">
                                        <i class="fas fa-crown me-1"></i>
                                        Reino <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('idreino') is-invalid @enderror" 
                                            id="idreino" 
                                            name="idreino" 
                                            required>
                                        <option value="">Seleccionar Reino</option>
                                        @foreach($reinos as $reino)
                                            <option value="{{ $reino->id }}" {{ old('idreino') == $reino->id ? 'selected' : '' }}>
                                                {{ $reino->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="form-text">Reino al que pertenece esta familia</div>
                                    @error('idreino')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="idclase" class="form-label">
                                        <i class="fas fa-layer-group me-1"></i>
                                        Clase <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('idclase') is-invalid @enderror" 
                                            id="idclase" 
                                            name="idclase" 
                                            required>
                                        <option value="">Seleccionar Clase</option>
                                        @foreach($clases as $clase)
                                            <option value="{{ $clase->idclase }}" 
                                                    data-reino="{{ $clase->idreino }}"
                                                    {{ old('idclase') == $clase->idclase ? 'selected' : '' }}>
                                                {{ $clase->nombre }} ({{ $clase->reino->nombre ?? 'Sin reino' }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('idclase')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="idorden" class="form-label">
                                        <i class="fas fa-sitemap me-1"></i>
                                        Orden Taxonómico <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('idorden') is-invalid @enderror" 
                                            id="idorden" 
                                            name="idorden" 
                                            required>
                                        <option value="">Selecciona un orden</option>
                                        @foreach($ordens as $orden)
                                            <option value="{{ $orden->idorden }}" 
                                                    data-clase="{{ $orden->idclase }}"
                                                    data-reino="{{ $orden->clase->idreino ?? '' }}"
                                                    {{ old('idorden', request('idorden')) == $orden->idorden ? 'selected' : '' }}>
                                                {{ $orden->nombre }}
                                                @if($orden->clase)
                                                    ({{ $orden->clase->nombre }})
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('idorden')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="nombre" class="form-label">
                                        <i class="fas fa-signature me-1"></i>
                                        Nombre de la Familia <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('nombre') is-invalid @enderror" 
                                           id="nombre" 
                                           name="nombre" 
                                           value="{{ old('nombre') }}" 
                                           placeholder="Ej: Felidae, Canidae, Rosaceae"
                                           required>
                                    @error('nombre')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="definicion" class="form-label">
                                <i class="fas fa-align-left me-1"></i>
                                Definición <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control @error('definicion') is-invalid @enderror" 
                                      id="definicion" 
                                      name="definicion" 
                                      rows="4" 
                                      placeholder="Describe las características principales que definen esta familia taxonómica..."
                                      required>{{ old('definicion') }}</textarea>
                            @error('definicion')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                            <div class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                Proporciona una descripción detallada de las características que definen esta familia.
                            </div>
                        </div>

                        <!-- Preview Section -->
                        <div class="card bg-light">
                            <div class="card-header">
                                <h6 class="mb-0">
                                    <i class="fas fa-eye me-1"></i>
                                    Vista Previa
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <strong>Nombre:</strong> <span id="preview-nombre" class="text-muted">Ingresa el nombre</span>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Orden:</strong> <span id="preview-orden" class="text-muted">Selecciona un orden</span>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-6">
                                        <strong>Clase:</strong> <span id="preview-clase" class="text-muted">-</span>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <strong>Definición:</strong>
                                    <p id="preview-definicion" class="text-muted mb-0">Ingresa la definición</p>
                                </div>
                            </div>
                        </div>

                        <!-- Jerarquía Taxonómica -->
                        <div class="card bg-info bg-opacity-10 mt-3" id="hierarchy-card" style="display: none;">
                            <div class="card-header">
                                <h6 class="mb-0">
                                    <i class="fas fa-sitemap me-1"></i>
                                    Jerarquía Taxonómica
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="d-flex flex-column">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fas fa-layer-group text-warning me-2"></i>
                                        <span class="fw-bold">Clase:</span>
                                        <span id="hierarchy-clase" class="ms-2">-</span>
                                    </div>
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fas fa-arrow-down text-muted ms-2 me-2"></i>
                                    </div>
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fas fa-sitemap text-info me-2"></i>
                                        <span class="fw-bold">Orden:</span>
                                        <span id="hierarchy-orden" class="ms-2">-</span>
                                    </div>
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fas fa-arrow-down text-muted ms-2 me-2"></i>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-users text-success me-2"></i>
                                        <span class="fw-bold text-success">Familia:</span>
                                        <span id="hierarchy-familia" class="ms-2 text-success">Nueva familia</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-footer">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.familias.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i>
                                Volver
                            </a>
                            <div>
                                <button type="reset" class="btn btn-outline-secondary me-2">
                                    <i class="fas fa-undo me-1"></i>
                                    Limpiar
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i>
                                    Guardar Familia
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@stop

@section('js')
<script>
$(document).ready(function() {
    // Live preview functionality
    $('#nombre').on('input', function() {
        const value = $(this).val();
        $('#preview-nombre').text(value || 'Ingresa el nombre').toggleClass('text-muted', !value);
        $('#hierarchy-familia').text(value || 'Nueva familia');
    });
    
    $('#idorden').on('change', function() {
        const selectedOption = $(this).find('option:selected');
        const ordenText = selectedOption.text();
        const claseText = selectedOption.data('clase') || '-';
        const value = $(this).val();
        
        $('#preview-orden').text(value ? ordenText : 'Selecciona un orden').toggleClass('text-muted', !value);
        $('#preview-clase').text(claseText).toggleClass('text-muted', claseText === '-' || claseText === 'Sin clase');
        
        // Update hierarchy
        $('#hierarchy-clase').text(claseText);
        $('#hierarchy-orden').text(value ? ordenText.split(' (')[0] : '-');
        
        // Show/hide hierarchy card
        if (value) {
            $('#hierarchy-card').show();
        } else {
            $('#hierarchy-card').hide();
        }
    });
    
    $('#definicion').on('input', function() {
        const value = $(this).val();
        $('#preview-definicion').text(value || 'Ingresa la definición').toggleClass('text-muted', !value);
    });
    
    // Initialize preview with existing values
    $('#nombre').trigger('input');
    $('#idorden').trigger('change');
    $('#definicion').trigger('input');
    
    // Form validation
    $('form').on('submit', function(e) {
        let isValid = true;
        
        // Check required fields
        const requiredFields = ['nombre', 'idorden', 'definicion'];
        requiredFields.forEach(function(field) {
            const $field = $(`#${field}`);
            if (!$field.val().trim()) {
                $field.addClass('is-invalid');
                isValid = false;
            } else {
                $field.removeClass('is-invalid');
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            $('html, body').animate({
                scrollTop: $('.is-invalid').first().offset().top - 100
            }, 500);
        }
    });
    
    // Remove validation errors on input
    $('.form-control, .form-select').on('input change', function() {
        $(this).removeClass('is-invalid');
    });
});
</script>
@stop