@extends('adminlte::page')

@section('title', 'Contactar Soporte')

@section('content_header')
    <h1>Contactar Soporte</h1>
@stop

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Formulario de Contacto</h3>
            </div>
            <form action="{{ route('admin.profile.send-support-message') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="form-group">
                        <label for="subject">Asunto</label>
                        <input type="text" class="form-control @error('subject') is-invalid @enderror" 
                               id="subject" name="subject" value="{{ old('subject') }}" required>
                        @error('subject')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="priority">Prioridad</label>
                        <select class="form-control @error('priority') is-invalid @enderror" 
                                id="priority" name="priority" required>
                            <option value="low">Baja</option>
                            <option value="medium">Media</option>
                            <option value="high">Alta</option>
                            <option value="urgent">Urgente</option>
                        </select>
                        @error('priority')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="message">Mensaje</label>
                        <textarea class="form-control @error('message') is-invalid @enderror" 
                                  id="message" name="message" rows="5" required>{{ old('message') }}</textarea>
                        @error('message')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="attachments">Archivos Adjuntos (opcional)</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input @error('attachments') is-invalid @enderror" 
                                   id="attachments" name="attachments[]" multiple>
                            <label class="custom-file-label" for="attachments">Seleccionar archivos</label>
                        </div>
                        <small class="form-text text-muted">
                            Puede adjuntar hasta 3 archivos. Formatos permitidos: PDF, DOC, DOCX, JPG, PNG. Tamaño máximo: 5MB por archivo.
                        </small>
                        @error('attachments')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                        @error('attachments.*')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane"></i> Enviar Mensaje
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Información de Contacto</h3>
            </div>
            <div class="card-body">
                <p><i class="fas fa-envelope mr-2"></i> soporte@ejemplo.com</p>
                <p><i class="fas fa-phone mr-2"></i> +1 234 567 890</p>
                <p><i class="fas fa-clock mr-2"></i> Lunes a Viernes, 9:00 - 18:00</p>
                <hr>
                <p class="mb-0">
                    <i class="fas fa-info-circle mr-2"></i>
                    Para problemas urgentes fuera del horario laboral, 
                    por favor use la opción de prioridad "Urgente".
                </p>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
<style>
    .custom-file-label::after {
        content: "Buscar";
    }
</style>
@stop

@section('js')
<script>
    // Actualizar label del input file con los nombres de los archivos seleccionados
    document.querySelector('.custom-file-input').addEventListener('change', function(e) {
        var files = Array.from(e.target.files).map(f => f.name);
        var label = e.target.nextElementSibling;
        label.textContent = files.join(', ') || 'Seleccionar archivos';
    });

    // Mantener el valor seleccionado de prioridad después de un error de validación
    document.addEventListener('DOMContentLoaded', function() {
        var oldPriority = '{{ old("priority") }}';
        if (oldPriority) {
            document.getElementById('priority').value = oldPriority;
        }
    });
</script>
@stop