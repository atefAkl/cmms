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
        Schema::table('maintenance_parts', function (Blueprint $table) {
            $table->renameColumn('cost_at_time', 'unit_cost');
            $table->text('notes')->nullable()->after('quantity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('maintenance_parts', function (Blueprint $table) {
            $table->renameColumn('unit_cost', 'cost_at_time');
            $table->dropColumn('notes');
        });
    }
};
