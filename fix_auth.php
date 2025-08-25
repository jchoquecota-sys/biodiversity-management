<?php
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

use Illuminate\Support\Facades\DB;

// Verificar la configuración de autenticación
echo "Verificando configuración de autenticación...\n";
$authConfig = include config_path('auth.php');
echo "Proveedor de autenticación: " . $authConfig['guards']['web']['provider'] . "\n";

// Verificar la configuración de middleware
echo "\nVerificando configuración de middleware...\n";
$kernelPath = app_path('Http/Kernel.php');
echo "Archivo Kernel.php existe: " . (file_exists($kernelPath) ? 'Sí' : 'No') . "\n";

// Verificar la configuración de rutas
echo "\nVerificando configuración de rutas...\n";
$routesPath = base_path('routes/web.php');
echo "Archivo web.php existe: " . (file_exists($routesPath) ? 'Sí' : 'No') . "\n";

// Verificar la configuración de AdminLTE
echo "\nVerificando configuración de AdminLTE...\n";
$adminlteConfig = include config_path('adminlte.php');
echo "Menú de AdminLTE configurado: " . (isset($adminlteConfig['menu']) ? 'Sí' : 'No') . "\n";

// Verificar si hay usuarios en la base de datos
echo "\nVerificando usuarios en la base de datos...\n";
try {
    $userCount = DB::table('users')->count();
    echo "Número de usuarios: " . $userCount . "\n";
    
    if ($userCount > 0) {
        $users = DB::table('users')->get();
        echo "Lista de usuarios:\n";
        foreach ($users as $user) {
            echo "- {$user->name} ({$user->email})\n";
        }
    }
} catch (\Exception $e) {
    echo "Error al verificar usuarios: " . $e->getMessage() . "\n";
}

// Crear un usuario de prueba si no hay usuarios
echo "\nCreando usuario de prueba si es necesario...\n";
try {
    if ($userCount == 0) {
        DB::table('users')->insert([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        echo "Usuario de prueba creado: admin@example.com / password\n";
    } else {
        echo "Ya existen usuarios, no se creará uno nuevo.\n";
    }
} catch (\Exception $e) {
    echo "Error al crear usuario de prueba: " . $e->getMessage() . "\n";
}

echo "\nLimpiando caché...\n";
system('php artisan view:clear');
system('php artisan route:clear');
system('php artisan cache:clear');
system('php artisan config:clear');

echo "\nProceso completado. Por favor, intente acceder al dashboard nuevamente.\n";