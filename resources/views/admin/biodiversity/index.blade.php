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
        .image-gallery {
            max-width: 150px;
        }
        .image-gallery img {
            margin: 2px;
        }
        .image-gallery img:hover {
            z-index: 999;
            position: relative;
        }
        
        /* Mejoras de alineación para la tabla */
        #biodiversity-table {
            table-layout: fixed;
        }
        
        #biodiversity-table th,
        #biodiversity-table td {
            vertical-align: middle;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }
        
        #biodiversity-table th:nth-child(1),
        #biodiversity-table td:nth-child(1) {
            text-align: center;
            font-weight: bold;
        }
        
        #biodiversity-table th:nth-child(2),
        #biodiversity-table td:nth-child(2) {
            text-align: center;
        }
        
        #biodiversity-table th:nth-child(6),
        #biodiversity-table td:nth-child(6),
        #biodiversity-table th:nth-child(7),
        #biodiversity-table td:nth-child(7),
        #biodiversity-table th:nth-child(8),
        #biodiversity-table td:nth-child(8) {
            text-align: center;
        }
        
        /* Estilos para nombres científicos */
        .scientific-name {
            font-style: italic;
            color: #6c757d;
        }
        
        /* Estilos para jerarquía taxonómica */
        .taxonomic-hierarchy {
            font-size: 0.85em;
            line-height: 1.2;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            #biodiversity-table th,
            #biodiversity-table td {
                font-size: 0.85em;
                padding: 0.5rem 0.25rem;
            }
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
                responsive: true,
                autoWidth: false,
                columnDefs: [
                    { targets: 0, width: '5%', className: 'text-center' },
                    { targets: 1, width: '12%', className: 'text-center', orderable: false, searchable: false },
                    { targets: 2, width: '18%' },
                    { targets: 3, width: '18%' },
                    { targets: 4, width: '20%', orderable: false },
                    { targets: 5, width: '8%', className: 'text-center' },
                    { targets: 6, width: '10%', className: 'text-center' },
                    { targets: 7, width: '9%', className: 'text-center', orderable: false, searchable: false }
                ],
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'image', name: 'image' },
                    { data: 'name', name: 'name' },
                    { data: 'scientific_name', name: 'scientific_name' },
                    { data: 'taxonomic_hierarchy', name: 'taxonomic_hierarchy' },
                    { data: 'kingdom', name: 'kingdom' },
                    { data: 'conservation_status', name: 'conservation_status' },
                    { data: 'action', name: 'action' }
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