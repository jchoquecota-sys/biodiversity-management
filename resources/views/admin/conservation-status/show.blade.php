@extends('adminlte::page')

@section('title', 'Detalles del Estado de Conservación')

@section('content_header')
    <h1>Detalles del Estado de Conservación: {{ $conservationStatus->name }}</h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-shield-alt me-2"></i>
                        Detalles del Estado de Conservación
                    </h3>
                </div>
                
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <!-- Basic Information -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <i class="fas fa-info-circle me-2"></i>
                                        Información Básica
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Código:</label>
                                                <div>
                                                    <span class="badge fs-6" style="background-color: {{ $conservationStatus->color ?? '#6c757d' }}; color: white;">
                                                        {{ $conservationStatus->code }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Estado:</label>
                                                <div>
                                                    @if($conservationStatus->is_active)
                                                        <span class="badge bg-success fs-6">Activo</span>
                                                    @else
                                                        <span class="badge bg-secondary fs-6">Inactivo</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Nombre:</label>
                                        <div class="fs-5">{{ $conservationStatus->name }}</div>
                                    </div>
                                    
                                    @if($conservationStatus->description)
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Descripción:</label>
                                            <div class="text-muted">{{ $conservationStatus->description }}</div>
                                        </div>
                                    @endif
                                    
                                    @if($conservationStatus->color)
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Color:</label>
                                            <div class="d-flex align-items-center">
                                                <div class="color-preview me-3" style="width: 30px; height: 30px; background-color: {{ $conservationStatus->color }}; border: 2px solid #dee2e6; border-radius: 5px;"></div>
                                                <code class="fs-6">{{ $conservationStatus->color }}</code>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Usage Statistics -->
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <i class="fas fa-chart-bar me-2"></i>
                                        Estadísticas de Uso
                                    </h5>
                                </div>
                                <div class="card-body">
                                    @php
                                        $usageCount = $conservationStatus->biodiversityCategories()->count();
                                    @endphp
                                    
                                    <div class="row text-center">
                                        <div class="col-md-12">
                                            <div class="border rounded p-3">
                                                <h3 class="text-primary mb-1">{{ $usageCount }}</h3>
                                                <p class="text-muted mb-0">
                                                    {{ $usageCount === 1 ? 'Categoría de Biodiversidad' : 'Categorías de Biodiversidad' }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    @if($usageCount > 0)
                                        <div class="mt-3">
                                            <h6>Categorías que usan este estado:</h6>
                                            <div class="list-group list-group-flush">
                                                @foreach($conservationStatus->biodiversityCategories()->take(5)->get() as $category)
                                                    <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                                        <div>
                                                            <strong>{{ $category->scientific_name }}</strong>
                                                            @if($category->common_name)
                                                                <br><small class="text-muted">{{ $category->common_name }}</small>
                                                            @endif
                                                        </div>
                                                        <a href="{{ route('admin.biodiversity.show', $category) }}" class="btn btn-sm btn-outline-primary">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    </div>
                                                @endforeach
                                                
                                                @if($usageCount > 5)
                                                    <div class="list-group-item px-0 text-center">
                                                        <small class="text-muted">Y {{ $usageCount - 5 }} más...</small>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @else
                                        <div class="text-center mt-3">
                                            <i class="fas fa-info-circle text-muted fa-2x mb-2"></i>
                                            <p class="text-muted">Este estado de conservación no está siendo utilizado por ninguna categoría de biodiversidad.</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <!-- Metadata -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <i class="fas fa-clock me-2"></i>
                                        Metadatos
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Fecha de Creación:</label>
                                        <div>{{ $conservationStatus->created_at->format('d/m/Y H:i:s') }}</div>
                                        <small class="text-muted">{{ $conservationStatus->created_at->diffForHumans() }}</small>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Última Actualización:</label>
                                        <div>{{ $conservationStatus->updated_at->format('d/m/Y H:i:s') }}</div>
                                        <small class="text-muted">{{ $conservationStatus->updated_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Actions -->
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <i class="fas fa-cogs me-2"></i>
                                        Acciones
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="d-grid gap-2">
                                        <a href="{{ route('admin.conservation-status.edit', $conservationStatus) }}" class="btn btn-warning">
                                            <i class="fas fa-edit me-2"></i>
                                            Editar Estado
                                        </a>
                                        
                                        @if($usageCount === 0)
                                            <button type="button" 
                                                    class="btn btn-danger" 
                                                    onclick="confirmDelete('{{ $conservationStatus->id }}', '{{ $conservationStatus->name }}')">
                                                <i class="fas fa-trash me-2"></i>
                                                Eliminar Estado
                                            </button>
                                        @else
                                            <button type="button" class="btn btn-danger" disabled title="No se puede eliminar porque está en uso">
                                                <i class="fas fa-trash me-2"></i>
                                                Eliminar Estado
                                            </button>
                                            <small class="text-muted">No se puede eliminar porque está siendo utilizado</small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card-footer">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.conservation-status.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i>
                            Volver a la Lista
                        </a>
                        <a href="{{ route('admin.conservation-status.edit', $conservationStatus) }}" class="btn btn-primary">
                            <i class="fas fa-edit me-1"></i>
                            Editar Estado
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
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                    Confirmar Eliminación
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>¿Estás seguro de que deseas eliminar el estado de conservación <strong id="statusName"></strong>?</p>
                <p class="text-muted small">Esta acción no se puede deshacer.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-1"></i>
                        Eliminar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function confirmDelete(statusId, statusName) {
    document.getElementById('statusName').textContent = statusName;
    document.getElementById('deleteForm').action = `/admin/conservation-status/${statusId}`;
    
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}
</script>
@endpush