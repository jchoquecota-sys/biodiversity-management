<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class ConfigurationController extends Controller
{
    /**
     * Muestra la configuración general del sistema
     */
    public function general()
    {
        $settings = [
            'site_name' => config('app.name'),
            'site_description' => config('app.description'),
            'contact_email' => config('mail.from.address'),
            'items_per_page' => config('app.pagination.per_page'),
            'maintenance_mode' => app()->isDownForMaintenance(),
        ];

        return view('admin.configuration.general', compact('settings'));
    }

    /**
     * Actualiza la configuración general
     */
    public function updateGeneral(Request $request)
    {
        $validated = $request->validate([
            'site_name' => 'required|string|max:255',
            'site_description' => 'required|string',
            'contact_email' => 'required|email',
            'items_per_page' => 'required|integer|min:5|max:100',
            'maintenance_mode' => 'boolean',
        ]);

        // Actualizar el archivo .env
        $this->updateEnvironmentFile([
            'APP_NAME' => $validated['site_name'],
            'APP_DESCRIPTION' => $validated['site_description'],
            'MAIL_FROM_ADDRESS' => $validated['contact_email'],
            'PAGINATION_PER_PAGE' => $validated['items_per_page'],
        ]);

        if ($validated['maintenance_mode']) {
            \Artisan::call('down');
        } else {
            \Artisan::call('up');
        }

        return redirect()->route('admin.configuration.general')
            ->with('success', 'Configuración actualizada exitosamente');
    }

    /**
     * Muestra la lista de usuarios
     */
    public function users()
    {
        $users = User::with('roles')->paginate(10);
        return view('admin.configuration.users.index', compact('users'));
    }

    /**
     * Muestra el formulario para crear un nuevo usuario
     */
    public function createUser()
    {
        $roles = Role::all();
        return view('admin.configuration.users.create', compact('roles'));
    }

    /**
     * Almacena un nuevo usuario
     */
    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'roles' => 'required|array',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $user->assignRole($validated['roles']);

        return redirect()->route('admin.configuration.users.index')
            ->with('success', 'Usuario creado exitosamente');
    }

    /**
     * Muestra el formulario para editar un usuario
     */
    public function editUser(User $user)
    {
        $roles = Role::all();
        return view('admin.configuration.users.edit', compact('user', 'roles'));
    }

    /**
     * Actualiza un usuario existente
     */
    public function updateUser(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'roles' => 'required|array',
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        if (!empty($validated['password'])) {
            $user->update(['password' => Hash::make($validated['password'])]);
        }

        $user->syncRoles($validated['roles']);

        return redirect()->route('admin.configuration.users.index')
            ->with('success', 'Usuario actualizado exitosamente');
    }

    /**
     * Elimina un usuario del sistema
     */
    public function destroyUser(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.configuration.users.index')
                ->with('error', 'No puedes eliminar tu propio usuario');
        }

        $user->delete();

        return redirect()->route('admin.configuration.users.index')
            ->with('success', 'Usuario eliminado exitosamente');
    }



    /**
     * Muestra la lista de roles y permisos
     */
    public function roles()
    {
        $roles = Role::with('permissions')->get();
        $permissions = Permission::all();
        return view('admin.configuration.roles.index', compact('roles', 'permissions'));
    }

    /**
     * Crea un nuevo rol
     */
    public function storeRole(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'permissions' => 'required|array',
        ]);

        $role = Role::create(['name' => $validated['name']]);
        $role->syncPermissions($validated['permissions']);

        return redirect()->route('admin.configuration.roles')
            ->with('success', 'Rol creado exitosamente');
    }

    /**
     * Actualiza un rol
     */
    public function updateRole(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'permissions' => 'required|array',
        ]);

        $role->update(['name' => $validated['name']]);
        $role->syncPermissions($validated['permissions']);

        return redirect()->route('admin.configuration.roles')
            ->with('success', 'Rol actualizado exitosamente');
    }

    /**
     * Elimina un rol
     */
    public function deleteRole(Role $role)
    {
        $role->delete();
        return redirect()->route('admin.configuration.roles')
            ->with('success', 'Rol eliminado exitosamente');
    }

    /**
     * Actualiza el archivo .env
     */
    private function updateEnvironmentFile($data)
    {
        $path = base_path('.env');
        $content = file_get_contents($path);

        foreach ($data as $key => $value) {
            $content = preg_replace(
                "/^{$key}=.*/m",
                "{$key}=" . (strpos($value, ' ') !== false ? '"{$value}"' : $value),
                $content
            );
        }

        file_put_contents($path, $content);
    }
}