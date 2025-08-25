@extends('adminlte::page')

@section('title', 'Crear Reino')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Crear Reino</h1>
        <a href="{{ route('admin.reinos.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Información del Reino</h3>
        </div>
        <form action="{{ route('admin.reinos.store') }}" method="POST">
            @csrf
            <div class="card-body">
                <div class="form-group">
                    <label for="nombre">Nombre <span class="text-danger">*</span></label>
                    <input type="text" 
                           class="form-control @error('nombre') is-invalid @enderror" 
                           id="nombre" 
                           name="nombre" 
                           value="{{ old('nombre') }}" 
                           placeholder="Ingrese el nombre del reino"
                           required>
                    @error('nombre')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="definicion">Definición</label>
                    <textarea class="form-control @error('definicion') is-invalid @enderror" 
                              id="definicion" 
                              name="definicion" 
                              rows="4" 
                              placeholder="Ingrese la definición del reino">{{ old('definicion') }}</textarea>
                    @error('definicion')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Guardar Reino
                </button>
                <a href="{{ route('admin.reinos.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script>
        // Auto-focus en el primer campo
        document.getElementById('nombre').focus();
    </script>
@stop