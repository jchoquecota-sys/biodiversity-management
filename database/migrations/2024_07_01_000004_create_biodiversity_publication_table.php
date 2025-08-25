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
        Schema::create('biodiversity_publication', function (Blueprint $table) {
            $table->foreignId('biodiversity_id')->constrained('biodiversity_categories');
            $table->foreignId('publication_id')->constrained('publications');
            $table->primary(['biodiversity_id', 'publication_id']);
            $table->text('relevant_excerpt')->nullable();
            $table->string('page_reference')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('biodiversity_publication');
    }
};