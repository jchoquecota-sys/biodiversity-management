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
            // Verificar si las columnas ya existen antes de agregarlas
            if (!Schema::hasColumn('biodiversity_categories', 'image_path_2')) {
                $table->string('image_path_2')->nullable()->after('image_path');
            }
            if (!Schema::hasColumn('biodiversity_categories', 'image_path_3')) {
                $table->string('image_path_3')->nullable()->after('image_path_2');
            }
            if (!Schema::hasColumn('biodiversity_categories', 'image_path_4')) {
                $table->string('image_path_4')->nullable()->after('image_path_3');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('biodiversity_categories', function (Blueprint $table) {
            $table->dropColumn(['image_path_2', 'image_path_3', 'image_path_4']);
        });
    }
};