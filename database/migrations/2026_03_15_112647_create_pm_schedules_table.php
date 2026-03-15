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
        Schema::create('pm_schedules', function (Blueprint $table) {
            $table->id();
            $table->morphs('equipment'); // Creates equipment_type and equipment_id
            $table->string('description');
            $table->integer('interval_days');
            $table->string('priority')->default('medium'); // low, medium, high
            $table->integer('estimated_duration')->nullable(); // in minutes
            $table->date('last_performed')->nullable();
            $table->date('next_due');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pm_schedules');
    }
};
