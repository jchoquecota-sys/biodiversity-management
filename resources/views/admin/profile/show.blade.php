@extends('adminlte::page')

@section('title', 'Mi Perfil')

@section('content_header')
    <h1>Mi Perfil</h1>
@stop

@section('content')
    <div class="row">
        <!-- Información del Perfil -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Información Personal</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="text-center mb-4">
                            <img src="{{ auth()->user()->avatar_url ?? '/img/default-avatar.png' }}" 
                                 class="profile-user-img img-fluid img-circle" 
                                 alt="Avatar de Usuario">
                            <div class="mt-2">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="avatar" name="avatar" accept="image/*">
                                    <label class="custom-file-label" for="avatar">Cambiar avatar</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="name">Nombre</label>
                            <input type="text" class="form-control" id="name" name="name" 
                                   value="{{ auth()->user()->name }}" required>
                        </div>

                        <div class="form-group">
                            <label for="email">Correo Electrónico</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="{{ auth()->user()->email }}" required>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Guardar Cambios
                        </button>
                    </form>
                </div>
            </div>

            <!-- Cambiar Contraseña -->
            <div class="card mt-4">
                <div class="card-header">
                    <h3 class="card-title">Cambiar Contraseña</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.profile.password') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="current_password">Contraseña Actual</label>
                            <input type="password" class="form-control" id="current_password" 
                                   name="current_password" required>
                        </div>

                        <div class="form-group">
                            <label for="password">Nueva Contraseña</label>
                            <input type="password" class="form-control" id="password" 
                                   name="password" required>
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation">Confirmar Nueva Contraseña</label>
                            <input type="password" class="form-control" id="password_confirmation" 
                                   name="password_confirmation" required>
                        </div>

                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-key"></i> Cambiar Contraseña
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <!-- Manual de Usuario -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Manual de Usuario</h3>
                </div>
                <div class="card-body">
                    <p>Acceda al manual de usuario para obtener información detallada sobre el uso del sistema.</p>
                    <a href="{{ route('admin.profile.manual') }}" class="btn btn-info">
                        <i class="fas fa-book"></i> Ver Manual
                    </a>
                </div>
            </div>

            <!-- Contactar Soporte -->
            <div class="card mt-4">
                <div class="card-header">
                    <h3 class="card-title">Contactar Soporte</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.profile.support') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="subject">Asunto</label>
                            <input type="text" class="form-control" id="subject" name="subject" required>
                        </div>

                        <div class="form-group">
                            <label for="message">Mensaje</label>
                            <textarea class="form-control" id="message" name="message" rows="4" required></textarea>
                        </div>

                        <div class="form-group">
                            <label>Adjuntos (opcional)</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="attachments" 
                                       name="attachments[]" multiple>
                                <label class="custom-file-label" for="attachments">Seleccionar archivos</label>
                            </div>
                            <small class="form-text text-muted">
                                Puede adjuntar hasta 3 archivos (máx. 2MB cada uno)
                            </small>
                        </div>

                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-paper-plane"></i> Enviar Mensaje
                        </button>
                    </form>
                </div>
            </div>

            <!-- Acerca del Sistema -->
            <div class="card mt-4">
                <div class="card-header">
                    <h3 class="card-title">Acerca del Sistema</h3>
                </div>
                <div class="card-body">
                    <p><strong>Versión:</strong> {{ config('app.version') }}</p>
                    <p><strong>Desarrollado por:</strong> {{ config('app.developer') }}</p>
                    <p><strong>Última actualización:</strong> {{ config('app.last_update') }}</p>
                    <p><strong>Licencia:</strong> {{ config('app.license') }}</p>
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
        // Actualizar label del input file para avatar
        document.querySelector('#avatar').addEventListener('change', function(e) {
            var fileName = e.target.files[0].name;
            var nextSibling = e.target.nextElementSibling;
            nextSibling.innerText = fileName;
        });

        // Actualizar label del input file para adjuntos
        document.querySelector('#attachments').addEventListener('change', function(e) {
            var fileCount = e.target.files.length;
            var nextSibling = e.target.nextElementSibling;
            nextSibling.innerText = fileCount > 1 ? fileCount + ' archivos seleccionados' : e.target.files[0].name;
        });

        // Validación de contraseña en tiempo real
        document.querySelector('#password').addEventListener('input', function() {
            var password = this.value;
            var confirmation = document.querySelector('#password_confirmation').value;
            var submitBtn = this.closest('form').querySelector('button[type="submit"]');
            
            if (password.length < 8) {
                this.setCustomValidity('La contraseña debe tener al menos 8 caracteres');
            } else if (confirmation && password !== confirmation) {
                this.setCustomValidity('Las contraseñas no coinciden');
            } else {
                this.setCustomValidity('');
            }
        });

        document.querySelector('#password_confirmation').addEventListener('input', function() {
            var password = document.querySelector('#password').value;
            var confirmation = this.value;
            
            if (password !== confirmation) {
                this.setCustomValidity('Las contraseñas no coinciden');
            } else {
                this.setCustomValidity('');
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