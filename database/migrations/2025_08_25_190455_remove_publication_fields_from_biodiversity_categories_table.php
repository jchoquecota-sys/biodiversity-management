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
            // Eliminar campos de publicación redundantes ya que existe la tabla de relación biodiversity_category_publication
            $table->dropColumn([
                'autor_publicacion',
                'titulo_publicacion', 
                'revista_publicacion',
                'año_publicacion',
                'doi'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('biodiversity_categories', function (Blueprint $table) {
            // Restaurar campos de publicación en caso de rollback
            $table->string('autor_publicacion')->nullable()->after('image_path_4');
            $table->string('titulo_publicacion')->nullable()->after('autor_publicacion');
            $table->string('revista_publicacion')->nullable()->after('titulo_publicacion');
            $table->year('año_publicacion')->nullable()->after('revista_publicacion');
            $table->string('doi')->nullable()->after('año_publicacion');
        });
    }
};
