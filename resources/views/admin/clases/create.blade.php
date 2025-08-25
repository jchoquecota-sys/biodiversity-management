@extends('adminlte::page')

@section('title', 'Crear Clase Taxonómica')

@section('content_header')
    <h1>Crear Clase Taxonómica</h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-plus me-2"></i>
                        Crear Clase Taxonómica
                    </h3>
                </div>
                
                <form action="{{ route('admin.clases.store') }}" method="POST">
                    @csrf
                    
                    <div class="card-body">
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <h6><i class="fas fa-exclamation-circle me-2"></i>Por favor corrige los siguientes errores:</h6>
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="idreino" class="form-label">
                                        <i class="fas fa-crown me-1"></i>
                                        Reino <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-control @error('idreino') is-invalid @enderror" 
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
                                    <div class="form-text">Reino al que pertenece esta clase</div>
                                    @error('idreino')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="nombre" class="form-label">
                                        <i class="fas fa-signature me-1"></i>
                                        Nombre <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('nombre') is-invalid @enderror" 
                                           id="nombre" 
                                           name="nombre" 
                                           value="{{ old('nombre') }}" 
                                           placeholder="Ej: Mammalia, Aves, Reptilia"
                                           required>
                                    <div class="form-text">Nombre científico de la clase taxonómica</div>
                                    @error('nombre')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="definicion" class="form-label">
                                        <i class="fas fa-align-left me-1"></i>
                                        Definición <span class="text-danger">*</span>
                                    </label>
                                    <textarea class="form-control @error('definicion') is-invalid @enderror" 
                                              id="definicion" 
                                              name="definicion" 
                                              rows="4" 
                                              placeholder="Descripción y características principales de la clase taxonómica"
                                              required>{{ old('definicion') }}</textarea>
                                    <div class="form-text">Descripción detallada de las características que definen esta clase</div>
                                    @error('definicion')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-footer">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.clases.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i>
                                Volver
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>
                                Crear Clase
                            </button>
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
    // Auto-capitalize first letter of nombre field
    $('#nombre').on('input', function() {
        let value = $(this).val();
        if (value.length > 0) {
            $(this).val(value.charAt(0).toUpperCase() + value.slice(1));
        }
    });
});
</script>
@stop