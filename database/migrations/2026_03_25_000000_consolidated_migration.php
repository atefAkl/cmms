<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Users & Auth
        Schema::create('users', function (Blueprint $table) {
            $table->engine('InnoDB');
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->engine('InnoDB');
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->engine('InnoDB');
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });

        // 2. System Tables
        Schema::create('cache', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->mediumText('value');
            $table->integer('expiration');
        });

        Schema::create('cache_locks', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->string('owner');
            $table->integer('expiration');
        });

        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->string('queue')->index();
            $table->longText('payload');
            $table->unsignedTinyInteger('attempts');
            $table->unsignedInteger('reserved_at')->nullable();
            $table->unsignedInteger('available_at');
            $table->unsignedInteger('created_at');
        });

        Schema::create('job_batches', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name');
            $table->integer('total_jobs');
            $table->integer('pending_jobs');
            $table->integer('failed_jobs');
            $table->longText('failed_job_ids');
            $table->mediumText('options')->nullable();
            $table->integer('cancelled_at')->nullable();
            $table->integer('created_at');
            $table->integer('finished_at')->nullable();
        });

        Schema::create('failed_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->text('connection');
            $table->text('queue');
            $table->longText('payload');
            $table->longText('exception');
            $table->timestamp('failed_at')->useCurrent();
        });

        Schema::create('personal_access_tokens', function (Blueprint $table) {
            $table->id();
            $table->morphs('tokenable');
            $table->string('name');
            $table->string('token', 64)->unique();
            $table->text('abilities')->nullable();
            $table->timestamp('last_used_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });

        // 3. Spatie Permissions (Simplified summary)
        $teams = config('permission.teams');
        $columnNames = config('permission.column_names');
        $pivotRole = $columnNames['role_pivot_key'] ?? 'role_id';
        $pivotPermission = $columnNames['permission_pivot_key'] ?? 'permission_id';

        Schema::create('permissions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 125);
            $table->string('guard_name', 125);
            $table->timestamps();
            $table->unique(['name', 'guard_name']);
        });

        Schema::create('roles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 125);
            $table->string('guard_name', 125);
            $table->timestamps();
            $table->unique(['name', 'guard_name']);
        });

        Schema::create('model_has_permissions', function (Blueprint $table) use ($pivotPermission, $columnNames) {
            $table->unsignedBigInteger($pivotPermission);
            $table->string('model_type', 125);
            $table->unsignedBigInteger($columnNames['model_morph_key']);
            $table->index([$columnNames['model_morph_key'], 'model_type'], 'model_has_permissions_model_id_model_type_index');
            $table->foreign($pivotPermission)->references('id')->on('permissions')->onDelete('cascade');
            $table->primary([$pivotPermission, $columnNames['model_morph_key'], 'model_type'], 'model_has_permissions_permission_primary');
        });

        Schema::create('model_has_roles', function (Blueprint $table) use ($pivotRole, $columnNames) {
            $table->unsignedBigInteger($pivotRole);
            $table->string('model_type', 125);
            $table->unsignedBigInteger($columnNames['model_morph_key']);
            $table->index([$columnNames['model_morph_key'], 'model_type'], 'model_has_roles_model_id_model_type_index');
            $table->foreign($pivotRole)->references('id')->on('roles')->onDelete('cascade');
            $table->primary([$pivotRole, $columnNames['model_morph_key'], 'model_type'], 'model_has_roles_role_primary');
        });

        Schema::create('role_has_permissions', function (Blueprint $table) use ($pivotPermission, $pivotRole) {
            $table->unsignedBigInteger($pivotPermission);
            $table->unsignedBigInteger($pivotRole);
            $table->foreign($pivotPermission)->references('id')->on('permissions')->onDelete('cascade');
            $table->foreign($pivotRole)->references('id')->on('roles')->onDelete('cascade');
            $table->primary([$pivotPermission, $pivotRole], 'role_has_permissions_permission_id_role_id_primary');
        });

        // 4. Core Business Domains
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->string('address');
            $table->string('phone');
            $table->string('email');
            $table->string('website');
            $table->string('logo');
            $table->string('favicon');
            $table->string('timezone');
            $table->string('currency');
            $table->string('language');
            $table->string('date_format');
            $table->string('time_format');
            $table->string('date_time_format');
            $table->softDeletes();
            $table->engine('InnoDB');
            $table->timestamps();
        });

        Schema::create('item_categories', function (Blueprint $table) {
            $table->engine('InnoDB');
            $table->id();
            $table->string('name');
            $table->tinyInteger('level')->default(0);
            $table->foreignId('parent_id')->nullable()->constrained('item_categories')->nullOnDelete();
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('suppliers', function (Blueprint $table) {
            $table->engine('InnoDB');
            $table->id();
            $table->string('name');
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->timestamps();
        });

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
            $table->engine('InnoDB');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('warehouses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete();
            $table->integer('max_room_count');
            $table->integer('max_path_count');
            $table->json('diameter');
            $table->json('door_dimensions');
            $table->boolean('is_active')->default(true);
            $table->engine('InnoDB');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('devices', function (Blueprint $table) {
            $table->engine('InnoDB');
            $table->id();
            $table->string('name', 45);
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('rooms', function (Blueprint $table) {
            $table->engine('InnoDB');
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->enum('status', ['running', 'stopped', 'maintenance'])->default('running');
            $table->boolean('is_active')->default(true);
            $table->foreignId('warehouse_id')->nullable()->constrained('warehouses')->cascadeOnDelete();
            $table->foreignId('room_layout_id')->nullable()->constrained('room_layouts')->cascadeOnDelete();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->foreignId('updated_by')->constrained('users')->cascadeOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('refrigeration_systems', function (Blueprint $table) {
            $table->engine('InnoDB');
            $table->id();
            $table->foreignId('room_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('status')->default('active');
            $table->date('installed_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('inventory_items', function (Blueprint $table) {
            $table->engine('InnoDB');
            $table->id();
            $table->string('name');
            $table->string('brand')->nullable();
            $table->string('part_number')->nullable();
            $table->string('model_number')->nullable();
            $table->string('reference_number')->nullable();
            $table->integer('stock')->default(0);
            $table->decimal('cost', 10, 2)->default(0);
            $table->json('tech_specs')->nullable();
            $table->string('uom')->default('unit');
            $table->integer('min_stock_level')->default(0);
            $table->foreignId('category_id')->nullable()->constrained('item_categories')->nullOnDelete();
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers')->nullOnDelete();
            $table->enum('type', ['part', 'consumable', 'tool', 'other'])->default('part');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('system_devices', function (Blueprint $table) {
            $table->engine('InnoDB');
            $table->id();
            $table->foreignId('product_id')->nullable()->constrained('inventory_items')->nullOnDelete();
            $table->foreignId('refrigeration_system_id')->constrained('refrigeration_systems')->onDelete('cascade');
            $table->foreignId('warehouse_id')->nullable()->constrained('warehouses')->nullOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('system_devices')->nullOnDelete();
            $table->unsignedTinyInteger('level')->default(0);
            $table->string('name', 100);
            $table->string('serial_number', 100)->nullable();
            $table->string('component_type', 100)->nullable();
            $table->enum('install_type', ['init', 'replace'])->default('init');
            $table->enum('status', ['working', 'stopped', 'unknown'])->default('unknown');
            $table->timestamp('last_status_ts')->nullable();
            $table->foreignId('device_id')->nullable()->constrained('devices')->onDelete('cascade');
            $table->date('installed')->default(now());
            $table->json('metadata')->nullable();
            $table->foreignId('branch_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('mac_address')->nullable();
            $table->string('ip_address')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->softDeletes();
            $table->timestamps();

            $table->index('parent_id');
            $table->index('product_id');
            $table->index('serial_number');
        });

        Schema::create('assets', function (Blueprint $table) {
            $table->engine('InnoDB');
            $table->id();
            $table->string('name');
            $table->string('type'); 
            $table->foreignId('parent_id')->nullable()->constrained('assets')->onDelete('cascade');
            $table->foreignId('refrigeration_system_id')->constrained('refrigeration_systems')->onDelete('cascade');
            $table->foreignId('system_device_id')->nullable()->constrained('system_devices')->onDelete('set null');
            $table->foreignId('item_category_id')->nullable()->constrained('item_categories')->onDelete('set null');
            $table->foreignId('inventory_item_id')->nullable()->constrained('inventory_items')->onDelete('set null');
            $table->foreignId('branch_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('room_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('status')->default('active');
            $table->string('manufacturer')->nullable();
            $table->string('model')->nullable();
            $table->string('serial_number')->nullable();
            $table->date('install_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index('parent_id');
            $table->index('type');
        });

        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->string('po_number')->unique();
            $table->foreignId('supplier_id')->constrained('suppliers');
            $table->foreignId('branch_id')->constrained('branches');
            $table->date('order_date');
            $table->string('status')->default('pending');
            $table->decimal('total_amount', 12, 2)->default(0);
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });

        Schema::create('purchase_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_order_id')->constrained('purchase_orders')->cascadeOnDelete();
            $table->foreignId('item_id')->constrained('inventory_items');
            $table->integer('quantity');
            $table->decimal('unit_price', 12, 2);
            $table->decimal('total_price', 12, 2);
            $table->string('serial_number')->nullable();
            $table->boolean('is_under_edit')->default(false);
            $table->timestamps();
        });

        Schema::create('maintenance_tasks', function (Blueprint $table) {
            $table->engine('InnoDB');
            $table->id();
            $table->foreignId('room_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('refrigeration_system_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('asset_id')->nullable()->constrained('assets');
            $table->string('maintenance_type')->default('breakdown');
            $table->string('work_order_number')->nullable();
            $table->text('issue_description');
            $table->text('root_cause')->nullable();
            $table->text('repair_action')->nullable();
            $table->foreignId('technician_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('status', ['open', 'diagnosed', 'in_progress', 'resolved', 'approved'])->default('open');
            $table->decimal('cost', 10, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('pm_schedules', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->morphs('equipment'); // Creates equipment_type and equipment_id
            $table->string('description');
            $table->string('frequency_type')->default('monthly'); // daily, weekly, monthly, quarterly, yearly
            $table->integer('frequency_value')->default(1);
            $table->integer('interval_days')->nullable();
            $table->string('priority')->default('medium'); // low, medium, high
            $table->integer('estimated_duration')->nullable(); // in minutes
            $table->date('last_performed')->nullable();
            $table->date('next_due');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('pm_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pm_schedule_id')->constrained('pm_schedules')->cascadeOnDelete();
            $table->string('description')->nullable();
            $table->date('scheduled_date')->nullable();
            $table->string('status')->default('pending');
            $table->boolean('is_mandatory')->default(false);
            $table->timestamps();
        });

        Schema::create('inventory_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained('inventory_items');
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('branch_id')->nullable()->constrained();
            $table->enum('type', ['in', 'out', 'adjustment']);
            $table->integer('quantity');
            $table->string('reference_type')->nullable(); // maintenance_task, purchase_order
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('temperature_readings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->constrained()->cascadeOnDelete();
            $table->foreignId('refrigeration_system_id')->nullable()->constrained()->cascadeOnDelete();
            $table->decimal('temperature', 5, 2);
            $table->decimal('humidity', 5, 2)->nullable();
            $table->foreignId('recorded_by')->constrained('users')->cascadeOnDelete();
            $table->timestamp('recorded_at')->useCurrent();
            $table->timestamps();
        });

        Schema::create('alerts', function (Blueprint $table) {
            $table->engine('InnoDB');
            $table->id();
            $table->foreignId('room_id')->constrained()->cascadeOnDelete();
            $table->decimal('temperature', 5, 2);
            $table->decimal('threshold', 5, 2);
            $table->enum('severity', ['warning', 'critical'])->default('warning');
            $table->enum('status', ['open', 'resolved'])->default('open');
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
        });

        Schema::create('inspections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('asset_id')->nullable()->constrained('assets');
            $table->foreignId('inspector_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('technician_id')->constrained('users')->cascadeOnDelete();
            $table->date('date');
            $table->date('scheduled_date');
            $table->enum('result', ['pass', 'fail', 'needs_attention'])->default('pass');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('inspection_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inspection_id')->constrained('inspections')->cascadeOnDelete();
            $table->string('name');
            $table->string('result')->default('pass'); // pass, fail, na
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('temperature_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('min_temp', 5, 2);
            $table->decimal('max_temp', 5, 2);
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('room_temperature_profile_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->constrained()->cascadeOnDelete();
            $table->foreignId('profile_id')->constrained('temperature_profiles')->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('asset_components', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_id')->constrained('assets')->cascadeOnDelete();
            $table->string('name');
            $table->string('part_number')->nullable();
            $table->timestamps();
        });

        Schema::create('item_work_registries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventory_item_id')->constrained('inventory_items')->cascadeOnDelete();
            $table->string('work_unit'); // hours, cycles, kilometers
            $table->decimal('current_reading', 15, 2)->default(0);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('component_install_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_id')->constrained('assets')->cascadeOnDelete();
            $table->foreignId('inventory_item_id')->constrained('inventory_items')->cascadeOnDelete();
            $table->date('install_date');
            $table->date('remove_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('system_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('string'); 
            $table->string('group')->default('general');
            $table->string('display_name');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_settings');
        Schema::dropIfExists('component_install_logs');
        Schema::dropIfExists('item_work_registries');
        Schema::dropIfExists('asset_components');
        Schema::dropIfExists('room_temperature_profile_assignments');
        Schema::dropIfExists('temperature_profiles');
        Schema::dropIfExists('inspection_items');
        Schema::dropIfExists('inspections');
        Schema::dropIfExists('alerts');
        Schema::dropIfExists('temperature_readings');
        Schema::dropIfExists('inventory_transactions');
        Schema::dropIfExists('pm_tasks');
        Schema::dropIfExists('pm_schedules');
        Schema::dropIfExists('maintenance_tasks');
        Schema::dropIfExists('purchase_order_items');
        Schema::dropIfExists('purchase_orders');
        Schema::dropIfExists('assets');
        Schema::dropIfExists('inventory_items');
        Schema::dropIfExists('system_devices');
        Schema::dropIfExists('refrigeration_systems');
        Schema::dropIfExists('rooms');
        Schema::dropIfExists('devices');
        Schema::dropIfExists('warehouses');
        Schema::dropIfExists('room_layouts');
        Schema::dropIfExists('suppliers');
        Schema::dropIfExists('item_categories');
        Schema::dropIfExists('branches');
        Schema::dropIfExists('role_has_permissions');
        Schema::dropIfExists('model_has_roles');
        Schema::dropIfExists('model_has_permissions');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('personal_access_tokens');
        Schema::dropIfExists('failed_jobs');
        Schema::dropIfExists('job_batches');
        Schema::dropIfExists('jobs');
        Schema::dropIfExists('cache_locks');
        Schema::dropIfExists('cache');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};
