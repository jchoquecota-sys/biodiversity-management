<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\BiodiversityCategory;
use App\Models\Familia;

class MigrateFamilyDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Este seeder debe ejecutarse ANTES de aplicar la migración que elimina el campo 'family'
        // para migrar los datos existentes a la nueva estructura
        
        $this->command->info('Iniciando migración de datos de familia...');
        
        // Obtener todas las categorías que tienen un valor en el campo 'family'
        $categories = DB::table('biodiversity_categories')
            ->whereNotNull('family')
            ->where('family', '!=', '')
            ->get();
            
        $migratedCount = 0;
        $notFoundCount = 0;
        
        foreach ($categories as $category) {
            // Buscar la familia correspondiente por nombre
            $familia = Familia::where('nombre', 'LIKE', '%' . trim($category->family) . '%')
                ->orWhere('nombre', 'LIKE', trim($category->family) . '%')
                ->orWhere('nombre', 'LIKE', '%' . trim($category->family))
                ->first();
                
            if ($familia) {
                // Actualizar la categoría con el ID de la familia
                DB::table('biodiversity_categories')
                    ->where('id', $category->id)
                    ->update(['idfamilia' => $familia->idfamilia]);
                    
                $migratedCount++;
                $this->command->info("Migrado: {$category->name} -> Familia: {$familia->nombre}");
            } else {
                $notFoundCount++;
                $this->command->warn("No se encontró familia para: {$category->name} (family: {$category->family})");
                
                // Opcionalmente, crear la familia si no existe
                // $newFamilia = Familia::create([
                //     'nombre' => $category->family,
                //     'definicion' => 'Familia creada automáticamente durante la migración',
                //     'idorden' => null // Deberá asignarse manualmente
                // ]);
            }
        }
        
        $this->command->info("Migración completada:");
        $this->command->info("- Categorías migradas: {$migratedCount}");
        $this->command->info("- Familias no encontradas: {$notFoundCount}");
        
        if ($notFoundCount > 0) {
            $this->command->warn("IMPORTANTE: {$notFoundCount} categorías no pudieron ser migradas.");
            $this->command->warn("Revise manualmente estas categorías y asigne las familias correspondientes.");
        }
    }
}