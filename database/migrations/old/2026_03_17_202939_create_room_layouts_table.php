<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('room_layouts', function (Blueprint $table) {
            $table->id();
            $table->string('name', 45);
            $table->string('slug', 45)->unique();
            $table->string('image', 100);
            $table->json('layout_dimensions');
            $table->json('door_dimensions');
            $table->enum('door_position', ['left', 'right', 'center']);
            $table->decimal('wall_thickness', 5, 2);
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->foreignId('updated_by')->constrained('users')->cascadeOnDelete();

            $table->engine('INNODB');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_layouts');
    }
};
