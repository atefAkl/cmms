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
            $table->unsignedBigInteger('product_id')->nullable()->after('id');
            $table->unsignedBigInteger('parent_id')->nullable()->after('refrigeration_system_id');
            $table->unsignedTinyInteger('level')->default(0)->after('parent_id');
            $table->string('component_type', 100)->nullable()->after('name');
            $table->enum('install_type', ['init', 'replace'])->default('init')->after('component_type');
            $table->enum('status', ['working', 'stopped', 'unknown'])->default('unknown')->after('install_type');
            $table->timestamp('last_status_ts')->nullable()->after('status');
            $table->json('metadata')->nullable()->after('installed');
            $table->unsignedBigInteger('created_by')->nullable()->after('updated_at');
            $table->unsignedBigInteger('updated_by')->nullable()->after('created_by');
            $table->softDeletes();

            // Existing field refrigeration_system_id is kept.
            // Existing field installed is kept.
            
            // Foreign Keys and Indexes
            $table->foreign('product_id')->references('id')->on('inventory_items')->nullOnDelete();
            $table->foreign('parent_id')->references('id')->on('system_devices')->nullOnDelete();
            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('updated_by')->references('id')->on('users')->nullOnDelete();

            $table->index('parent_id');
            $table->index('product_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('system_devices', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
            $table->dropForeign(['parent_id']);
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            
            $table->dropIndex(['parent_id']);
            $table->dropIndex(['product_id']);

            $table->dropColumn([
                'product_id',
                'parent_id',
                'level',
                'component_type',
                'install_type',
                'status',
                'last_status_ts',
                'metadata',
                'created_by',
                'updated_by',
                'deleted_at'
            ]);
        });
    }
};
