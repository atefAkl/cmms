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
        Schema::create('system_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('string'); // string, integer, boolean
            $table->string('group')->default('general');
            $table->string('display_name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Seed initial monitoring settings
        DB::table('system_settings')->insert([
            [
                'key' => 'temp_cooldown_minutes',
                'value' => '60',
                'type' => 'integer',
                'group' => 'monitoring',
                'display_name' => 'Temperature Cooldown (Minutes)',
                'description' => 'المدة المطلوبة بين كل قراءتين لنفس الغرفة بالدقائق.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'temp_allow_user_time',
                'value' => '0',
                'type' => 'boolean',
                'group' => 'monitoring',
                'display_name' => 'Allow Manual Time Entry',
                'description' => 'السماح للمستخدم بإدخال وقت القراءة يدوياً بدلاً من وقت النظام الحالي.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_settings');
    }
};
