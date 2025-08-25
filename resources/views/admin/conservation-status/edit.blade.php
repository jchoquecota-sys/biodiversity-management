@extends('adminlte::page')

@section('title', 'Editar Estado de Conservación')

@section('content_header')
    <h1>Editar Estado de Conservación: {{ $conservationStatus->name }}</h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-edit me-2"></i>
                        Editar Estado de Conservación: {{ $conservationStatus->name }}
                    </h3>
                </div>
                
                <form action="{{ route('admin.conservation-status.update', $conservationStatus) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="card-body">
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <h6><i class="fas fa-exclamation-circle me-2"></i>Por favor corrige los siguientes errores:</h6>
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="code" class="form-label">
                                        <i class="fas fa-tag me-1"></i>
                                        Código <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('code') is-invalid @enderror" 
                                           id="code" 
                                           name="code" 
                                           value="{{ old('code', $conservationStatus->code) }}" 
                                           maxlength="2"
                                           placeholder="Ej: CR, EN, VU"
                                           required>
                                    <div class="form-text">Código de 2 caracteres (según estándares IUCN)</div>
                                    @error('code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">
                                        <i class="fas fa-signature me-1"></i>
                                        Nombre <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name', $conservationStatus->name) }}" 
                                           placeholder="Ej: En Peligro Crítico"
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="description" class="form-label">
                                        <i class="fas fa-align-left me-1"></i>
                                        Descripción
                                    </label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" 
                                              name="description" 
                                              rows="4"
                                              placeholder="Descripción detallada del estado de conservación...">{{ old('description', $conservationStatus->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="color" class="form-label">
                                        <i class="fas fa-palette me-1"></i>
                                        Color
                                    </label>
                                    <div class="input-group">
                                        <div class="input-group-text p-1">
                                            <div id="colorPreview" class="color-preview" style="width: 30px; height: 30px; background-color: {{ old('color', $conservationStatus->color ?? '#6c757d') }}; border: 1px solid #dee2e6; border-radius: 3px;"></div>
                                        </div>
                                        <input type="color" 
                                               class="form-control form-control-color @error('color') is-invalid @enderror" 
                                               id="color" 
                                               name="color" 
                                               value="{{ old('color', $conservationStatus->color ?? '#6c757d') }}" 
                                               title="Seleccionar color">
                                        <input type="text" 
                                               class="form-control" 
                                               id="colorHex" 
                                               value="{{ old('color', $conservationStatus->color ?? '#6c757d') }}" 
                                               readonly>
                                    </div>
                                    <div class="form-text">Color para identificar visualmente el estado</div>
                                    @error('color')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               id="is_active" 
                                               name="is_active" 
                                               value="1"
                                               {{ old('is_active', $conservationStatus->is_active) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            <i class="fas fa-toggle-on me-1"></i>
                                            Estado Activo
                                        </label>
                                    </div>
                                    <div class="form-text">Los estados inactivos no aparecerán en los formularios</div>
                                </div>
                            </div>
                        </div>

                        <!-- Preview -->
                        <div class="row">
                            <div class="col-12">
                                <div class="card bg-light">
                                    <div class="card-header">
                                        <h6 class="mb-0">
                                            <i class="fas fa-eye me-1"></i>
                                            Vista Previa
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <span class="badge me-3" id="preview-badge" style="background-color: {{ $conservationStatus->color ?? '#6c757d' }}; color: white;">
                                                <span id="preview-code">{{ $conservationStatus->code }}</span>
                                            </span>
                                            <div>
                                                <strong id="preview-name">{{ $conservationStatus->name }}</strong>
                                                <div class="text-muted small" id="preview-description">{{ $conservationStatus->description ?? 'Sin descripción' }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Usage Information -->
                        @if($conservationStatus->biodiversityCategories()->count() > 0)
                            <div class="row mt-3">
                                <div class="col-12">
                                    <div class="alert alert-info">
                                        <h6><i class="fas fa-info-circle me-2"></i>Información de Uso</h6>
                                        <p class="mb-0">
                                            Este estado de conservación está siendo utilizado por 
                                            <strong>{{ $conservationStatus->biodiversityCategories()->count() }}</strong> 
                                            {{ $conservationStatus->biodiversityCategories()->count() === 1 ? 'categoría de biodiversidad' : 'categorías de biodiversidad' }}.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                    
                    <div class="card-footer">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.conservation-status.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i>
                                Volver
                            </a>
                            <div>
                                <a href="{{ route('admin.conservation-status.show', $conservationStatus) }}" class="btn btn-info me-2">
                                    <i class="fas fa-eye me-1"></i>
                                    Ver Detalles
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i>
                                    Actualizar Estado
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.form-control-color {
    width: 50px !important;
    height: 38px !important;
    padding: 0.375rem 0.5rem !important;
    border: 1px solid #ced4da !important;
    border-radius: 0.375rem !important;
    cursor: pointer !important;
}

.form-control-color::-webkit-color-swatch-wrapper {
    padding: 0 !important;
    border: none !important;
    border-radius: 0.25rem !important;
}

.form-control-color::-webkit-color-swatch {
    border: none !important;
    border-radius: 0.25rem !important;
}

.form-control-color::-moz-color-swatch {
    border: none !important;
    border-radius: 0.25rem !important;
}

.color-preview {
    cursor: pointer;
    transition: all 0.2s ease;
}

.color-preview:hover {
    transform: scale(1.1);
    box-shadow: 0 2px 4px rgba(0,0,0,0.2);
}

.input-group .form-control-color {
    border-left: 0 !important;
    border-right: 0 !important;
}

.input-group .form-control-color + .form-control {
    border-left: 0 !important;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const codeInput = document.getElementById('code');
    const nameInput = document.getElementById('name');
    const descriptionInput = document.getElementById('description');
    const colorInput = document.getElementById('color');
    const colorHexInput = document.getElementById('colorHex');
    
    const previewCode = document.getElementById('preview-code');
    const previewName = document.getElementById('preview-name');
    const previewDescription = document.getElementById('preview-description');
    const previewBadge = document.getElementById('preview-badge');
    
    function updatePreview() {
        previewCode.textContent = codeInput.value || '--';
        previewName.textContent = nameInput.value || 'Nombre del Estado';
        previewDescription.textContent = descriptionInput.value || 'Sin descripción';
        previewBadge.style.backgroundColor = colorInput.value;
    }
    
    function updateColorHex() {
        colorHexInput.value = colorInput.value;
        document.getElementById('colorPreview').style.backgroundColor = colorInput.value;
        updatePreview();
    }
    
    // Event listeners
    codeInput.addEventListener('input', updatePreview);
    nameInput.addEventListener('input', updatePreview);
    descriptionInput.addEventListener('input', updatePreview);
    colorInput.addEventListener('input', updateColorHex);
    
    // Convert code to uppercase
    codeInput.addEventListener('input', function() {
        this.value = this.value.toUpperCase();
    });
});
</script>
@endpush