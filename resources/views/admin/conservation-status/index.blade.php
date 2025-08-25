@extends('adminlte::page')

@section('title', 'Estados de Conservación')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1>Estados de Conservación</h1>
        <div>
            <a href="{{ route('admin.conservation-status.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Nuevo Estado
            </a>
            <a href="{{ route('admin.conservation-status.export') }}" class="btn btn-success">
                <i class="fas fa-download"></i> Exportar
            </a>
        </div>
    </div>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-shield-alt me-2"></i>
                        Estados de Conservación
                    </h3>
                    <div class="btn-group">
                        <a href="{{ route('admin.conservation-status.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i>
                            Nuevo Estado
                        </a>
                        <a href="{{ route('admin.conservation-status.export') }}" class="btn btn-success">
                            <i class="fas fa-download me-1"></i>
                            Exportar
                        </a>
                    </div>
                </div>
                
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($conservationStatuses->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Código</th>
                                        <th>Nombre</th>
                                        <th>Descripción</th>
                                        <th>Color</th>
                                        <th>Estado</th>
                                        <th>Fecha de Creación</th>
                                        <th class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($conservationStatuses as $status)
                                        <tr>
                                            <td>
                                                @php
                                                    $colorClass = $status->color ?? 'secondary';
                                                    // Si el color es un código hex, usar estilo inline
                                                    if (str_starts_with($colorClass, '#')) {
                                                        $badgeStyle = "background-color: {$colorClass}; color: #ffffff; font-weight: bold; text-shadow: 1px 1px 2px rgba(0,0,0,0.7);";
                                                        $badgeClass = "badge";
                                                    } else {
                                                        // Si es una clase de Bootstrap, usar la clase
                                                        $badgeStyle = "";
                                                        $badgeClass = "badge bg-{$colorClass}";
                                                    }
                                                @endphp
                                                <span class="{{ $badgeClass }}" @if($badgeStyle) style="{{ $badgeStyle }}" @endif>
                                                    {{ $status->code }}
                                                </span>
                                            </td>
                                            <td class="fw-bold">{{ $status->name }}</td>
                                            <td>
                                                @if($status->description)
                                                    {{ Str::limit($status->description, 50) }}
                                                @else
                                                    <span class="text-muted">Sin descripción</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($status->color)
                                                    <div class="d-flex align-items-center">
                                                        @php
                                                            // Si es una clase de Bootstrap, convertir a color hex para la vista previa
                                                            $previewColor = match($status->color) {
                                                                'danger' => '#dc3545',
                                                                'warning' => '#ffc107',
                                                                'info' => '#0dcaf0',
                                                                'success' => '#198754',
                                                                'secondary' => '#6c757d',
                                                                'primary' => '#0d6efd',
                                                                default => $status->color
                                                            };
                                                        @endphp
                                                        <div class="color-preview me-2" style="width: 25px; height: 25px; background-color: {{ $previewColor }}; border: 2px solid #dee2e6; border-radius: 4px; box-shadow: 0 1px 3px rgba(0,0,0,0.2);"></div>
                                                        <code class="text-muted">{{ $status->color }}</code>
                                                    </div>
                                                @else
                                                    <span class="text-muted">Sin color</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($status->is_active)
                                                    <span class="badge bg-success">Activo</span>
                                                @else
                                                    <span class="badge bg-secondary">Inactivo</span>
                                                @endif
                                            </td>
                                            <td>{{ $status->created_at->format('d/m/Y H:i') }}</td>
                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('admin.conservation-status.show', $status) }}" 
                                                       class="btn btn-outline-info" 
                                                       title="Ver detalles">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.conservation-status.edit', $status) }}" 
                                                       class="btn btn-outline-warning" 
                                                       title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button type="button" 
                                                            class="btn btn-outline-danger" 
                                                            title="Eliminar"
                                                            onclick="confirmDelete('{{ $status->id }}', '{{ $status->name }}')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="d-flex justify-content-center">
                            {{ $conservationStatuses->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-shield-alt fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No hay estados de conservación registrados</h5>
                            <p class="text-muted">Comienza creando tu primer estado de conservación.</p>
                            <a href="{{ route('admin.conservation-status.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i>
                                Crear Estado de Conservación
                            </a>
                        </div>
                    @endif
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

@push('styles')
<style>
.color-preview {
    display: inline-block;
    min-width: 25px;
    min-height: 25px;
    border-radius: 4px;
    border: 2px solid #dee2e6;
    box-shadow: 0 1px 3px rgba(0,0,0,0.2);
    transition: transform 0.2s ease;
}

.color-preview:hover {
    transform: scale(1.1);
    box-shadow: 0 2px 6px rgba(0,0,0,0.3);
}

.badge {
    font-size: 0.85em;
    padding: 0.5em 0.75em;
    border-radius: 0.375rem;
}

/* Ensure text is readable on any background color */
.badge[style*="background-color"] {
    color: #ffffff !important;
    text-shadow: 1px 1px 2px rgba(0,0,0,0.7) !important;
}

/* Special handling for light colors */
.badge[style*="background-color: #ffffff"],
.badge[style*="background-color: #f8f9fa"],
.badge[style*="background-color: #e9ecef"],
.badge[style*="background-color: #dee2e6"],
.badge[style*="background-color: #ced4da"],
.badge[style*="background-color: #adb5bd"],
.badge[style*="background-color: #6c757d"] {
    color: #000000 !important;
    text-shadow: none !important;
}

.table-responsive {
    border-radius: 0.375rem;
    overflow: hidden;
}

.table th {
    border-top: none;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.85em;
    letter-spacing: 0.5px;
}

.table td {
    vertical-align: middle;
    padding: 0.75rem;
}
</style>
@endpush

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