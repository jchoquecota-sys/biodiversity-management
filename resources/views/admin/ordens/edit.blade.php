@extends('adminlte::page')

@section('title', 'Editar Orden Taxonómico')

@section('content_header')
    <h1>Editar Orden Taxonómico</h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-edit me-2"></i>
                        Editar: {{ $orden->nombre }}
                    </h3>
                </div>
                
                <form action="{{ route('admin.ordens.update', $orden) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
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
                                            <option value="{{ $reino->id }}" {{ old('idreino', $orden->clase->idreino ?? '') == $reino->id ? 'selected' : '' }}>
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
                                                    {{ old('idclase', $orden->idclase) == $clase->idclase ? 'selected' : '' }}>
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
                                           value="{{ old('nombre', $orden->nombre) }}" 
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
                                      required>{{ old('definicion', $orden->definicion) }}</textarea>
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

                        <!-- Metadata -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">
                                        <i class="fas fa-calendar-plus me-1"></i>
                                        Fecha de Creación
                                    </label>
                                    <input type="text" 
                                           class="form-control" 
                                           value="{{ $orden->created_at ? $orden->created_at->format('d/m/Y H:i:s') : 'N/A' }}" 
                                           readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">
                                        <i class="fas fa-calendar-edit me-1"></i>
                                        Última Actualización
                                    </label>
                                    <input type="text" 
                                           class="form-control" 
                                           value="{{ $orden->updated_at ? $orden->updated_at->format('d/m/Y H:i:s') : 'N/A' }}" 
                                           readonly>
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
                                <a href="{{ route('admin.ordens.show', $orden) }}" class="btn btn-info me-2">
                                    <i class="fas fa-eye me-1"></i>
                                    Ver Detalles
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i>
                                    Actualizar Orden
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="col-md-4">
            <!-- Familias Asociadas -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-users me-2"></i>
                        Familias Asociadas
                    </h3>
                </div>
                
                <div class="card-body">
                    @if($orden->familias && $orden->familias->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($orden->familias as $familia)
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">{{ $familia->nombre }}</h6>
                                        <small class="text-muted">{{ Str::limit($familia->definicion, 40) }}</small>
                                    </div>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.familias.show', $familia) }}" 
                                           class="btn btn-outline-info btn-sm" 
                                           title="Ver familia">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.familias.edit', $familia) }}" 
                                           class="btn btn-outline-warning btn-sm" 
                                           title="Editar familia">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="mt-3 text-center">
                            <a href="{{ route('admin.familias.create', ['idorden' => $orden->idorden]) }}" 
                               class="btn btn-primary btn-sm">
                                <i class="fas fa-plus me-1"></i>
                                Agregar Familia
                            </a>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-users fa-2x text-muted mb-3"></i>
                            <p class="text-muted mb-3">No hay familias asociadas a este orden</p>
                            <a href="{{ route('admin.familias.create', ['idorden' => $orden->idorden]) }}" 
                               class="btn btn-primary btn-sm">
                                <i class="fas fa-plus me-1"></i>
                                Crear Primera Familia
                            </a>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Información de la Clase -->
            @if($orden->clase)
            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-layer-group me-2"></i>
                        Clase Taxonómica
                    </h3>
                </div>
                
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h5 class="text-primary mb-1">{{ $orden->clase->nombre }}</h5>
                            <p class="text-muted mb-2">{{ Str::limit($orden->clase->definicion, 80) }}</p>
                            <small class="text-muted">
                                <i class="fas fa-calendar me-1"></i>
                                Creada: {{ $orden->clase->created_at ? $orden->clase->created_at->format('d/m/Y') : 'N/A' }}
                            </small>
                        </div>
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('admin.clases.show', $orden->clase) }}" 
                               class="btn btn-outline-info btn-sm" 
                               title="Ver clase">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.clases.edit', $orden->clase) }}" 
                               class="btn btn-outline-warning btn-sm" 
                               title="Editar clase">
                                <i class="fas fa-edit"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            
            <!-- Statistics -->
            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-chart-bar me-2"></i>
                        Estadísticas
                    </h3>
                </div>
                
                <div class="card-body text-center">
                    <h4 class="text-success mb-0">{{ $orden->familias ? $orden->familias->count() : 0 }}</h4>
                    <small class="text-muted">Familias Asociadas</small>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('js')
<script>
$(document).ready(function() {
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