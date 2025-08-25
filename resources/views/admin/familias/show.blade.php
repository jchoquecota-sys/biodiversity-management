@extends('adminlte::page')

@section('title', 'Detalles de Familia Taxonómica')

@section('content_header')
    <h1>Detalles de Familia Taxonómica</h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-users me-2"></i>
                        {{ $familia->nombre }}
                    </h3>
                </div>
                
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-hashtag me-1"></i>
                                    ID de la Familia
                                </label>
                                <p class="form-control-plaintext">
                                    <span class="badge bg-primary fs-6">{{ $familia->idfamilia }}</span>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-signature me-1"></i>
                                    Nombre
                                </label>
                                <p class="form-control-plaintext fs-5 fw-bold text-primary">{{ $familia->nombre }}</p>
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
                                    <p class="mb-0">{{ $familia->definicion }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-sitemap me-1"></i>
                                    Orden Taxonómico
                                </label>
                                <p class="form-control-plaintext">
                                    @if($familia->orden)
                                        <a href="{{ route('admin.ordens.show', $familia->orden) }}" 
                                           class="text-decoration-none">
                                            <span class="badge bg-info fs-6">{{ $familia->orden->nombre }}</span>
                                        </a>
                                    @else
                                        <span class="badge bg-secondary fs-6">Sin orden asignado</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-layer-group me-1"></i>
                                    Clase Taxonómica
                                </label>
                                <p class="form-control-plaintext">
                                    @if($familia->orden && $familia->orden->clase)
                                        <a href="{{ route('admin.clases.show', $familia->orden->clase) }}" 
                                           class="text-decoration-none">
                                            <span class="badge bg-warning text-dark fs-6">{{ $familia->orden->clase->nombre }}</span>
                                        </a>
                                    @else
                                        <span class="badge bg-secondary fs-6">Sin clase asignada</span>
                                    @endif
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
                                    {{ $familia->created_at ? $familia->created_at->format('d/m/Y H:i:s') : 'N/A' }}
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
                                    {{ $familia->updated_at ? $familia->updated_at->format('d/m/Y H:i:s') : 'N/A' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card-footer">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.familias.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i>
                            Volver al Listado
                        </a>
                        <div>
                            <a href="{{ route('admin.familias.edit', $familia) }}" class="btn btn-warning">
                                <i class="fas fa-edit me-1"></i>
                                Editar
                            </a>
                            <button type="button" 
                                    class="btn btn-danger" 
                                    onclick="confirmDelete('{{ $familia->idfamilia }}', '{{ $familia->nombre }}')">
                                <i class="fas fa-trash me-1"></i>
                                Eliminar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <!-- Orden Taxonómico -->
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
                            <p class="text-muted mb-2">{{ Str::limit($familia->orden->definicion, 100) }}</p>
                            @if($familia->orden->clase)
                                <span class="badge bg-warning text-dark mb-2">{{ $familia->orden->clase->nombre }}</span>
                                <br>
                            @endif
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
            
            <!-- Clase Taxonómica -->
            @if($familia->orden && $familia->orden->clase)
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
                            <h5 class="text-primary mb-1">{{ $familia->orden->clase->nombre }}</h5>
                            <p class="text-muted mb-2">{{ Str::limit($familia->orden->clase->definicion, 100) }}</p>
                            <small class="text-muted">
                                <i class="fas fa-calendar me-1"></i>
                                Creada: {{ $familia->orden->clase->created_at ? $familia->orden->clase->created_at->format('d/m/Y') : 'N/A' }}
                            </small>
                        </div>
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('admin.clases.show', $familia->orden->clase) }}" 
                               class="btn btn-outline-info btn-sm" 
                               title="Ver clase">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.clases.edit', $familia->orden->clase) }}" 
                               class="btn btn-outline-warning btn-sm" 
                               title="Editar clase">
                                <i class="fas fa-edit"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            
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
                
                <div class="card-body text-center">
                    <div class="row">
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
            
            <!-- Acciones Rápidas -->
            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-bolt me-2"></i>
                        Acciones Rápidas
                    </h3>
                </div>
                
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @if($familia->orden)
                            <a href="{{ route('admin.ordens.show', $familia->orden) }}" 
                               class="btn btn-outline-info btn-sm">
                                <i class="fas fa-sitemap me-1"></i>
                                Ver Orden Completo
                            </a>
                        @endif
                        @if($familia->orden && $familia->orden->clase)
                            <a href="{{ route('admin.clases.show', $familia->orden->clase) }}" 
                               class="btn btn-outline-warning btn-sm">
                                <i class="fas fa-layer-group me-1"></i>
                                Ver Clase Completa
                            </a>
                        @endif
                        <a href="{{ route('admin.familias.edit', $familia) }}" 
                           class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-edit me-1"></i>
                            Editar Familia
                        </a>
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
                <p>¿Estás seguro de que deseas eliminar la familia <strong id="deleteItemName"></strong>?</p>
                <p class="text-muted">Esta acción no se puede deshacer.</p>
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
    document.getElementById('deleteForm').action = `/admin/familias/${id}`;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>
@stop