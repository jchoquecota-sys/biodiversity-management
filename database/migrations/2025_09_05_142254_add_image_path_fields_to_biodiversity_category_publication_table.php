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
        Schema::table('biodiversity_category_publication', function (Blueprint $table) {
            // Campos para rutas de imágenes reales de fuentes nacionales e internacionales
            $table->text('image_path')->nullable()->comment('Ruta principal de imagen de fuente nacional o internacional');
            $table->text('image_path2')->nullable()->comment('Segunda imagen alternativa');
            $table->text('image_path3')->nullable()->comment('Tercera imagen alternativa');
            $table->text('image_path4')->nullable()->comment('Cuarta imagen alternativa');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('biodiversity_category_publication', function (Blueprint $table) {
            $table->dropColumn([
                'image_path',
                'image_path2', 
                'image_path3',
                'image_path4'
            ]);
        });
    }
};
