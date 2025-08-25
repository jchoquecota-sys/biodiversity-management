@extends('adminlte::page')

@section('title', 'Reinos')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Reinos</h1>
        <a href="{{ route('admin.reinos.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nuevo Reino
        </a>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Lista de Reinos</h3>
            <div class="card-tools">
                <a href="{{ route('admin.reinos.export') }}" class="btn btn-success btn-sm">
                    <i class="fas fa-file-excel"></i> Exportar
                </a>
            </div>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    {{ session('error') }}
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Definición</th>
                            <th>Clases</th>
                            <th>Fecha de Creación</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reinos as $reino)
                            <tr>
                                <td>{{ $reino->id }}</td>
                                <td>{{ $reino->nombre }}</td>
                                <td>{{ Str::limit($reino->definicion, 100) }}</td>
                                <td>
                                    <span class="badge badge-info">{{ $reino->clases_count }} clases</span>
                                </td>
                                <td>{{ $reino->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.reinos.show', $reino) }}" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.reinos.edit', $reino) }}" class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.reinos.destroy', $reino) }}" method="POST" style="display: inline;" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este reino?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No hay reinos registrados</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($reinos->hasPages())
            <div class="card-footer">
                {{ $reinos->links() }}
            </div>
        @endif
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop