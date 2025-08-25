@extends('adminlte::page')

@section('title', 'Detalles de Clase Taxonómica')

@section('content_header')
    <h1>Detalles de Clase Taxonómica</h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-layer-group me-2"></i>
                        {{ $clase->nombre }}
                    </h3>
                </div>
                
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-hashtag me-1"></i>
                                    ID de la Clase
                                </label>
                                <p class="form-control-plaintext">
                                    <span class="badge bg-primary fs-6">{{ $clase->idclase }}</span>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-signature me-1"></i>
                                    Nombre
                                </label>
                                <p class="form-control-plaintext fs-5 fw-bold text-primary">{{ $clase->nombre }}</p>
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
                                    <p class="mb-0">{{ $clase->definicion }}</p>
                                </div>
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
                                    {{ $clase->created_at ? $clase->created_at->format('d/m/Y H:i:s') : 'N/A' }}
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
                                    {{ $clase->updated_at ? $clase->updated_at->format('d/m/Y H:i:s') : 'N/A' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card-footer">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.clases.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i>
                            Volver al Listado
                        </a>
                        <div>
                            <a href="{{ route('admin.clases.edit', $clase) }}" class="btn btn-warning">
                                <i class="fas fa-edit me-1"></i>
                                Editar
                            </a>
                            <button type="button" 
                                    class="btn btn-danger" 
                                    onclick="confirmDelete('{{ $clase->idclase }}', '{{ $clase->nombre }}')">
                                <i class="fas fa-trash me-1"></i>
                                Eliminar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-sitemap me-2"></i>
                        Órdenes Asociados
                    </h3>
                </div>
                
                <div class="card-body">
                    @if($clase->ordens && $clase->ordens->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($clase->ordens as $orden)
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">{{ $orden->nombre }}</h6>
                                        <small class="text-muted">{{ Str::limit($orden->definicion, 50) }}</small>
                                        @if(isset($orden->familias_count))
                                            <br><small class="badge bg-info">{{ $orden->familias_count }} familias</small>
                                        @endif
                                    </div>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.ordens.show', $orden) }}" 
                                           class="btn btn-outline-info btn-sm" 
                                           title="Ver orden">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.ordens.edit', $orden) }}" 
                                           class="btn btn-outline-warning btn-sm" 
                                           title="Editar orden">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="mt-3 text-center">
                            <a href="{{ route('admin.ordens.create', ['idclase' => $clase->idclase]) }}" 
                               class="btn btn-primary btn-sm">
                                <i class="fas fa-plus me-1"></i>
                                Agregar Orden
                            </a>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-sitemap fa-2x text-muted mb-3"></i>
                            <p class="text-muted mb-3">No hay órdenes asociados a esta clase</p>
                            <a href="{{ route('admin.ordens.create', ['idclase' => $clase->idclase]) }}" 
                               class="btn btn-primary btn-sm">
                                <i class="fas fa-plus me-1"></i>
                                Crear Primer Orden
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
                
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-12">
                            <div class="border-bottom pb-2 mb-2">
                                <h4 class="text-primary mb-0">{{ $clase->ordens ? $clase->ordens->count() : 0 }}</h4>
                                <small class="text-muted">Órdenes</small>
                            </div>
                        </div>
                        <div class="col-12">
                            <h4 class="text-info mb-0">
                                {{ $clase->ordens ? $clase->ordens->sum('familias_count') : 0 }}
                            </h4>
                            <small class="text-muted">Familias Totales</small>
                        </div>
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
                <p>¿Estás seguro de que deseas eliminar la clase <strong id="deleteItemName"></strong>?</p>
                <p class="text-muted">Esta acción no se puede deshacer y eliminará todos los órdenes y familias asociados.</p>
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
    document.getElementById('deleteForm').action = `/admin/clases/${id}`;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>
@stop