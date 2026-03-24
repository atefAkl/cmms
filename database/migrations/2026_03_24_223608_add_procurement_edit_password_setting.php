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
        DB::table('system_settings')->insert([
            'key' => 'procurement_edit_password',
            'value' => 'admin123',
            'type' => 'string',
            'group' => 'procurement',
            'display_name' => 'كلمة مرور تعديل المشتريات',
            'description' => 'كلمة المرور المطلوبة للسماح بتعديل الفواتير المستلمة',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('system_settings')->where('key', 'procurement_edit_password')->delete();
    }
};
