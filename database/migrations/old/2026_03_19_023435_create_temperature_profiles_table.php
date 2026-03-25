<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('temperature_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->decimal('min_temp', 8, 2);
            $table->decimal('max_temp', 8, 2);
            $table->decimal('target_temp', 8, 2);
            $table->decimal('tolerance', 8, 2)->default(0);
            $table->string('product_type')->default('custom'); // chilled, frozen, custom
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('temperature_profiles');
    }
};
