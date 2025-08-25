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
            $table->unsignedBigInteger('idreino')->nullable()->after('kingdom');
            $table->foreign('idreino')->references('id')->on('reinos')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('biodiversity_categories', function (Blueprint $table) {
            $table->dropForeign(['idreino']);
            $table->dropColumn('idreino');
        });
    }
};
