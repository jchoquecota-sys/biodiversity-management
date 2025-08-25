<?php
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Verificar si existe la vista admin.dashboard
echo "Verificando vista admin.dashboard...\n";
$viewPath = resource_path('views/admin/dashboard.blade.php');
echo "Archivo de vista existe: " . (file_exists($viewPath) ? 'Sí' : 'No') . "\n";

// Verificar si existe la vista adminlte::page
echo "\nVerificando vista adminlte::page...\n";
$adminltePath = base_path('vendor/jeroennoten/laravel-adminlte/resources/views/page.blade.php');
echo "Archivo de vista adminlte::page existe: " . (file_exists($adminltePath) ? 'Sí' : 'No') . "\n";

// Verificar si el paquete AdminLTE está instalado correctamente
echo "\nVerificando instalación de AdminLTE...\n";
echo "Paquete AdminLTE instalado: " . (class_exists('JeroenNoten\\LaravelAdminLte\\AdminLte') ? 'Sí' : 'No') . "\n";

// Verificar si el proveedor de servicios de AdminLTE está registrado
echo "\nVerificando registro del proveedor de servicios de AdminLTE...\n";
$configApp = include config_path('app.php');
echo "Proveedor de servicios registrado: " . (in_array('JeroenNoten\\LaravelAdminLte\\AdminLteServiceProvider', $configApp['providers']) ? 'Sí' : 'No') . "\n";

// Crear una copia de seguridad de la vista dashboard
echo "\nCreando copia de seguridad de la vista dashboard...\n";
if (file_exists($viewPath)) {
    copy($viewPath, $viewPath . '.bak');
    echo "Copia de seguridad creada: {$viewPath}.bak\n";
}

// Crear una nueva vista dashboard simplificada para probar
echo "\nCreando nueva vista dashboard simplificada...\n";
$newContent = <<<EOT
@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Dashboard Simple</h3>
                </div>
                <div class="card-body">
                    <p>Esta es una versión simplificada del dashboard para pruebas.</p>
                </div>
            </div>
        </div>
    </div>
@stop
EOT;

file_put_contents($viewPath, $newContent);
echo "Nueva vista dashboard creada.\n";

echo "\nLimpiando caché...\n";
system('php artisan view:clear');
system('php artisan route:clear');
system('php artisan cache:clear');

echo "\nProceso completado. Por favor, intente acceder al dashboard nuevamente.\n";