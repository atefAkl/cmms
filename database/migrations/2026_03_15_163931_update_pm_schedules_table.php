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
        Schema::table('pm_schedules', function (Blueprint $table) {
            $table->string('title')->after('id');
            $table->string('frequency_type')->default('monthly'); // daily, weekly, monthly, quarterly, yearly
            $table->integer('frequency_value')->default(1);
            $table->integer('interval_days')->nullable()->change();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pm_schedules', function (Blueprint $table) {
            //
        });
    }
};
