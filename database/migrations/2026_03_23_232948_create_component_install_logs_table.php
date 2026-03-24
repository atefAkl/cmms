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
        Schema::create('component_install_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('system_device_id');
            $table->unsignedBigInteger('old_product_id')->nullable();
            $table->unsignedBigInteger('new_product_id')->nullable();
            $table->enum('install_type', ['init', 'replace'])->default('init');
            $table->timestamp('installed_at')->nullable();
            $table->unsignedBigInteger('performed_by')->nullable();
            $table->timestamps();

            // Keys
            $table->foreign('system_device_id')->references('id')->on('system_devices')->cascadeOnDelete();
            $table->foreign('old_product_id')->references('id')->on('inventory_items')->nullOnDelete();
            $table->foreign('new_product_id')->references('id')->on('inventory_items')->nullOnDelete();
            $table->foreign('performed_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('component_install_logs');
    }
};
