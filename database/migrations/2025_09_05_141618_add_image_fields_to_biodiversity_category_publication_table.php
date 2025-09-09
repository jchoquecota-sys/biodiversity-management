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
            // Campos para imágenes nacionales
            $table->text('national_image_url')->nullable()->comment('URL de imagen de fuente nacional');
            $table->string('national_image_source', 500)->nullable()->comment('Fuente de la imagen nacional');
            $table->text('national_image_credits')->nullable()->comment('Créditos de la imagen nacional');
            $table->text('national_image_description')->nullable()->comment('Descripción de la imagen nacional');
            
            // Campos para imágenes internacionales
            $table->text('international_image_url')->nullable()->comment('URL de imagen de fuente internacional');
            $table->string('international_image_source', 500)->nullable()->comment('Fuente de la imagen internacional');
            $table->text('international_image_credits')->nullable()->comment('Créditos de la imagen internacional');
            $table->text('international_image_description')->nullable()->comment('Descripción de la imagen internacional');
            
            // Campos adicionales para metadatos
            $table->string('image_license', 100)->nullable()->comment('Licencia de uso de las imágenes');
            $table->boolean('images_verified')->default(false)->comment('Indica si las imágenes han sido verificadas');
            $table->timestamp('images_last_updated')->nullable()->comment('Última actualización de imágenes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('biodiversity_category_publication', function (Blueprint $table) {
            $table->dropColumn([
                'national_image_url',
                'national_image_source',
                'national_image_credits',
                'national_image_description',
                'international_image_url',
                'international_image_source',
                'international_image_credits',
                'international_image_description',
                'image_license',
                'images_verified',
                'images_last_updated'
            ]);
        });
    }
};
