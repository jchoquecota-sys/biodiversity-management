@extends('adminlte::page')

@section('title', 'Reporte de Biodiversidad')

@section('content_header')
    <h1>Reporte de Biodiversidad</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Lista de Especies</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-primary" onclick="window.location.href='{{ route("admin.reports.biodiversity.pdf") }}'">
                    <i class="fas fa-file-pdf"></i> Generar PDF
                </button>
            </div>
        </div>
        <div class="card-body">
            <table id="biodiversity-table" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Nombre Científico</th>
                        <th>Nombre Común</th>
                        <th>Reino</th>
                        <th>Familia</th>
                        <th>Estado de Conservación</th>
                        <th>Hábitat</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
@stop

@section('js')
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#biodiversity-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route("admin.reports.biodiversity.data") }}',
                columns: [
                    {data: 'scientific_name', name: 'scientific_name'},
                    {data: 'common_name', name: 'common_name'},
                    {data: 'kingdom', name: 'kingdom'},
                    {data: 'family', name: 'family'},
                    {data: 'conservation_status', name: 'conservation_status'},
                    {data: 'habitat', name: 'habitat'}
                ],
                language: {
                    url: '{{ asset("js/datatables-es.json") }}'
                }
            });
        });
    </script>
@stop