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
        Schema::table('evaporators', function (Blueprint $table) {
            $table->foreignId('refrigeration_system_id')->nullable()->constrained()->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evaporators', function (Blueprint $table) {
            $table->dropForeign(['refrigeration_system_id']);
            $table->dropColumn('refrigeration_system_id');
        });
    }
};
