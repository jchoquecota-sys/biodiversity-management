@extends('adminlte::page')

@section('title', 'Editar Usuario')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Editar Usuario: {{ $user->name }}</h1>
        <a href="{{ route('admin.configuration.users.index') }}" class="btn btn-secondary">
             <i class="fas fa-arrow-left"></i> Volver
         </a>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.configuration.users.update', $user) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="name">Nombre</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                           id="name" name="name" value="{{ old('name', $user->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">Correo Electrónico</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                           id="email" name="email" value="{{ old('email', $user->email) }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">Nueva Contraseña</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                           id="password" name="password">
                    <small class="form-text text-muted">Dejar en blanco para mantener la contraseña actual</small>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Confirmar Nueva Contraseña</label>
                    <input type="password" class="form-control" 
                           id="password_confirmation" name="password_confirmation">
                </div>

                <div class="form-group">
                    <label>Roles</label>
                    <div class="row">
                        @foreach($roles as $role)
                            <div class="col-md-4">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" 
                                           id="role_{{ $role->id }}" name="roles[]" 
                                           value="{{ $role->name }}" 
                                           {{ in_array($role->name, old('roles', $user->roles->pluck('name')->toArray())) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="role_{{ $role->id }}">{{ $role->name }}</label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @error('roles')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Actualizar Usuario
                    </button>
                </div>
            </form>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script>
        // Validación de contraseña en tiempo real
        const passwordInput = document.getElementById('password');
        const confirmationInput = document.getElementById('password_confirmation');

        confirmationInput.addEventListener('input', function() {
            if (passwordInput.value) {
                if (passwordInput.value !== this.value) {
                    this.setCustomValidity('Las contraseñas no coinciden');
                } else {
                    this.setCustomValidity('');
                }
            } else {
                this.setCustomValidity('');
            }
        });

        passwordInput.addEventListener('input', function() {
            if (this.value) {
                confirmationInput.required = true;
            } else {
                confirmationInput.required = false;
                confirmationInput.setCustomValidity('');
            }
        });
    </script>
@stop