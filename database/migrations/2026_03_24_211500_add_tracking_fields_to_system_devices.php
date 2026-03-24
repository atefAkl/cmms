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
        Schema::table('system_devices', function (Blueprint $table) {
            $table->string('serial_number', 100)->nullable()->after('name');
            $table->foreignId('warehouse_id')->nullable()->after('product_id')->constrained('warehouses');
            
            // Add index for uniqueness later if needed, but for now we validate in controller
            // as requested (across systems). 
            $table->index('serial_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('system_devices', function (Blueprint $table) {
            $table->dropConstrainedForeignId('warehouse_id');
            $table->dropColumn('serial_number');
        });
    }
};
