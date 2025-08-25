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
            $table->boolean('has_overlay_image')->default(false)->after('is_active');
            $table->string('overlay_position')->default('left')->after('has_overlay_image'); // left, right, center
            $table->string('overlay_alt_text')->nullable()->after('overlay_position');
            $table->text('overlay_description')->nullable()->after('overlay_alt_text');
            $table->string('overlay_button_text')->nullable()->after('overlay_description');
            $table->string('overlay_button_url')->nullable()->after('overlay_button_text');
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
            $table->dropColumn([
                'has_overlay_image',
                'overlay_position',
                'overlay_alt_text',
                'overlay_description',
                'overlay_button_text',
                'overlay_button_url',
                'overlay_width',
                'overlay_height'
            ]);
        });
    }
};
