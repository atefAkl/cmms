<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Adds the new `attributes` JSON column to inventory_items.
     * This is separate from the existing `tech_specs` column.
     */
    public function up(): void
    {
        Schema::table('inventory_items', function (Blueprint $table) {
            // Added after tech_specs for logical grouping
            $table->json('attributes')->nullable()->after('tech_specs');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventory_items', function (Blueprint $table) {
            $table->dropColumn('attributes');
        });
    }
};
