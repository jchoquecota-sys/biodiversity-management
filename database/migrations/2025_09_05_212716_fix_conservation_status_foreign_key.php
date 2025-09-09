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
        // Primero, eliminar la clave foránea incorrecta en conservation_status
        Schema::table('biodiversity_categories', function (Blueprint $table) {
            $table->dropForeign(['conservation_status']);
        });
        
        // Eliminar temporalmente la clave foránea de conservation_status_id
        Schema::table('biodiversity_categories', function (Blueprint $table) {
            $table->dropForeign(['conservation_status_id']);
        });
        
        // Poblar conservation_status_id basado en conservation_status
        $this->populateConservationStatusId();
        
        // Hacer conservation_status_id NOT NULL después de poblarlo
        Schema::table('biodiversity_categories', function (Blueprint $table) {
            $table->unsignedBigInteger('conservation_status_id')->nullable(false)->change();
        });
        
        // Restaurar la clave foránea de conservation_status_id
        Schema::table('biodiversity_categories', function (Blueprint $table) {
            $table->foreign('conservation_status_id')
                  ->references('id')
                  ->on('conservation_statuses')
                  ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Eliminar la clave foránea de conservation_status_id
        Schema::table('biodiversity_categories', function (Blueprint $table) {
            $table->dropForeign(['conservation_status_id']);
        });
        
        // Revertir conservation_status_id a nullable
        Schema::table('biodiversity_categories', function (Blueprint $table) {
            $table->unsignedBigInteger('conservation_status_id')->nullable()->change();
        });
        
        // Limpiar conservation_status_id
        DB::table('biodiversity_categories')->update(['conservation_status_id' => null]);
        
        // Restaurar la clave foránea de conservation_status_id con SET NULL
        Schema::table('biodiversity_categories', function (Blueprint $table) {
            $table->foreign('conservation_status_id')
                  ->references('id')
                  ->on('conservation_statuses')
                  ->onDelete('set null');
        });
        
        // Restaurar la clave foránea incorrecta en conservation_status
        Schema::table('biodiversity_categories', function (Blueprint $table) {
            $table->foreign('conservation_status')
                  ->references('code')
                  ->on('conservation_statuses')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');
        });
    }
    
    /**
     * Poblar conservation_status_id basado en conservation_status
     */
    private function populateConservationStatusId(): void
    {
        // Obtener el mapeo de códigos a IDs
        $statusMapping = DB::table('conservation_statuses')
            ->pluck('id', 'code')
            ->toArray();
        
        // Actualizar conservation_status_id basado en conservation_status
        foreach ($statusMapping as $code => $id) {
            DB::table('biodiversity_categories')
                ->where('conservation_status', $code)
                ->update(['conservation_status_id' => $id]);
        }
        
        // Para cualquier registro que no tenga conservation_status_id, usar 'NE' (No Evaluado)
        $neId = $statusMapping['NE'] ?? null;
        if ($neId) {
            DB::table('biodiversity_categories')
                ->whereNull('conservation_status_id')
                ->update(['conservation_status_id' => $neId]);
        }
    }
};