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
        // First convert existing data to valid JSON strings before changing column type
        $layouts = \Illuminate\Support\Facades\DB::table('room_layouts')->get();
        foreach ($layouts as $layout) {
            
            // Skip if already looks like JSON or empty
            if (empty($layout->layout_dimensions) || str_starts_with($layout->layout_dimensions, '{')) {
                continue;
            }
            
            $lDim = explode('x', $layout->layout_dimensions);
            $dDim = current(explode('x', $layout->door_dimensions)) ? explode('x', $layout->door_dimensions) : [];

            $lJson = json_encode([
                'width' => $lDim[0] ?? 0,
                'length' => $lDim[1] ?? 0,
                'height' => $lDim[2] ?? 0,
            ]);
            
            $dJson = json_encode([
                'width' => $dDim[0] ?? 0,
                'height' => $dDim[1] ?? 0,
            ]);

            \Illuminate\Support\Facades\DB::table('room_layouts')->where('id', $layout->id)->update([
                'layout_dimensions' => $lJson,
                'door_dimensions' => $dJson,
            ]);
        }

        Schema::table('room_layouts', function (Blueprint $table) {
            $table->json('layout_dimensions')->nullable()->change();
            $table->json('door_dimensions')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('room_layouts', function (Blueprint $table) {
            $table->string('layout_dimensions')->nullable()->change();
            $table->string('door_dimensions')->nullable()->change();
        });
    }
};
