<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Services\TemperatureProfileService;
use App\Models\TemperatureProfile;
use Illuminate\Http\Request;

class TemperatureProfileController extends Controller
{
    protected $service;

    public function __construct(TemperatureProfileService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $profiles = $this->service->paginate(
            15, 
            $request->query('search'), 
            $request->query('product_type')
        );

        return view('settings.temperature-profiles.index', compact('profiles'));
    }

    public function create()
    {
        return view('settings.temperature-profiles.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:temperature_profiles,name',
            'min_temp' => 'required|numeric|lt:target_temp',
            'max_temp' => 'required|numeric|gt:target_temp',
            'target_temp' => 'required|numeric',
            'tolerance' => 'required|numeric|min:0',
            'product_type' => 'required|string|in:chilled,frozen,custom',
        ]);

        $this->service->create($validated);

        return redirect()->route('settings.temperature-profiles.index')
                         ->with('success', 'Temperature Profile created successfully.');
    }

    public function edit(TemperatureProfile $temperature_profile)
    {
        return view('settings.temperature-profiles.edit', compact('temperature_profile'));
    }

    public function update(Request $request, TemperatureProfile $temperature_profile)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:temperature_profiles,name,' . $temperature_profile->id,
            'min_temp' => 'required|numeric|lt:target_temp',
            'max_temp' => 'required|numeric|gt:target_temp',
            'target_temp' => 'required|numeric',
            'tolerance' => 'required|numeric|min:0',
            'product_type' => 'required|string|in:chilled,frozen,custom',
        ]);

        $this->service->update($temperature_profile, $validated);

        return redirect()->route('settings.temperature-profiles.index')
                         ->with('success', 'Temperature Profile updated successfully.');
    }

    public function destroy(TemperatureProfile $temperature_profile)
    {
        try {
            $this->service->delete($temperature_profile);
            return redirect()->route('settings.temperature-profiles.index')
                             ->with('success', 'Temperature Profile deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
