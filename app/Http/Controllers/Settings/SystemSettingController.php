<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use Illuminate\Http\Request;

class SystemSettingController extends Controller
{
    /**
     * Display a listing of the system settings.
     */
    public function index()
    {
        $settings = SystemSetting::all()->groupBy('group');
        return view('settings.system.index', compact('settings'));
    }

    /**
     * Store a new system setting.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'key' => 'required|string|unique:system_settings,key',
            'display_name' => 'required|string',
            'type' => 'required|string',
            'group' => 'required|string',
            'value' => 'nullable',
            'description' => 'nullable|string',
            'options' => 'nullable|array',
            'range' => 'nullable|array',
        ]);

        $setting = SystemSetting::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Setting created successfully.',
            'setting' => $setting
        ]);
    }

    /**
     * Update the specified system setting in storage via AJAX.
     */
    public function update(Request $request, SystemSetting $system_setting)
    {
        $request->validate([
            'value' => 'nullable',
        ]);

        $value = $request->input('value');
        
        // Range validation
        if ($system_setting->range) {
            $min = $system_setting->range['min'] ?? null;
            $max = $system_setting->range['max'] ?? null;
            
            if ($min !== null && $value < $min) $value = $min;
            if ($max !== null && $value > $max) $value = $max;
        }

        // Basic type conversion
        if ($system_setting->type === 'boolean') {
            $value = filter_var($value, FILTER_VALIDATE_BOOLEAN) ? '1' : '0';
        } elseif ($system_setting->type === 'integer') {
            $value = (int) $value;
        } elseif ($system_setting->type === 'float') {
            $value = (float) $value;
        }

        $system_setting->update(['value' => (string) $value]);

        return response()->json([
            'success' => true,
            'message' => 'Setting updated successfully.',
            'new_value' => $system_setting->typed_value,
        ]);
    }
}
