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
        Schema::table('hero_slider_images', function (Blueprint $table) {
            $table->integer('overlay_width')->default(300)->after('overlay_button_url'); // Width in pixels
            $table->integer('overlay_height')->default(200)->after('overlay_width'); // Height in pixels
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hero_slider_images', function (Blueprint $table) {
            $table->dropColumn(['overlay_width', 'overlay_height']);
        });
    }
};
