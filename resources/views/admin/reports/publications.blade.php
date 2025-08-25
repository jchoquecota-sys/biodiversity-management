@extends('adminlte::page')

@section('title', 'Reporte de Publicaciones')

@section('content_header')
    <h1>Reporte de Publicaciones</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Lista de Publicaciones</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-primary" onclick="window.location.href='{{ route("admin.reports.publications.pdf") }}'">
                    <i class="fas fa-file-pdf"></i> Generar PDF
                </button>
            </div>
        </div>
        <div class="card-body">
            <table id="publications-table" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Título</th>
                        <th>Año</th>
                        <th>Revista</th>
                        <th>DOI</th>
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
            $('#publications-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route("admin.reports.publications.data") }}',
                columns: [
                    {data: 'title', name: 'title'},
                    {data: 'publication_year', name: 'publication_year'},
                    {data: 'journal', name: 'journal'},
                    {data: 'doi', name: 'doi'}
                ],
                language: {
                    url: '{{ asset("js/datatables-es.json") }}'
                }
            });
        });
    </script>
@stop