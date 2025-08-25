@extends('adminlte::page')

@section('title', 'Gestión de Contenido del Home')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Gestión de Contenido del Home</h1>
        <a href="{{ route('admin.home-content.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Agregar Contenido
        </a>
    </div>
@stop

@section('content')
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row">
        @foreach($content as $sectionKey => $section)
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ $section['title'] }}</h3>
                    </div>
                    <div class="card-body">
                        @if($section['items']->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Clave</th>
                                            <th>Tipo</th>
                                            <th>Estado</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($section['items'] as $item)
                                            <tr>
                                                <td>{{ $item->key }}</td>
                                                <td>
                                                    <span class="badge badge-{{ $item->type === 'text' ? 'info' : ($item->type === 'image' ? 'warning' : 'success') }}">
                                                        {{ ucfirst($item->type) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge badge-{{ $item->is_active ? 'success' : 'secondary' }}">
                                                        {{ $item->is_active ? 'Activo' : 'Inactivo' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="btn-group btn-group-sm" role="group">
                                                        <a href="{{ route('admin.home-content.edit', $item) }}" class="btn btn-outline-primary btn-sm">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <form action="{{ route('admin.home-content.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de eliminar este contenido?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-muted">No hay contenido configurado para esta sección.</p>
                            <a href="{{ route('admin.home-content.create') }}?section={{ $sectionKey }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-plus"></i> Agregar contenido
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@stop

@section('css')
    <style>
        .card {
            box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
        }
        .table th {
            border-top: none;
            font-weight: 600;
        }
    </style>
@stop

@section('js')
    <script>
        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 5000);
    </script>
@stop