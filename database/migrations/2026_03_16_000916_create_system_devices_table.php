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
        Schema::create('system_devices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('refrigeration_system_id')->constrained('refrigeration_systems')->onDelete('cascade');
            $table->string('name', 45);
            $table->foreignId('device_id')->constrained('devices')->onDelete('cascade');
            $table->date('installed')->default(now());
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_devices');
    }
};
