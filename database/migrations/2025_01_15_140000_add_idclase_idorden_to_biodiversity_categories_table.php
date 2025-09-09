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
            // Agregar campos idclase e idorden
            $table->unsignedBigInteger('idclase')->nullable()->after('idreino');
            $table->unsignedBigInteger('idorden')->nullable()->after('idclase');
            
            // Crear índices para mejorar el rendimiento
            $table->index('idclase', 'biodiversity_categories_idclase_index');
            $table->index('idorden', 'biodiversity_categories_idorden_index');
            
            // Agregar claves foráneas
            $table->foreign('idclase', 'biodiversity_categories_idclase_foreign')
                  ->references('idclase')
                  ->on('clases')
                  ->onDelete('set null')
                  ->onUpdate('cascade');
                  
            $table->foreign('idorden', 'biodiversity_categories_idorden_foreign')
                  ->references('idorden')
                  ->on('ordens')
                  ->onDelete('set null')
                  ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('biodiversity_categories', function (Blueprint $table) {
            // Eliminar claves foráneas primero
            $table->dropForeign('biodiversity_categories_idclase_foreign');
            $table->dropForeign('biodiversity_categories_idorden_foreign');
            
            // Eliminar índices
            $table->dropIndex('biodiversity_categories_idclase_index');
            $table->dropIndex('biodiversity_categories_idorden_index');
            
            // Eliminar columnas
            $table->dropColumn(['idclase', 'idorden']);
        });
    }
};