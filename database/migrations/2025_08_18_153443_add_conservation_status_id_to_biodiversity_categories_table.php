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
            $table->unsignedBigInteger('conservation_status_id')->nullable()->after('idreino');
            $table->foreign('conservation_status_id')->references('id')->on('conservation_statuses')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('biodiversity_categories', function (Blueprint $table) {
            $table->dropForeign(['conservation_status_id']);
            $table->dropColumn('conservation_status_id');
        });
    }
};
