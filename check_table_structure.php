<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "=== Estructura de la tabla biodiversity_category_publication ===\n";

try {
    // Obtener columnas de la tabla
    $columns = Schema::getColumnListing('biodiversity_category_publication');
    
    echo "Columnas encontradas:\n";
    foreach ($columns as $column) {
        echo "- $column\n";
    }
    
    echo "\n=== Descripción detallada de la tabla ===\n";
    $description = DB::select('DESCRIBE biodiversity_category_publication');
    
    foreach ($description as $field) {
        echo "Campo: {$field->Field}\n";
        echo "  Tipo: {$field->Type}\n";
        echo "  Null: {$field->Null}\n";
        echo "  Key: {$field->Key}\n";
        echo "  Default: {$field->Default}\n";
        echo "  Extra: {$field->Extra}\n";
        echo "\n";
    }
    
    echo "\n=== Muestra de datos (primeros 3 registros) ===\n";
    $sample = DB::table('biodiversity_category_publication')->limit(3)->get();
    
    foreach ($sample as $record) {
        echo "ID: {$record->id}\n";
        echo "Biodiversity Category ID: {$record->biodiversity_category_id}\n";
        echo "Publication ID: {$record->publication_id}\n";
        
        // Verificar si existen campos de imagen
        $fields = get_object_vars($record);
        foreach ($fields as $field => $value) {
            if (strpos($field, 'image') !== false) {
                echo "Campo de imagen encontrado - {$field}: {$value}\n";
            }
        }
        echo "---\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

echo "\n=== Verificación completada ===\n";