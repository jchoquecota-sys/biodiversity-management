@extends('adminlte::page')

@section('title', 'Mantenimiento de Base de Datos')

@section('content_header')
    <h1>Mantenimiento de Base de Datos</h1>
@stop

@section('content')
    <div class="row">
        <!-- Backup -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Respaldo de Base de Datos</h3>
                </div>
                <div class="card-body">
                    <p>Crear una copia de seguridad de todos los datos actuales.</p>
                    <form action="{{ route('admin.database.backup') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-download"></i> Crear Respaldo
                        </button>
                    </form>
                </div>
            </div>

            <!-- Restore -->
            <div class="card mt-4">
                <div class="card-header">
                    <h3 class="card-title">Restaurar Base de Datos</h3>
                </div>
                <div class="card-body">
                    <p>Restaurar la base de datos desde un archivo de respaldo.</p>
                    <form action="{{ route('admin.database.restore') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="backup_file" name="backup_file" required>
                                <label class="custom-file-label" for="backup_file">Seleccionar archivo</label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-warning" 
                                onclick="return confirm('¿Está seguro de restaurar la base de datos? Esta acción reemplazará todos los datos actuales.')">
                            <i class="fas fa-upload"></i> Restaurar Respaldo
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <!-- Optimize -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Optimizar Base de Datos</h3>
                </div>
                <div class="card-body">
                    <p>Optimizar las tablas de la base de datos para mejorar el rendimiento.</p>
                    <form id="optimizeForm" action="{{ route('admin.database.optimize') }}" method="POST" enctype="application/x-www-form-urlencoded">
                        @csrf
                        @method('POST')
                        <div class="form-group">
                            <label>Seleccionar Tablas</label>
                            <div class="row">
                                @foreach($tableNames as $table)
                                    <div class="col-md-6">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" 
                                                   id="table_{{ $loop->index }}" name="tables[]" 
                                                   value="{{ $table }}">
                                            <label class="custom-control-label" 
                                                   for="table_{{ $loop->index }}">{{ $table }}</label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-database"></i> Optimizar Tablas
                        </button>
                    </form>
                </div>
            </div>

            <!-- Clear Cache -->
            <div class="card mt-4">
                <div class="card-header">
                    <h3 class="card-title">Limpiar Caché</h3>
                </div>
                <div class="card-body">
                    <p>Limpiar diferentes tipos de caché del sistema.</p>
                    <form action="{{ route('admin.database.clear-cache') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="cache_application" name="cache_types[]" value="application">
                                <label class="custom-control-label" for="cache_application">Caché de Aplicación</label>
                            </div>
                            <div class="custom-control custom-checkbox mt-2">
                                <input type="checkbox" class="custom-control-input" id="cache_views" name="cache_types[]" value="views">
                                <label class="custom-control-label" for="cache_views">Caché de Vistas</label>
                            </div>
                            <div class="custom-control custom-checkbox mt-2">
                                <input type="checkbox" class="custom-control-input" id="cache_routes" name="cache_types[]" value="routes">
                                <label class="custom-control-label" for="cache_routes">Caché de Rutas</label>
                            </div>
                            <div class="custom-control custom-checkbox mt-2">
                                <input type="checkbox" class="custom-control-input" id="cache_config" name="cache_types[]" value="config">
                                <label class="custom-control-label" for="cache_config">Caché de Configuración</label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-info">
                            <i class="fas fa-broom"></i> Limpiar Caché
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script>
        // Actualizar label del input file
        document.querySelector('.custom-file-input').addEventListener('change', function(e) {
            var fileName = e.target.files[0].name;
            var nextSibling = e.target.nextElementSibling;
            nextSibling.innerText = fileName;
        });

        // Seleccionar/Deseleccionar todas las tablas
        function toggleAllTables(checked) {
            document.querySelectorAll('input[name="tables[]"]').forEach(checkbox => {
                checkbox.checked = checked;
            });
        }

        // Form validation and submission
        document.addEventListener('DOMContentLoaded', function() {
            const optimizeForm = document.getElementById('optimizeForm');
            if (optimizeForm) {
                optimizeForm.addEventListener('submit', function(e) {
                    const selectedTables = this.querySelectorAll('input[name="tables[]"]:checked');
                    if (selectedTables.length === 0) {
                        e.preventDefault();
                        Swal.fire({
                            icon: 'warning',
                            title: 'Atención',
                            text: 'Por favor selecciona al menos una tabla para optimizar.'
                        });
                        return false;
                    }
                    
                    // Show loading message
                    Swal.fire({
                        title: 'Optimizando...',
                        text: 'Por favor espera mientras se optimizan las tablas.',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                });
            }
        });

        // Mostrar mensajes de éxito/error
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: '{{ session("success") }}',
                showConfirmButton: false,
                timer: 1500
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '{{ session("error") }}'
            });
        @endif
    </script>
@stop