<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'display_name',
        'description',
        'options',
        'range',
    ];

    protected $casts = [
        'options' => 'array',
        'range' => 'array',
    ];

    /**
     * Get the typed value of the setting.
     */
    public function getTypedValueAttribute()
    {
        return match ($this->type) {
            'integer' => (int) $this->value,
            'boolean' => (bool) $this->value,
            default => $this->value,
        };
    }

    /**
     * Static helper to get setting value by key.
     */
    public static function get($key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        return $setting ? $setting->typed_value : $default;
    }
}
