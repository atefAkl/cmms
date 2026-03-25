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
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->foreignId('warehouse_id')->nullable()->after('supplier_id')->constrained()->nullOnDelete();
            $table->date('transaction_date')->nullable()->after('warehouse_id');
            $table->string('payment_status')->default('unpaid')->after('status'); // unpaid, partially_paid, paid
            $table->text('notes')->nullable()->after('total_cost');
            $table->string('reference_number')->nullable()->after('id');
            $table->foreignId('supplier_id')->nullable()->change();
        });

        Schema::table('purchase_order_items', function (Blueprint $table) {
            $table->renameColumn('spare_part_id', 'inventory_item_id');
        });

        Schema::table('inventory_transactions', function (Blueprint $table) {
            $table->renameColumn('spare_part_id', 'inventory_item_id');
            $table->foreignId('warehouse_id')->nullable()->after('id')->constrained()->nullOnDelete();
        });

        Schema::table('maintenance_parts', function (Blueprint $table) {
            $table->renameColumn('spare_part_id', 'inventory_item_id');
        });
    }

    public function down(): void
    {
        Schema::table('maintenance_parts', function (Blueprint $table) {
            $table->renameColumn('inventory_item_id', 'spare_part_id');
        });

        Schema::table('inventory_transactions', function (Blueprint $table) {
            $table->dropForeign(['warehouse_id']);
            $table->dropColumn('warehouse_id');
            $table->renameColumn('inventory_item_id', 'spare_part_id');
        });

        Schema::table('purchase_order_items', function (Blueprint $table) {
            $table->renameColumn('inventory_item_id', 'spare_part_id');
        });

        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->dropForeign(['warehouse_id']);
            $table->dropColumn(['warehouse_id', 'transaction_date', 'payment_status', 'notes', 'reference_number']);
            $table->foreignId('supplier_id')->nullable(false)->change();
        });
    }
};
