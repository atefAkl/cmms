<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Device;
use Illuminate\Http\Request;

class DeviceWebController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $devices = Device::latest()->get();
        return view('devices.index', compact('devices'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:45',
            'description' => 'required|string',
        ]);

        Device::create($validated);

        return redirect()->route('devices.index')
            ->with('success', 'Device type added to catalog.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Device $device)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:45',
            'description' => 'required|string',
        ]);

        $device->update($validated);

        return redirect()->back()
            ->with('success', 'Device type updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Device $device)
    {
        $device->delete();
        return redirect()->route('devices.index')
            ->with('success', 'Device type removed from catalog.');
    }
}
