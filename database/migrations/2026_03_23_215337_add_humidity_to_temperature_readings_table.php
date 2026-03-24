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
        Schema::table('temperature_readings', function (Blueprint $table) {
            $table->decimal('humidity', 5, 2)->nullable()->after('temperature');
            $table->boolean('save_status_snapshot')->default(false)->after('humidity');
            $table->foreignId('registered_by')->nullable()->after('save_status_snapshot')->constrained('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('temperature_readings', function (Blueprint $table) {
            $table->dropForeign(['registered_by']);
            $table->dropColumn(['humidity', 'save_status_snapshot', 'registered_by']);
        });
    }
};
