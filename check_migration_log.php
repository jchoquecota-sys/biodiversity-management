<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== REGISTROS DE MIGRACIÓN AUTOMÁTICA ===\n\n";

$logs = DB::table('temp_migration_log')->orderBy('created_at')->get();

if ($logs->isEmpty()) {
    echo "No se encontraron registros de migración.\n";
} else {
    foreach ($logs as $log) {
        $status = $log->success ? '[ÉXITO]' : '[ERROR]';
        echo "• {$log->operation}: {$log->details} {$status}\n";
        echo "  Fecha: {$log->created_at}\n\n";
    }
}

echo "=== FIN DE REGISTROS ===\n";