<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear permisos si no existen
        $permissions = [
            'view_dashboard',
            'manage_users',
            'manage_roles',
            'manage_species',
            'view_species',
            'create_species',
            'edit_species',
            'delete_species',
            'manage_locations',
            'view_locations',
            'create_locations',
            'edit_locations',
            'delete_locations',
            'manage_observations',
            'view_observations',
            'create_observations',
            'edit_observations',
            'delete_observations',
            'generate_reports',
            'view_reports',
            'manage_database',
            'backup_database',
            'restore_database'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Crear roles de ejemplo
        $roles = [
            [
                'name' => 'Administrador',
                'permissions' => $permissions // Todos los permisos
            ],
            [
                'name' => 'Investigador Senior',
                'permissions' => [
                    'view_dashboard',
                    'manage_species',
                    'view_species',
                    'create_species',
                    'edit_species',
                    'delete_species',
                    'manage_locations',
                    'view_locations',
                    'create_locations',
                    'edit_locations',
                    'manage_observations',
                    'view_observations',
                    'create_observations',
                    'edit_observations',
                    'delete_observations',
                    'generate_reports',
                    'view_reports'
                ]
            ],
            [
                'name' => 'Investigador Junior',
                'permissions' => [
                    'view_dashboard',
                    'view_species',
                    'create_species',
                    'edit_species',
                    'view_locations',
                    'create_locations',
                    'view_observations',
                    'create_observations',
                    'edit_observations',
                    'view_reports'
                ]
            ],
            [
                'name' => 'Técnico de Campo',
                'permissions' => [
                    'view_dashboard',
                    'view_species',
                    'view_locations',
                    'create_locations',
                    'view_observations',
                    'create_observations',
                    'edit_observations'
                ]
            ],
            [
                'name' => 'Consultor',
                'permissions' => [
                    'view_dashboard',
                    'view_species',
                    'view_locations',
                    'view_observations',
                    'view_reports'
                ]
            ],
            [
                'name' => 'Estudiante',
                'permissions' => [
                    'view_dashboard',
                    'view_species',
                    'view_locations',
                    'view_observations'
                ]
            ]
        ];

        foreach ($roles as $roleData) {
            $role = Role::firstOrCreate(['name' => $roleData['name']]);
            $role->syncPermissions($roleData['permissions']);
        }
    }
}