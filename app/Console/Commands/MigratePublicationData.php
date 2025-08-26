<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Publication;
use App\Models\BiodiversityCategory;

class MigratePublicationData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:publication-data {--dry-run : Ejecutar en modo de prueba sin hacer cambios} {--force : Forzar migración sin confirmación}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migra datos de publicación desde biodiversity_categories hacia las tablas publications y biodiversity_category_publication';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔄 Iniciando migración de datos de publicación...');
        
        // Verificar si existen los campos de publicación
        if (!$this->checkPublicationFields()) {
            $this->error('❌ Los campos de publicación no existen en la tabla biodiversity_categories');
            return 1;
        }

        // Obtener registros con datos de publicación
        $categoriesWithPublications = $this->getCategoriesWithPublications();
        
        if ($categoriesWithPublications->isEmpty()) {
            $this->info('ℹ️  No se encontraron registros con datos de publicación para migrar.');
            return 0;
        }

        $this->info("📊 Se encontraron {$categoriesWithPublications->count()} registros con datos de publicación.");
        
        if ($this->option('dry-run')) {
            $this->info('🧪 Modo de prueba activado - No se realizarán cambios en la base de datos');
            $this->previewMigration($categoriesWithPublications);
            return 0;
        }

        // Confirmar migración
        if (!$this->option('force') && !$this->confirm('¿Desea proceder con la migración?')) {
            $this->info('❌ Migración cancelada por el usuario.');
            return 0;
        }

        // Ejecutar migración
        $this->executeMigration($categoriesWithPublications);
        
        $this->info('✅ Migración completada exitosamente!');
        return 0;
    }

    /**
     * Verificar si existen los campos de publicación en biodiversity_categories
     */
    private function checkPublicationFields(): bool
    {
        try {
            $columns = DB::getSchemaBuilder()->getColumnListing('biodiversity_categories');
            $requiredFields = ['autor_publicacion', 'titulo_publicacion', 'revista_publicacion', 'año_publicacion', 'doi'];
            
            foreach ($requiredFields as $field) {
                if (!in_array($field, $columns)) {
                    return false;
                }
            }
            
            return true;
        } catch (\Exception $e) {
            $this->error("Error verificando campos: {$e->getMessage()}");
            return false;
        }
    }

    /**
     * Obtener categorías con datos de publicación
     */
    private function getCategoriesWithPublications()
    {
        return DB::table('biodiversity_categories')
            ->whereNotNull('titulo_publicacion')
            ->where('titulo_publicacion', '!=', '')
            ->get();
    }

    /**
     * Mostrar vista previa de la migración
     */
    private function previewMigration($categories)
    {
        $this->info('\n📋 Vista previa de la migración:');
        $this->info('================================');
        
        foreach ($categories->take(5) as $category) {
            $this->line("\n🔹 Categoría: {$category->name} (ID: {$category->id})");
            $this->line("   Título: {$category->titulo_publicacion}");
            $this->line("   Autor: {$category->autor_publicacion}");
            $this->line("   Revista: {$category->revista_publicacion}");
            $this->line("   Año: {$category->año_publicacion}");
            $this->line("   DOI: {$category->doi}");
        }
        
        if ($categories->count() > 5) {
            $remaining = $categories->count() - 5;
            $this->line("\n... y {$remaining} registros más");
        }
    }

    /**
     * Ejecutar la migración de datos
     */
    private function executeMigration($categories)
    {
        $this->info('\n🚀 Ejecutando migración...');
        
        $progressBar = $this->output->createProgressBar($categories->count());
        $progressBar->start();
        
        $migratedCount = 0;
        $skippedCount = 0;
        
        DB::beginTransaction();
        
        try {
            foreach ($categories as $category) {
                $result = $this->migrateCategory($category);
                
                if ($result) {
                    $migratedCount++;
                } else {
                    $skippedCount++;
                }
                
                $progressBar->advance();
            }
            
            DB::commit();
            $progressBar->finish();
            
            $this->info("\n\n📈 Resultados de la migración:");
            $this->info("   ✅ Migrados: {$migratedCount}");
            $this->info("   ⏭️  Omitidos: {$skippedCount}");
            
        } catch (\Exception $e) {
            DB::rollback();
            $progressBar->finish();
            $this->error("\n❌ Error durante la migración: {$e->getMessage()}");
            throw $e;
        }
    }

    /**
     * Migrar una categoría específica
     */
    private function migrateCategory($category): bool
    {
        try {
            // Verificar si ya existe una publicación con el mismo título y autor
            $existingPublication = Publication::where('title', $category->titulo_publicacion)
                ->where('author', $category->autor_publicacion)
                ->first();
            
            if (!$existingPublication) {
                // Crear nueva publicación
                $publication = Publication::create([
                    'title' => $category->titulo_publicacion,
                    'abstract' => 'Migrado desde biodiversity_categories',
                    'publication_year' => $category->año_publicacion,
                    'author' => $category->autor_publicacion,
                    'journal' => $category->revista_publicacion,
                    'doi' => $category->doi,
                ]);
            } else {
                $publication = $existingPublication;
            }
            
            // Crear relación en biodiversity_category_publication si no existe
            $relationExists = DB::table('biodiversity_category_publication')
                ->where('biodiversity_category_id', $category->id)
                ->where('publication_id', $publication->id)
                ->exists();
            
            if (!$relationExists) {
                DB::table('biodiversity_category_publication')->insert([
                    'biodiversity_category_id' => $category->id,
                    'publication_id' => $publication->id,
                    'relevant_excerpt' => null,
                    'page_reference' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            
            return true;
            
        } catch (\Exception $e) {
            $this->error("Error migrando categoría {$category->id}: {$e->getMessage()}");
            return false;
        }
    }
}
