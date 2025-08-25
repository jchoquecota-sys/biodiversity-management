@extends('adminlte::page')

@section('title', 'Detalles del Reino')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Detalles del Reino</h1>
        <div>
            <a href="{{ route('admin.reinos.edit', $reino) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Editar
            </a>
            <a href="{{ route('admin.reinos.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Información General</h3>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="30%">ID:</th>
                            <td>{{ $reino->id }}</td>
                        </tr>
                        <tr>
                            <th>Nombre:</th>
                            <td><strong>{{ $reino->nombre }}</strong></td>
                        </tr>
                        <tr>
                            <th>Definición:</th>
                            <td>{{ $reino->definicion ?? 'Sin definición' }}</td>
                        </tr>
                        <tr>
                            <th>Fecha de Creación:</th>
                            <td>{{ $reino->created_at->format('d/m/Y H:i:s') }}</td>
                        </tr>
                        <tr>
                            <th>Última Actualización:</th>
                            <td>{{ $reino->updated_at->format('d/m/Y H:i:s') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Estadísticas</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="info-box">
                                <span class="info-box-icon bg-info"><i class="fas fa-layer-group"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Clases</span>
                                    <span class="info-box-number">{{ $reino->clases->count() }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="info-box">
                                <span class="info-box-icon bg-success"><i class="fas fa-project-diagram"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Órdenes</span>
                                    <span class="info-box-number">{{ $reino->clases->sum(function($clase) { return $clase->ordens->count(); }) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="info-box">
                                <span class="info-box-icon bg-warning"><i class="fas fa-users"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Familias</span>
                                    <span class="info-box-number">{{ $reino->getAllFamilias()->count() }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($reino->clases->count() > 0)
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Clases Asociadas</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Definición</th>
                                <th>Órdenes</th>
                                <th>Familias</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reino->clases as $clase)
                                <tr>
                                    <td>{{ $clase->idclase }}</td>
                                    <td>{{ $clase->nombre }}</td>
                                    <td>{{ Str::limit($clase->definicion, 50) }}</td>
                                    <td>
                                        <span class="badge badge-info">{{ $clase->ordens->count() }}</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-success">{{ $clase->ordens->sum(function($orden) { return $orden->familias->count(); }) }}</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.clases.show', $clase->idclase) }}" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script> console.log('Reino details loaded'); </script>
@stop