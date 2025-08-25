@extends('adminlte::page')

@section('title', 'Categorías de Biodiversidad')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1>Categorías de Biodiversidad</h1>
        <div>
            <a href="{{ route('admin.biodiversity.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Nueva Categoría
            </a>
            <a href="{{ route('admin.biodiversity.export') }}" class="btn btn-success">
                <i class="fas fa-file-excel"></i> Exportar
            </a>
            <a href="{{ route('admin.biodiversity.trashed') }}" class="btn btn-warning">
                <i class="fas fa-trash-restore"></i> Papelera
            </a>
        </div>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <table class="table table-bordered table-striped" id="biodiversity-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Imagen</th>
                        <th>Nombre</th>
                        <th>Nombre Científico</th>
                        <th>Jerarquía Taxonómica</th>
                        <th>Reino</th>
                        <th>Estado de Conservación</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <!-- Modal para visualizar imagen en tamaño completo -->
    <div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel">Imagen de Biodiversidad</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <img id="modalImage" src="" alt="" class="img-fluid" style="max-height: 500px;">
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
    <style>
        .cursor-pointer {
            cursor: pointer;
        }
        .img-thumbnail:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        #biodiversity-table td {
            vertical-align: middle;
        }
    </style>
@stop

@section('js')
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
    <script>
        // Función para mostrar el modal de imagen
        function showImageModal(imageUrl, imageName) {
            $('#modalImage').attr('src', imageUrl);
            $('#modalImage').attr('alt', imageName);
            $('#imageModalLabel').text('Imagen de ' + imageName);
            $('#imageModal').modal('show');
        }

        $(document).ready(function() {
            $('#biodiversity-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route("admin.biodiversity.index") }}',
                columns: [
                    { data: 'id', name: 'id', width: '5%' },
                    { data: 'image', name: 'image', orderable: false, searchable: false, width: '10%' },
                    { data: 'name', name: 'name', width: '15%' },
                    { data: 'scientific_name', name: 'scientific_name', width: '15%' },
                    { data: 'taxonomic_hierarchy', name: 'taxonomic_hierarchy', orderable: false, width: '20%' },
                    { data: 'kingdom', name: 'kingdom', width: '10%' },
                    { data: 'conservation_status', name: 'conservation_status', width: '10%' },
                    { data: 'action', name: 'action', orderable: false, searchable: false, width: '15%' }
                ],
                language: {
                    "decimal": "",
                    "emptyTable": "No hay datos disponibles en la tabla",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ entradas",
                    "infoEmpty": "Mostrando 0 a 0 de 0 entradas",
                    "infoFiltered": "(filtrado de _MAX_ entradas totales)",
                    "infoPostFix": "",
                    "thousands": ",",
                    "lengthMenu": "Mostrar _MENU_ entradas",
                    "loadingRecords": "Cargando...",
                    "processing": "Procesando...",
                    "search": "Buscar:",
                    "zeroRecords": "No se encontraron registros coincidentes",
                    "paginate": {
                        "first": "Primero",
                        "last": "Último",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    },
                    "aria": {
                        "sortAscending": ": activar para ordenar la columna ascendente",
                        "sortDescending": ": activar para ordenar la columna descendente"
                    }
                }
            });
        });
    </script>
@stop