<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\ConservationStatus;

class InspectConservationStatuses extends Command
{
    protected $signature = 'inspect:conservation-statuses';
    protected $description = 'Inspect available conservation statuses';

    public function handle()
    {
        $this->info('=== CONSERVATION STATUSES DISPONIBLES ===');
        
        try {
            $statuses = ConservationStatus::all();
            
            if ($statuses->isEmpty()) {
                $this->warn('No hay estados de conservación disponibles.');
                return 1;
            }
            
            $headers = ['ID', 'Código', 'Nombre', 'Descripción'];
            $rows = [];
            
            foreach ($statuses as $status) {
                $rows[] = [
                    $status->id ?? 'N/A',
                    $status->code ?? 'N/A',
                    $status->name ?? 'N/A',
                    $status->description ?? 'N/A'
                ];
            }
            
            $this->table($headers, $rows);
            
            $this->info('\nTotal: ' . $statuses->count() . ' estados de conservación.');
            
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
            return 1;
        }
        
        return 0;
    }
}