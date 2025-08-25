@extends('adminlte::page')

@section('title', 'Gestión de Roles')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Gestión de Roles y Permisos</h1>
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createRoleModal">
            <i class="fas fa-plus"></i> Crear Rol
        </button>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Permisos</th>
                        <th>Usuarios</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($roles as $role)
                        <tr>
                            <td>{{ $role->name }}</td>
                            <td>
                                @foreach($role->permissions as $permission)
                                    <span class="badge badge-info">{{ $permission->name }}</span>
                                @endforeach
                            </td>
                            <td>{{ $role->users_count }}</td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-warning" 
                                            onclick="editRole('{{ $role->id }}', '{{ $role->name }}')" 
                                            title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    @if(!in_array($role->name, ['admin', 'user']))
                                        <form action="{{ route('admin.configuration.roles.delete', $role) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" 
                                                    onclick="return confirm('¿Está seguro de eliminar este rol?')" 
                                                    title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Crear Rol -->
    <div class="modal fade" id="createRoleModal" tabindex="-1" role="dialog" aria-labelledby="createRoleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('admin.configuration.roles.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="createRoleModalLabel">Crear Nuevo Rol</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Nombre del Rol</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label>Permisos</label>
                            <div class="row">
                                @foreach($permissions as $permission)
                                    <div class="col-md-4">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" 
                                                   id="permission_{{ $permission->id }}" 
                                                   name="permissions[]" value="{{ $permission->name }}">
                                            <label class="custom-control-label" 
                                                   for="permission_{{ $permission->id }}">{{ $permission->name }}</label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Crear Rol</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Editar Rol -->
    <div class="modal fade" id="editRoleModal" tabindex="-1" role="dialog" aria-labelledby="editRoleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="editRoleForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="editRoleModalLabel">Editar Rol</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="edit_name">Nombre del Rol</label>
                            <input type="text" class="form-control" id="edit_name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label>Permisos</label>
                            <div class="row">
                                @foreach($permissions as $permission)
                                    <div class="col-md-4">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input edit-permission" 
                                                   id="edit_permission_{{ $permission->id }}" 
                                                   name="permissions[]" value="{{ $permission->name }}">
                                            <label class="custom-control-label" 
                                                   for="edit_permission_{{ $permission->id }}">{{ $permission->name }}</label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Actualizar Rol</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script>
        function editRole(roleId, roleName) {
            // Limpiar checkboxes
            document.querySelectorAll('.edit-permission').forEach(checkbox => {
                checkbox.checked = false;
            });

            // Establecer nombre y ruta del formulario
            document.getElementById('edit_name').value = roleName;
            document.getElementById('editRoleForm').action = `/admin/configuration/roles/${roleId}`;

            // Obtener y marcar permisos actuales
            fetch(`/admin/configuration/roles/${roleId}/permissions`)
                .then(response => response.json())
                .then(permissions => {
                    permissions.forEach(permission => {
                        const checkbox = document.querySelector(`input[value="${permission}"]`);
                        if (checkbox) checkbox.checked = true;
                    });
                });

            // Mostrar modal
            $('#editRoleModal').modal('show');
        }

        // Mostrar mensajes de éxito
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: '{{ session("success") }}',
                showConfirmButton: false,
                timer: 1500
            });
        @endif
    </script>
@stop