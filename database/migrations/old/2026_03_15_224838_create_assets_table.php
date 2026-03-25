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
        Schema::create('assets', function (Blueprint $table) {
            $table->engine('InnoDB');
            $table->id();
            $table->string('name');
            $table->string('type'); // compressor, evaporator, motor, sensor, fan, heater, control_panel, power_panel
            $table->foreignId('parent_id')->nullable()->constrained('assets')->onDelete('cascade');
            $table->foreignId('refrigeration_system_id')->constrained('refrigeration_systems')->onDelete('cascade');
            $table->string('status')->default('active');
            $table->string('manufacturer')->nullable();
            $table->string('model')->nullable();
            $table->string('serial_number')->nullable();
            $table->date('install_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('parent_id');
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
