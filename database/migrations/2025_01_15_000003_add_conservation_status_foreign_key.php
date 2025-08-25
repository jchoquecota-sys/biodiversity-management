<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('biodiversity_categories', function (Blueprint $table) {
            // Cambiar el tipo de columna para que coincida con el código de la tabla conservation_statuses
            $table->string('conservation_status', 2)->change();
            
            // Agregar la clave foránea
            $table->foreign('conservation_status')
                  ->references('code')
                  ->on('conservation_statuses')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('biodiversity_categories', function (Blueprint $table) {
            // Eliminar la clave foránea
            $table->dropForeign(['conservation_status']);
            
            // Revertir el tipo de columna
            $table->string('conservation_status')->change();
        });
    }
};