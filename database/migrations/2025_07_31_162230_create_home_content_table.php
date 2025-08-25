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
        Schema::create('home_content', function (Blueprint $table) {
            $table->id();
            $table->string('section'); // hero, search, stats, cta
            $table->string('key'); // title, subtitle, description, etc.
            $table->text('value');
            $table->string('type')->default('text'); // text, image, url
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            
            $table->unique(['section', 'key']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('home_content');
    }
};
