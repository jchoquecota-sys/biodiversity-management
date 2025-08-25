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
            $table->string('code', 10)->unique(); // Código IUCN (LC, NT, VU, EN, CR, etc.)
            $table->string('name'); // Nombre completo del estado
            $table->text('description')->nullable(); // Descripción del estado
            $table->string('color', 7)->nullable(); // Color hexadecimal para mostrar en UI
            $table->integer('priority')->default(0); // Prioridad para ordenamiento
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
