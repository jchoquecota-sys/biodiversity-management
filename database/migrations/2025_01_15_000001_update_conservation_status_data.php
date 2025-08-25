<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Mapear valores largos a códigos de 2 caracteres
        $statusMapping = [
            'Extinto' => 'EX',
            'Extinto en Estado Silvestre' => 'EW',
            'En Peligro Crítico' => 'CR',
            'En Peligro' => 'EN',
            'Vulnerable' => 'VU',
            'Casi Amenazado' => 'NT',
            'Preocupación Menor' => 'LC',
            'Datos Insuficientes' => 'DD',
            'No Evaluado' => 'NE',
        ];

        foreach ($statusMapping as $oldValue => $newValue) {
            DB::table('biodiversity_categories')
                ->where('conservation_status', $oldValue)
                ->update(['conservation_status' => $newValue]);
        }

        // Actualizar cualquier valor que no esté en el mapeo a 'NE' (No Evaluado)
        DB::table('biodiversity_categories')
            ->whereNotIn('conservation_status', array_values($statusMapping))
            ->where('conservation_status', '!=', '')
            ->whereNotNull('conservation_status')
            ->update(['conservation_status' => 'NE']);

        // Actualizar valores nulos o vacíos a 'NE'
        DB::table('biodiversity_categories')
            ->where(function($query) {
                $query->whereNull('conservation_status')
                      ->orWhere('conservation_status', '');
            })
            ->update(['conservation_status' => 'NE']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revertir los códigos a nombres completos
        $statusMapping = [
            'EX' => 'Extinto',
            'EW' => 'Extinto en Estado Silvestre',
            'CR' => 'En Peligro Crítico',
            'EN' => 'En Peligro',
            'VU' => 'Vulnerable',
            'NT' => 'Casi Amenazado',
            'LC' => 'Preocupación Menor',
            'DD' => 'Datos Insuficientes',
            'NE' => 'No Evaluado',
        ];

        foreach ($statusMapping as $oldValue => $newValue) {
            DB::table('biodiversity_categories')
                ->where('conservation_status', $oldValue)
                ->update(['conservation_status' => $newValue]);
        }
    }
};