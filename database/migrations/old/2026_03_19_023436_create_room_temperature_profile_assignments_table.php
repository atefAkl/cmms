<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('room_temperature_profile_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->constrained('rooms')->onDelete('cascade');
            $table->foreignId('temperature_profile_id');
            $table->foreign('temperature_profile_id', 'fk_room_temp_assign_profile_id')->references('id')->on('temperature_profiles')->onDelete('cascade');
            $table->dateTime('start_date');
            $table->dateTime('end_date')->nullable();
            $table->timestamps();
            
            // Helpful index for timeline tracking and active profile lookups
            $table->index(['room_id', 'start_date', 'end_date'], 'room_temp_assignment_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('room_temperature_profile_assignments');
    }
};
