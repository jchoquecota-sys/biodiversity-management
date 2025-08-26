<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Crear tabla de log temporal para registrar la migración
        Schema::create('temp_migration_log', function (Blueprint $table) {
            $table->id();
            $table->string('operation');
            $table->text('details')->nullable();
            $table->boolean('success')->default(false);
            $table->timestamps();
        });

        // Verificar si existen campos de publicación antes de migrar
        $columns = DB::getSchemaBuilder()->getColumnListing('biodiversity_categories');
        $publicationFields = ['autor_publicacion', 'titulo_publicacion', 'revista_publicacion', 'año_publicacion', 'doi'];
        
        $hasPublicationFields = true;
        foreach ($publicationFields as $field) {
            if (!in_array($field, $columns)) {
                $hasPublicationFields = false;
                break;
            }
        }

        if ($hasPublicationFields) {
            // Registrar inicio de migración
            DB::table('temp_migration_log')->insert([
                'operation' => 'migration_start',
                'details' => 'Iniciando migración automática de datos de publicación',
                'success' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            try {
                // Ejecutar el comando de migración de datos automáticamente
                $exitCode = Artisan::call('migrate:publication-data', ['--force' => true]);
                
                if ($exitCode === 0) {
                    DB::table('temp_migration_log')->insert([
                        'operation' => 'data_migration',
                        'details' => 'Migración de datos completada exitosamente',
                        'success' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                } else {
                    DB::table('temp_migration_log')->insert([
                        'operation' => 'data_migration',
                        'details' => 'Error en la migración de datos. Código de salida: ' . $exitCode,
                        'success' => false,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    
                    throw new \Exception('Error ejecutando migración de datos de publicación');
                }
            } catch (\Exception $e) {
                DB::table('temp_migration_log')->insert([
                    'operation' => 'migration_error',
                    'details' => 'Error durante la migración: ' . $e->getMessage(),
                    'success' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                
                throw $e;
            }
        } else {
            // Los campos ya fueron eliminados, registrar que no hay nada que migrar
            DB::table('temp_migration_log')->insert([
                'operation' => 'no_migration_needed',
                'details' => 'Los campos de publicación ya no existen en biodiversity_categories',
                'success' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // En caso de rollback, mostrar advertencia
        DB::table('temp_migration_log')->insert([
            'operation' => 'rollback_warning',
            'details' => 'ADVERTENCIA: Se está haciendo rollback de la migración de datos. Los datos migrados permanecerán en las tablas publications y biodiversity_category_publication.',
            'success' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        Schema::dropIfExists('temp_migration_log');
    }
};

/*
 * INSTRUCCIONES DE USO:
 * 
 * 1. Esta migración debe ejecutarse ANTES de la migración que elimina los campos de publicación
 * 2. Ejecuta automáticamente el comando: php artisan migrate:publication-data --force
 * 3. Registra todo el proceso en la tabla temp_migration_log
 * 4. Si hay errores, la migración fallará y no se procederá con la eliminación de campos
 * 
 * Para ejecutar manualmente:
 * php artisan migrate --path=database/migrations/2025_08_26_134747_migrate_publication_data_before_removal.php
 * 
 * Para ver el log de la migración:
 * SELECT * FROM temp_migration_log ORDER BY created_at DESC;
 */
