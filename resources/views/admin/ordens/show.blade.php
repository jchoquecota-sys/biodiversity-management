@extends('adminlte::page')

@section('title', 'Detalles de Orden Taxonómico')

@section('content_header')
    <h1>Detalles de Orden Taxonómico</h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-sitemap me-2"></i>
                        {{ $orden->nombre }}
                    </h3>
                </div>
                
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-hashtag me-1"></i>
                                    ID del Orden
                                </label>
                                <p class="form-control-plaintext">
                                    <span class="badge bg-primary fs-6">{{ $orden->idorden }}</span>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-signature me-1"></i>
                                    Nombre
                                </label>
                                <p class="form-control-plaintext fs-5 fw-bold text-primary">{{ $orden->nombre }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-align-left me-1"></i>
                                    Definición
                                </label>
                                <div class="border rounded p-3 bg-light">
                                    <p class="mb-0">{{ $orden->definicion }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-layer-group me-1"></i>
                                    Clase Taxonómica
                                </label>
                                <p class="form-control-plaintext">
                                    @if($orden->clase)
                                        <a href="{{ route('admin.clases.show', $orden->clase) }}" 
                                           class="text-decoration-none">
                                            <span class="badge bg-info fs-6">{{ $orden->clase->nombre }}</span>
                                        </a>
                                    @else
                                        <span class="badge bg-secondary fs-6">Sin clase asignada</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-users me-1"></i>
                                    Familias Asociadas
                                </label>
                                <p class="form-control-plaintext">
                                    <span class="badge bg-success fs-6">{{ $orden->familias ? $orden->familias->count() : 0 }}</span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-calendar-plus me-1"></i>
                                    Fecha de Creación
                                </label>
                                <p class="form-control-plaintext">
                                    {{ $orden->created_at ? $orden->created_at->format('d/m/Y H:i:s') : 'N/A' }}
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-calendar-edit me-1"></i>
                                    Última Actualización
                                </label>
                                <p class="form-control-plaintext">
                                    {{ $orden->updated_at ? $orden->updated_at->format('d/m/Y H:i:s') : 'N/A' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card-footer">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.ordens.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i>
                            Volver al Listado
                        </a>
                        <div>
                            <a href="{{ route('admin.ordens.edit', $orden) }}" class="btn btn-warning">
                                <i class="fas fa-edit me-1"></i>
                                Editar
                            </a>
                            <button type="button" 
                                    class="btn btn-danger" 
                                    onclick="confirmDelete('{{ $orden->idorden }}', '{{ $orden->nombre }}')">
                                <i class="fas fa-trash me-1"></i>
                                Eliminar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <!-- Clase Taxonómica -->
            @if($orden->clase)
            <div class="card">
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
                            <p class="text-muted mb-2">{{ Str::limit($orden->clase->definicion, 100) }}</p>
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
            
            <!-- Familias Asociadas -->
            <div class="card {{ $orden->clase ? 'mt-3' : '' }}">
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
                                        <small class="text-muted">{{ Str::limit($familia->definicion, 50) }}</small>
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
            
            <!-- Statistics Card -->
            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-chart-bar me-2"></i>
                        Estadísticas
                    </h3>
                </div>
                
                <div class="card-body text-center">
                    <div class="row">
                        <div class="col-12">
                            <h4 class="text-success mb-0">{{ $orden->familias ? $orden->familias->count() : 0 }}</h4>
                            <small class="text-muted">Familias Asociadas</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Jerarquía Taxonómica -->
            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-sitemap me-2"></i>
                        Jerarquía Taxonómica
                    </h3>
                </div>
                
                <div class="card-body">
                    <div class="d-flex flex-column">
                        @if($orden->clase)
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-layer-group text-info me-2"></i>
                                <span class="fw-bold">Clase:</span>
                                <a href="{{ route('admin.clases.show', $orden->clase) }}" 
                                   class="ms-2 text-decoration-none">
                                    {{ $orden->clase->nombre }}
                                </a>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-arrow-down text-muted ms-2 me-2"></i>
                            </div>
                        @endif
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-sitemap text-primary me-2"></i>
                            <span class="fw-bold text-primary">Orden: {{ $orden->nombre }}</span>
                        </div>
                        @if($orden->familias && $orden->familias->count() > 0)
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-arrow-down text-muted ms-2 me-2"></i>
                            </div>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-users text-success me-2"></i>
                                <span class="fw-bold">Familias:</span>
                                <span class="ms-2 badge bg-success">{{ $orden->familias->count() }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar Eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>¿Estás seguro de que deseas eliminar el orden <strong id="deleteItemName"></strong>?</p>
                <p class="text-muted">Esta acción no se puede deshacer y eliminará todas las familias asociadas.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>
@stop

@section('js')
<script>
function confirmDelete(id, name) {
    document.getElementById('deleteItemName').textContent = name;
    document.getElementById('deleteForm').action = `/admin/ordens/${id}`;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>
@stop