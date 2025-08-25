<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear usuario administrador principal
        if (!User::where('email', 'admin@biodiversidad.com')->exists()) {
            User::create([
                'name' => 'Administrador Principal',
                'email' => 'admin@biodiversidad.com',
                'password' => Hash::make('admin123'),
                'email_verified_at' => now(),
            ]);
        }

        // Crear usuario de prueba adicional
        if (!User::where('email', 'usuario@biodiversidad.com')->exists()) {
            User::create([
                'name' => 'Usuario de Prueba',
                'email' => 'usuario@biodiversidad.com',
                'password' => Hash::make('usuario123'),
                'email_verified_at' => now(),
            ]);
        }

        // Mantener el usuario de prueba original si no existe
        if (!User::where('email', 'test@example.com')->exists()) {
            User::create([
                'name' => 'Test User',
                'email' => 'test@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]);
        }
    }
}