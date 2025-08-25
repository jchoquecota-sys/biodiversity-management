<?php

namespace App\Console\Commands;

use App\Helpers\VisitCounterHelper;
use App\Models\PageVisit;
use Illuminate\Console\Command;

class CleanOldVisits extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'visits:clean {--days=90 : Number of days to keep visits} {--force : Force deletion without confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean old page visits from the database to maintain performance';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = (int) $this->option('days');
        $force = $this->option('force');
        
        $cutoffDate = now()->subDays($days);
        
        // Contar registros que serán eliminados
        $oldVisitsCount = PageVisit::where('created_at', '<', $cutoffDate)->count();
        
        if ($oldVisitsCount === 0) {
            $this->info('No hay visitas antiguas para limpiar.');
            return 0;
        }
        
        $this->info("Se encontraron {$oldVisitsCount} visitas anteriores a {$cutoffDate->format('Y-m-d')}.");
        
        if (!$force && !$this->confirm('¿Desea continuar con la eliminación?')) {
            $this->info('Operación cancelada.');
            return 0;
        }
        
        $this->info('Eliminando visitas antiguas...');
        
        // Eliminar en lotes para evitar problemas de memoria
        $deletedCount = 0;
        $batchSize = 1000;
        
        do {
            $deleted = PageVisit::where('created_at', '<', $cutoffDate)
                ->limit($batchSize)
                ->delete();
            
            $deletedCount += $deleted;
            
            if ($deleted > 0) {
                $this->info("Eliminados {$deletedCount} de {$oldVisitsCount} registros...");
            }
            
        } while ($deleted > 0);
        
        // Limpiar caché relacionado
        $this->info('Limpiando caché de contadores...');
        VisitCounterHelper::clearCache();
        
        $this->info("✅ Limpieza completada. Se eliminaron {$deletedCount} registros de visitas.");
        
        return 0;
    }
}
