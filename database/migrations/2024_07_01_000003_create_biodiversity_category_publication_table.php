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
        Schema::create('biodiversity_category_publication', function (Blueprint $table) {
            $table->id();
            $table->foreignId('biodiversity_category_id')->constrained('biodiversity_categories', 'id', 'bio_cat_pub_category_fk')->onDelete('cascade');
            $table->foreignId('publication_id')->constrained('publications', 'id', 'bio_cat_pub_publication_fk')->onDelete('cascade');
            $table->text('relevant_excerpt')->nullable();
            $table->string('page_reference')->nullable();
            $table->timestamps();
            
            // Asegurarse de que no haya duplicados
            $table->unique(['biodiversity_category_id', 'publication_id'], 'bio_cat_pub_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('biodiversity_category_publication');
    }
};