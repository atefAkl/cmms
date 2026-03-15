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
        if (Schema::hasTable('spare_parts') && !Schema::hasTable('inventory_items')) {
            Schema::rename('spare_parts', 'inventory_items');
        }

        Schema::table('inventory_items', function (Blueprint $table) {
            if (!Schema::hasColumn('inventory_items', 'category_id')) {
                $table->foreignId('category_id')->nullable()->after('id')->constrained('item_categories')->nullOnDelete();
            }
            if (!Schema::hasColumn('inventory_items', 'type')) {
                $table->enum('type', ['part', 'consumable', 'tool', 'other'])->default('part')->after('category_id');
            }
            if (!Schema::hasColumn('inventory_items', 'uom')) {
                $table->string('uom')->default('unit')->after('type');
            }
            if (!Schema::hasColumn('inventory_items', 'reference_number')) {
                $table->string('reference_number')->nullable()->after('part_number');
            }
            if (!Schema::hasColumn('inventory_items', 'min_stock_level')) {
                $table->integer('min_stock_level')->default(0)->after('cost');
            }
            if (!Schema::hasColumn('inventory_items', 'supplier_id')) {
                $table->foreignId('supplier_id')->nullable()->after('min_stock_level')->constrained()->nullOnDelete();
            }
            if (!Schema::hasColumn('inventory_items', 'is_active')) {
                $table->boolean('is_active')->default(true);
            }
        });
    }

    public function down(): void
    {
        Schema::table('inventory_items', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropForeign(['supplier_id']);
            $table->dropColumn(['category_id', 'type', 'uom', 'reference_number', 'min_stock_level', 'supplier_id', 'is_active']);
        });

        Schema::rename('inventory_items', 'spare_parts');
    }
};
