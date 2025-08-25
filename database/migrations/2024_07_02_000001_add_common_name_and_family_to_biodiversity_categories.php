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
            $table->string('common_name')->nullable()->after('scientific_name');
            $table->string('family')->nullable()->after('kingdom');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('biodiversity_categories', function (Blueprint $table) {
            $table->dropColumn(['common_name', 'family']);
        });
    }
};