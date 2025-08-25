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
        Schema::create('conservation_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('code', 2)->unique(); // EX, EW, CR, EN, VU, NT, LC, DD, NE
            $table->string('name'); // Nombre en español
            $table->string('name_en'); // Nombre en inglés
            $table->text('description')->nullable(); // Descripción del estado
            $table->string('color', 20)->default('secondary'); // Color para badges
            $table->integer('priority')->default(0); // Prioridad para ordenamiento
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conservation_statuses');
    }
};