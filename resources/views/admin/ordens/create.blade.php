@extends('adminlte::page')

@section('title', 'Crear Orden Taxonómico')

@section('content_header')
    <h1>Crear Orden Taxonómico</h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-plus me-2"></i>
                        Nuevo Orden Taxonómico
                    </h3>
                </div>
                
                <form action="{{ route('admin.ordens.store') }}" method="POST">
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
                            <div class="col-md-4">
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
                                    <div class="form-text">Reino al que pertenece este orden</div>
                                    @error('idreino')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="idclase" class="form-label">
                                        <i class="fas fa-layer-group me-1"></i>
                                        Clase Taxonómica <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('idclase') is-invalid @enderror" 
                                            id="idclase" 
                                            name="idclase" 
                                            required>
                                        <option value="">Selecciona una clase</option>
                                        @foreach($clases as $clase)
                                            <option value="{{ $clase->idclase }}" 
                                                    data-reino="{{ $clase->idreino }}"
                                                    {{ old('idclase', request('idclase')) == $clase->idclase ? 'selected' : '' }}>
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
                            
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="nombre" class="form-label">
                                        <i class="fas fa-signature me-1"></i>
                                        Nombre del Orden <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('nombre') is-invalid @enderror" 
                                           id="nombre" 
                                           name="nombre" 
                                           value="{{ old('nombre') }}" 
                                           placeholder="Ej: Primates, Carnivora, Lepidoptera"
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
                                      placeholder="Describe las características principales que definen este orden taxonómico..."
                                      required>{{ old('definicion') }}</textarea>
                            @error('definicion')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                            <div class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                Proporciona una descripción detallada de las características que definen este orden.
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
                                        <strong>Clase:</strong> <span id="preview-clase" class="text-muted">Selecciona una clase</span>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <strong>Definición:</strong>
                                    <p id="preview-definicion" class="text-muted mb-0">Ingresa la definición</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-footer">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.ordens.index') }}" class="btn btn-secondary">
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
                                    Guardar Orden
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
    });
    
    $('#idclase').on('change', function() {
        const selectedText = $(this).find('option:selected').text();
        const value = $(this).val();
        $('#preview-clase').text(value ? selectedText : 'Selecciona una clase').toggleClass('text-muted', !value);
    });
    
    $('#definicion').on('input', function() {
        const value = $(this).val();
        $('#preview-definicion').text(value || 'Ingresa la definición').toggleClass('text-muted', !value);
    });
    
    // Initialize preview with existing values
    $('#nombre').trigger('input');
    $('#idclase').trigger('change');
    $('#definicion').trigger('input');
    
    // Form validation
    $('form').on('submit', function(e) {
        let isValid = true;
        
        // Check required fields
        const requiredFields = ['nombre', 'idclase', 'definicion'];
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