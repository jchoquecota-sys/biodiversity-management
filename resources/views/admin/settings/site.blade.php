@extends('adminlte::page')

@section('title', 'Configuración de Menús y Logo')

@section('content_header')
    <h1>Configuración de Menús y Logo</h1>
@stop

@section('content')
    <div class="row">
        <!-- Configuración del Logo -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Logo del Sitio</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.settings.logo.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="form-group">
                            <label>Logo Actual</label>
                            <div class="mb-3">
                                @php
                                    $logoPath = \App\Models\Setting::get('site_logo', 'logos/default-logo.svg');
                                    $logoAlt = \App\Models\Setting::get('site_logo_alt', 'Biodiversidad');
                                @endphp
                                <img src="{{ asset('storage/' . $logoPath) }}" alt="{{ $logoAlt }}" class="img-fluid" style="max-height: 100px;">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="site_logo">Nuevo Logo</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input @error('site_logo') is-invalid @enderror" id="site_logo" name="site_logo">
                                    <label class="custom-file-label" for="site_logo">Seleccionar archivo</label>
                                </div>
                            </div>
                            @error('site_logo')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Formatos permitidos: JPG, PNG, GIF, SVG. Tamaño máximo: 2MB.</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="site_logo_alt">Texto Alternativo</label>
                            <input type="text" class="form-control @error('site_logo_alt') is-invalid @enderror" id="site_logo_alt" name="site_logo_alt" value="{{ old('site_logo_alt', $logoAlt) }}">
                            @error('site_logo_alt')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Texto que se mostrará si la imagen no puede cargarse.</small>
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Actualizar Logo</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Configuración de Menús -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Menú Principal</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.settings.menus.update') }}" method="POST" id="menuForm">
                        @csrf
                        
                        <div class="menu-items-container">
                            @php
                                $menuItems = json_decode(\App\Models\Setting::get('main_menu', '[]'), true) ?: [];
                                // Ordenar por orden
                                usort($menuItems, function($a, $b) {
                                    return $a['order'] <=> $b['order'];
                                });
                            @endphp
                            
                            @foreach($menuItems as $index => $item)
                                <div class="card menu-item mb-3">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0">Elemento de Menú #{{ $index + 1 }}</h5>
                                        <button type="button" class="btn btn-sm btn-danger remove-menu-item">Eliminar</button>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label>Texto</label>
                                            <input type="text" class="form-control" name="menu_items[{{ $index }}][text]" value="{{ $item['text'] }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label>URL</label>
                                            <input type="text" class="form-control" name="menu_items[{{ $index }}][url]" value="{{ $item['url'] }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Orden</label>
                                            <input type="number" class="form-control" name="menu_items[{{ $index }}][order]" value="{{ $item['order'] }}" required>
                                        </div>
                                        <div class="form-group">
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input" id="is_active_{{ $index }}" name="menu_items[{{ $index }}][is_active]" value="1" {{ $item['is_active'] ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="is_active_{{ $index }}">Activo</label>
                                            </div>
                                        </div>
                                        <input type="hidden" name="menu_items[{{ $index }}][parent_id]" value="{{ $item['parent_id'] }}">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="form-group">
                            <button type="button" class="btn btn-success" id="addMenuItem">Agregar Elemento</button>
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Guardar Menú</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Botón para inicializar configuraciones por defecto -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.settings.initialize') }}" method="POST">
                        @csrf
                        <p class="text-warning">Esta acción restablecerá todas las configuraciones de menú y logo a sus valores predeterminados.</p>
                        <button type="submit" class="btn btn-warning" onclick="return confirm('¿Está seguro de restablecer todas las configuraciones a sus valores predeterminados?');">Inicializar Configuraciones Predeterminadas</button>
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
        $(document).ready(function() {
            // Inicializar BS Custom File Input
            bsCustomFileInput.init();
            
            // Contador para nuevos elementos de menú
            let menuItemCount = {{ count($menuItems) }};
            
            // Agregar nuevo elemento de menú
            $('#addMenuItem').click(function() {
                const newItem = `
                    <div class="card menu-item mb-3">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Nuevo Elemento de Menú</h5>
                            <button type="button" class="btn btn-sm btn-danger remove-menu-item">Eliminar</button>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label>Texto</label>
                                <input type="text" class="form-control" name="menu_items[${menuItemCount}][text]" required>
                            </div>
                            <div class="form-group">
                                <label>URL</label>
                                <input type="text" class="form-control" name="menu_items[${menuItemCount}][url]" required>
                            </div>
                            <div class="form-group">
                                <label>Orden</label>
                                <input type="number" class="form-control" name="menu_items[${menuItemCount}][order]" value="${menuItemCount + 1}" required>
                            </div>
                            <div class="form-group">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="is_active_${menuItemCount}" name="menu_items[${menuItemCount}][is_active]" value="1" checked>
                                    <label class="custom-control-label" for="is_active_${menuItemCount}">Activo</label>
                                </div>
                            </div>
                            <input type="hidden" name="menu_items[${menuItemCount}][parent_id]" value="">
                        </div>
                    </div>
                `;
                
                $('.menu-items-container').append(newItem);
                menuItemCount++;
            });
            
            // Eliminar elemento de menú
            $(document).on('click', '.remove-menu-item', function() {
                $(this).closest('.menu-item').remove();
                
                // Reindexar los elementos del menú
                $('.menu-item').each(function(index) {
                    $(this).find('input, select').each(function() {
                        const name = $(this).attr('name');
                        if (name) {
                            const newName = name.replace(/menu_items\[\d+\]/, `menu_items[${index}]`);
                            $(this).attr('name', newName);
                        }
                        
                        const id = $(this).attr('id');
                        if (id && id.startsWith('is_active_')) {
                            $(this).attr('id', `is_active_${index}`);
                            $(this).next('label').attr('for', `is_active_${index}`);
                        }
                    });
                });
            });
            
            // Validar formulario antes de enviar
            $('#menuForm').submit(function() {
                // Verificar si hay elementos de menú
                if ($('.menu-item').length === 0) {
                    alert('Debe agregar al menos un elemento al menú');
                    return false;
                }
                return true;
            });
        });
    </script>
@stop