@extends('adminlte::page')

@section('title', 'Clases Taxonómicas')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1>Clases Taxonómicas</h1>
        <div>
            <a href="{{ route('admin.clases.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Nueva Clase
            </a>
            <a href="{{ route('admin.clases.export') }}" class="btn btn-success">
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
                        <i class="fas fa-layer-group me-2"></i>
                        Clases Taxonómicas
                    </h3>
                    <div class="btn-group">
                        <a href="{{ route('admin.clases.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i>
                            Nueva Clase
                        </a>
                        <a href="{{ route('admin.clases.export') }}" class="btn btn-success">
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

                    <div class="table-responsive">
                        <table id="clasesTable" class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Definición</th>
                                    <th>Órdenes</th>
                                    <th>Fecha de Creación</th>
                                    <th class="text-center">Acciones</th>
                                </tr>
                            </thead>
                        </table>
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
                <p class="text-muted">Esta acción no se puede deshacer y eliminará todos los órdenes asociados.</p>
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

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap4.min.css">
@stop

@section('js')
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/responsive.bootstrap4.min.js"></script>
<script>
$(document).ready(function() {
    $('#clasesTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('admin.clases.index') }}',
        columns: [
            { data: 'idclase', name: 'idclase', render: function(data) {
                return '<span class="badge bg-primary">' + data + '</span>';
            }},
            { data: 'nombre', name: 'nombre', render: function(data) {
                return '<span class="fw-bold">' + data + '</span>';
            }},
            { data: 'definicion', name: 'definicion', render: function(data) {
                return data ? (data.length > 50 ? data.substring(0, 50) + '...' : data) : '<span class="text-muted">Sin definición</span>';
            }},
            { data: 'ordens_count', name: 'ordens_count', render: function(data) {
                return '<span class="badge bg-info">' + data + ' órdenes</span>';
            }},
            { data: 'created_at', name: 'created_at' },
            { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
        ],
        language: {
            url: '{{ asset("js/datatables-es.json") }}'
        },
        responsive: true,
        pageLength: 10,
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]]
    });
});

function confirmDelete(id, name) {
    document.getElementById('deleteItemName').textContent = name;
    document.getElementById('deleteForm').action = `/admin/clases/${id}`;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>
@stop