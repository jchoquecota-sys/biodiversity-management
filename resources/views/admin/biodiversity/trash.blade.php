@extends('adminlte::page')

@section('title', 'Categorías de Biodiversidad Eliminadas')

@section('content_header')
    <h1>Categorías de Biodiversidad Eliminadas</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="float-right">
                <a href="{{ route('admin.biodiversity.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <table id="biodiversity-table" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Nombre Científico</th>
                        <th>Reino</th>
                        <th>Estado de Conservación</th>
                        <th>Eliminado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($trashedBiodiversity as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->name }}</td>
                            <td><em>{{ $item->scientific_name }}</em></td>
                            <td>{{ $kingdoms[$item->kingdom] ?? $item->kingdom }}</td>
                            <td>
                                @php
                                    $statusColors = [
                                        'EX' => 'danger',
                                        'EW' => 'danger',
                                        'CR' => 'danger',
                                        'EN' => 'warning',
                                        'VU' => 'warning',
                                        'NT' => 'info',
                                        'LC' => 'success',
                                        'DD' => 'secondary',
                                        'NE' => 'secondary',
                                    ];
                                    $statusColor = $statusColors[$item->conservation_status] ?? 'secondary';
                                @endphp
                                <span class="badge badge-{{ $statusColor }}">
                                    {{ $conservationStatuses[$item->conservation_status] ?? $item->conservation_status }}
                                </span>
                            </td>
                            <td>{{ $item->deleted_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <form action="{{ route('admin.biodiversity.restore', $item->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('¿Está seguro de que desea restaurar este elemento?')">
                                        <i class="fas fa-trash-restore"></i> Restaurar
                                    </button>
                                </form>
                                <form action="{{ route('admin.biodiversity.force-delete', $item->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Está seguro de que desea eliminar permanentemente este elemento? Esta acción no se puede deshacer.')">
                                        <i class="fas fa-trash-alt"></i> Eliminar Permanentemente
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap4.min.css">
@stop

@section('js')
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#biodiversity-table').DataTable({
                responsive: true,
                autoWidth: false,
                language: {
                    url: '{{ asset("js/datatables-es.json") }}'
                }
            });
        });
    </script>
@stop