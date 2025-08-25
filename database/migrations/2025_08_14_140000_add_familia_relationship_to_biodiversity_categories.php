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
            // Eliminar el campo family actual (string)
            $table->dropColumn('family');
            
            // Agregar la clave foránea hacia la tabla familias
            $table->unsignedBigInteger('idfamilia')->nullable()->after('kingdom');
            $table->foreign('idfamilia')->references('idfamilia')->on('familias')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('biodiversity_categories', function (Blueprint $table) {
            // Eliminar la clave foránea
            $table->dropForeign(['idfamilia']);
            $table->dropColumn('idfamilia');
            
            // Restaurar el campo family original
            $table->string('family')->nullable()->after('kingdom');
        });
    }
};