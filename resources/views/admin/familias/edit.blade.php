@extends('adminlte::page')

@section('title', 'Editar Familia Taxonómica')

@section('content_header')
    <h1>Editar Familia Taxonómica</h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-edit me-2"></i>
                        Editar: {{ $familia->nombre }}
                    </h3>
                </div>
                
                <form action="{{ route('admin.familias.update', $familia) }}" method="POST">
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
                                        <option value="">Selecciona un reino</option>
                                        @foreach($reinos as $reino)
                                            <option value="{{ $reino->idreino }}" 
                                                    {{ old('idreino', $familia->orden->clase->idreino ?? '') == $reino->idreino ? 'selected' : '' }}>
                                                {{ $reino->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
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
                                        <option value="">Selecciona una clase</option>
                                        @foreach($clases as $clase)
                                            <option value="{{ $clase->idclase }}" 
                                                    data-reino="{{ $clase->reino ? $clase->reino->nombre : 'Sin reino' }}"
                                                    {{ old('idclase', $familia->orden->idclase ?? '') == $clase->idclase ? 'selected' : '' }}>
                                                {{ $clase->nombre }}
                                                @if($clase->reino)
                                                    ({{ $clase->reino->nombre }})
                                                @endif
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
                                                    data-clase="{{ $orden->clase ? $orden->clase->nombre : 'Sin clase' }}"
                                                    data-reino="{{ $orden->clase && $orden->clase->reino ? $orden->clase->reino->nombre : 'Sin reino' }}"
                                                    {{ old('idorden', $familia->idorden) == $orden->idorden ? 'selected' : '' }}>
                                                {{ $orden->nombre }}
                                                @if($orden->clase)
                                                    ({{ $orden->clase->nombre }}
                                                    @if($orden->clase->reino)
                                                        - {{ $orden->clase->reino->nombre }}
                                                    @endif
                                                    )
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
                                           value="{{ old('nombre', $familia->nombre) }}" 
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
                                      required>{{ old('definicion', $familia->definicion) }}</textarea>
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
                                           value="{{ $familia->created_at ? $familia->created_at->format('d/m/Y H:i:s') : 'N/A' }}" 
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
                                           value="{{ $familia->updated_at ? $familia->updated_at->format('d/m/Y H:i:s') : 'N/A' }}" 
                                           readonly>
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
                                <a href="{{ route('admin.familias.show', $familia) }}" class="btn btn-info me-2">
                                    <i class="fas fa-eye me-1"></i>
                                    Ver Detalles
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i>
                                    Actualizar Familia
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="col-md-4">
            <!-- Orden Asociado -->
            @if($familia->orden)
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-sitemap me-2"></i>
                        Orden Taxonómico
                    </h3>
                </div>
                
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h5 class="text-primary mb-1">{{ $familia->orden->nombre }}</h5>
                            <p class="text-muted mb-2">{{ Str::limit($familia->orden->definicion, 80) }}</p>
                            @if($familia->orden->clase)
                                <span class="badge bg-warning text-dark mb-2">{{ $familia->orden->clase->nombre }}</span>
                            @endif
                            <br>
                            <small class="text-muted">
                                <i class="fas fa-calendar me-1"></i>
                                Creado: {{ $familia->orden->created_at ? $familia->orden->created_at->format('d/m/Y') : 'N/A' }}
                            </small>
                        </div>
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('admin.ordens.show', $familia->orden) }}" 
                               class="btn btn-outline-info btn-sm" 
                               title="Ver orden">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.ordens.edit', $familia->orden) }}" 
                               class="btn btn-outline-warning btn-sm" 
                               title="Editar orden">
                                <i class="fas fa-edit"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            
            <!-- Jerarquía Taxonómica -->
            <div class="card {{ $familia->orden ? 'mt-3' : '' }}">
                <div class="card-header">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-sitemap me-2"></i>
                        Jerarquía Taxonómica
                    </h3>
                </div>
                
                <div class="card-body">
                    <div class="d-flex flex-column">
                        @if($familia->orden && $familia->orden->clase)
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-layer-group text-warning me-2"></i>
                                <span class="fw-bold">Clase:</span>
                                <a href="{{ route('admin.clases.show', $familia->orden->clase) }}" 
                                   class="ms-2 text-decoration-none">
                                    {{ $familia->orden->clase->nombre }}
                                </a>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-arrow-down text-muted ms-2 me-2"></i>
                            </div>
                        @endif
                        @if($familia->orden)
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-sitemap text-info me-2"></i>
                                <span class="fw-bold">Orden:</span>
                                <a href="{{ route('admin.ordens.show', $familia->orden) }}" 
                                   class="ms-2 text-decoration-none">
                                    {{ $familia->orden->nombre }}
                                </a>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-arrow-down text-muted ms-2 me-2"></i>
                            </div>
                        @endif
                        <div class="d-flex align-items-center">
                            <i class="fas fa-users text-success me-2"></i>
                            <span class="fw-bold text-success">Familia: {{ $familia->nombre }}</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Información Adicional -->
            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        Información
                    </h3>
                </div>
                
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-12">
                            <div class="border-bottom pb-2 mb-2">
                                <h4 class="text-primary mb-0">{{ $familia->idfamilia }}</h4>
                                <small class="text-muted">ID de la Familia</small>
                            </div>
                        </div>
                        <div class="col-12">
                            <small class="text-muted">
                                <i class="fas fa-calendar-plus me-1"></i>
                                Creada: {{ $familia->created_at ? $familia->created_at->format('d/m/Y') : 'N/A' }}
                            </small>
                        </div>
                    </div>
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