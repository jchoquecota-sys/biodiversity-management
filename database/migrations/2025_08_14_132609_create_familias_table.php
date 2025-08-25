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
        Schema::create('familias', function (Blueprint $table) {
            $table->id('idfamilia');
            $table->string('nombre');
            $table->string('definicion');
            $table->unsignedBigInteger('idorden');
            $table->timestamps();
            
            $table->foreign('idorden')->references('idorden')->on('ordens')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('familias');
    }
};
