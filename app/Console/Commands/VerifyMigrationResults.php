<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\BiodiversityCategory;
use Illuminate\Support\Facades\DB;

class VerifyMigrationResults extends Command
{
    protected $signature = 'verify:migration-results';
    protected $description = 'Verifica los resultados de la migración de biodiversidad';

    public function handle()
    {
        $this->info('=== VERIFICACIÓN DE RESULTADOS DE MIGRACIÓN ===');
        
        // Contar registros totales
        $totalRecords = BiodiversityCategory::count();
        $this->info("✓ Total de registros migrados: {$totalRecords}");
        
        // Mostrar algunos ejemplos
        $this->info('\n=== EJEMPLOS DE REGISTROS MIGRADOS ===');
        $samples = BiodiversityCategory::with('conservationStatus')
            ->limit(5)
            ->get();
            
        foreach ($samples as $sample) {
            $this->line("ID: {$sample->id}");
            $this->line("  Nombre: {$sample->name}");
            $this->line("  Nombre científico: {$sample->scientific_name}");
            $this->line("  Nombre común: {$sample->common_name}");
            $this->line("  Estado conservación (código): {$sample->conservation_status}");
            $this->line("  Estado conservación (ID): {$sample->conservation_status_id}");
            if ($sample->conservationStatus) {
                $this->line("  Estado conservación (nombre): {$sample->conservationStatus->name}");
            }
            $this->line("  Reino: {$sample->kingdom}");
            $this->line('---');
        }
        
        // Estadísticas por estado de conservación
        $this->info('\n=== ESTADÍSTICAS POR ESTADO DE CONSERVACIÓN ===');
        $stats = DB::table('biodiversity_categories')
            ->select('conservation_status', 'conservation_status_id', DB::raw('COUNT(*) as count'))
            ->groupBy('conservation_status', 'conservation_status_id')
            ->orderBy('count', 'desc')
            ->get();
            
        foreach ($stats as $stat) {
            $this->line("Código: {$stat->conservation_status} (ID: {$stat->conservation_status_id}) - {$stat->count} registros");
        }
        
        $this->info('\n✓ Verificación completada exitosamente.');
        
        return 0;
    }
}