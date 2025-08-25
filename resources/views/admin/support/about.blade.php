@extends('adminlte::page')

@section('title', 'Acerca del Sistema')

@section('content_header')
    <h1>Acerca del Sistema</h1>
@stop

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Información del Sistema</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-box">
                            <span class="info-box-icon bg-info"><i class="fas fa-code-branch"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Versión</span>
                                <span class="info-box-number">{{ config('app.version', '1.0.0') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-box">
                            <span class="info-box-icon bg-success"><i class="fas fa-calendar-alt"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Última Actualización</span>
                                <span class="info-box-number">{{ date('d/m/Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <h5><i class="fas fa-info-circle mr-2"></i>Descripción</h5>
                    <p class="text-muted">
                        Sistema de Gestión de Biodiversidad y Publicaciones Científicas es una plataforma integral 
                        diseñada para facilitar el registro, seguimiento y análisis de especies biológicas y 
                        publicaciones científicas relacionadas.
                    </p>
                </div>

                <div class="mt-4">
                    <h5><i class="fas fa-cogs mr-2"></i>Características Principales</h5>
                    <ul class="text-muted">
                        <li>Gestión completa de registros de biodiversidad</li>
                        <li>Catálogo de publicaciones científicas</li>
                        <li>Sistema de reportes y estadísticas</li>
                        <li>Herramientas de mantenimiento de base de datos</li>
                        <li>Panel de administración intuitivo</li>
                        <li>Gestión de usuarios y roles</li>
                    </ul>
                </div>

                <div class="mt-4">
                    <h5><i class="fas fa-laptop-code mr-2"></i>Tecnologías Utilizadas</h5>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="small-box bg-gradient-primary">
                                <div class="inner">
                                    <h4>Laravel</h4>
                                    <p>Framework PHP</p>
                                </div>
                                <div class="icon">
                                    <i class="fab fa-laravel"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="small-box bg-gradient-danger">
                                <div class="inner">
                                    <h4>MySQL</h4>
                                    <p>Base de Datos</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-database"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="small-box bg-gradient-warning">
                                <div class="inner">
                                    <h4>AdminLTE</h4>
                                    <p>Panel Admin</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-desktop"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Información del Desarrollador</h3>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <img src="{{ asset('img/developer-logo.png') }}" 
                         alt="Logo Desarrollador" 
                         class="img-fluid mb-3" 
                         style="max-width: 200px;">
                </div>

                <div class="developer-info">
                    <p>
                        <i class="fas fa-building mr-2"></i>
                        <strong>Empresa:</strong> Nombre de la Empresa
                    </p>
                    <p>
                        <i class="fas fa-globe mr-2"></i>
                        <strong>Sitio Web:</strong>
                        <a href="https://www.ejemplo.com" target="_blank">www.ejemplo.com</a>
                    </p>
                    <p>
                        <i class="fas fa-envelope mr-2"></i>
                        <strong>Email:</strong>
                        <a href="mailto:contacto@ejemplo.com">contacto@ejemplo.com</a>
                    </p>
                    <p>
                        <i class="fas fa-phone mr-2"></i>
                        <strong>Teléfono:</strong> +1 234 567 890
                    </p>
                </div>

                <hr>

                <div class="license-info mt-4">
                    <h5><i class="fas fa-file-contract mr-2"></i>Licencia</h5>
                    <p class="text-muted">
                        Este software está licenciado bajo los términos de la licencia MIT. 
                        Todos los derechos reservados © {{ date('Y') }}
                    </p>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Enlaces Útiles</h3>
            </div>
            <div class="card-body p-0">
                <div class="list-group">
                    <a href="{{ route('admin.profile.manual') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-book mr-2"></i> Manual de Usuario
                    </a>
                    <a href="{{ route('admin.profile.contact') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-headset mr-2"></i> Soporte Técnico
                    </a>
                    <a href="https://github.com/ejemplo/repo" target="_blank" class="list-group-item list-group-item-action">
                        <i class="fab fa-github mr-2"></i> Repositorio GitHub
                    </a>
                    <a href="https://ejemplo.com/docs" target="_blank" class="list-group-item list-group-item-action">
                        <i class="fas fa-file-alt mr-2"></i> Documentación Técnica
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
<style>
    .developer-info p {
        margin-bottom: 1rem;
    }
    .small-box .icon {
        font-size: 50px;
        right: 15px;
        top: 15px;
    }
    .small-box .inner {
        padding: 20px;
    }
    .small-box .inner h4 {
        font-size: 1.5rem;
        margin: 0;
    }
    .small-box .inner p {
        margin: 0;
    }
</style>
@stop