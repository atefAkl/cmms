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
        // 1. Add asset_id columns safely
        if (!Schema::hasColumn('maintenance_tasks', 'asset_id')) {
            Schema::table('maintenance_tasks', function (Blueprint $table) {
                $table->foreignId('asset_id')->nullable()->after('refrigeration_system_id')->constrained('assets');
            });
        }

        if (!Schema::hasColumn('inspections', 'asset_id')) {
            Schema::table('inspections', function (Blueprint $table) {
                $table->foreignId('asset_id')->nullable()->after('room_id')->constrained('assets');
            });
        }

        // 2. Data Migration
        // Only migrate if assets table is empty or we haven't migrated yet (basic check)
        if (DB::table('assets')->count() == 0) {
            // Process Compressors
            if (Schema::hasTable('compressors')) {
                $compressors = DB::table('compressors')->get();
                foreach ($compressors as $compressor) {
                    $assetId = DB::table('assets')->insertGetId([
                        'name' => $compressor->name,
                        'type' => 'compressor',
                        'refrigeration_system_id' => $compressor->refrigeration_system_id,
                        'status' => $compressor->status,
                        'created_at' => $compressor->created_at,
                        'updated_at' => $compressor->updated_at,
                    ]);

                    if (Schema::hasColumn('maintenance_tasks', 'compressor_id')) {
                        DB::table('maintenance_tasks')
                            ->where('compressor_id', $compressor->id)
                            ->update(['asset_id' => $assetId]);
                    }

                    if (Schema::hasColumn('inspections', 'compressor_id')) {
                        DB::table('inspections')
                            ->where('compressor_id', $compressor->id)
                            ->update(['asset_id' => $assetId]);
                    }
                }
            }

            // Process Evaporators
            if (Schema::hasTable('evaporators')) {
                $evaporators = DB::table('evaporators')->get();
                foreach ($evaporators as $evaporator) {
                    $assetId = DB::table('assets')->insertGetId([
                        'name' => 'Evaporator ' . ($evaporator->id),
                        'type' => 'evaporator',
                        'refrigeration_system_id' => $evaporator->refrigeration_system_id,
                        'status' => $evaporator->status,
                        'notes' => "Fans: " . ($evaporator->fan_count ?? 0) . ", Heaters: " . ($evaporator->heater_count ?? 0),
                        'created_at' => $evaporator->created_at,
                        'updated_at' => $evaporator->updated_at,
                    ]);

                    if (Schema::hasColumn('maintenance_tasks', 'evaporator_id')) {
                        DB::table('maintenance_tasks')
                            ->where('evaporator_id', $evaporator->id)
                            ->update(['asset_id' => $assetId]);
                    }
                }
            }
        }

        // 3. Drop old columns and tables safely
        Schema::table('maintenance_tasks', function (Blueprint $table) {
            if (Schema::hasColumn('maintenance_tasks', 'compressor_id')) {
                $table->dropColumn('compressor_id');
            }
            if (Schema::hasColumn('maintenance_tasks', 'evaporator_id')) {
                $table->dropColumn('evaporator_id');
            }
        });

        Schema::table('inspections', function (Blueprint $table) {
            if (Schema::hasColumn('inspections', 'compressor_id')) {
                $table->dropColumn('compressor_id');
            }
        });

        Schema::dropIfExists('compressors');
        Schema::dropIfExists('evaporators');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            //
        });
    }
};
