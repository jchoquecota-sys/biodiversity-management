<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\BiodiversityCategory;

class ListSpeciesWithoutImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'biodiversity:list-without-images {--limit=20 : Número de especies a mostrar}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Listar especies que no tienen imágenes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $limit = $this->option('limit');
        
        $this->info('📋 Especies sin imágenes:');
        
        $speciesWithoutImages = BiodiversityCategory::whereNull('image_path')
            ->select('id', 'name', 'scientific_name', 'conservation_status')
            ->limit($limit)
            ->get();
        
        if ($speciesWithoutImages->isEmpty()) {
            $this->info('🎉 ¡Todas las especies tienen imágenes!');
            return 0;
        }
        
        $tableData = [];
        foreach ($speciesWithoutImages as $species) {
            $tableData[] = [
                $species->id,
                $species->name,
                $species->scientific_name ?? 'N/A',
                $species->conservation_status ?? 'N/A'
            ];
        }
        
        $this->table(
            ['ID', 'Nombre', 'Nombre Científico', 'Estado'],
            $tableData
        );
        
        $totalWithoutImages = BiodiversityCategory::whereNull('image_path')->count();
        $this->newLine();
        $this->info("📊 Total de especies sin imágenes: {$totalWithoutImages}");
        
        if ($totalWithoutImages > $limit) {
            $this->info("ℹ️  Mostrando las primeras {$limit} especies. Use --limit para ver más.");
        }
        
        return 0;
    }
}