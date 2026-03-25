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
        Schema::create('pm_tasks', function (Blueprint $table) {
            $table->engine('InnoDB');
            $table->id();
            $table->foreignId('pm_schedule_id')->constrained()->cascadeOnDelete();
            $table->date('scheduled_date');
            $table->string('status')->default('pending'); // pending, in_progress, completed, skipped
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('completed_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pm_tasks');
    }
};
