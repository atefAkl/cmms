<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\SystemDevice;
use App\Models\RefrigerationSystem;
use Illuminate\Http\Request;

class SystemDeviceWebController extends Controller
{
    public function create(Request $request)
    {
        $system = RefrigerationSystem::findOrFail($request->system_id);
        $devices = Device::all();
        
        return view('system-devices.create', compact('system', 'devices'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'refrigeration_system_id' => 'required|exists:refrigeration_systems,id',
            'name' => 'required|string|max:45',
            'device_id' => 'required|exists:devices,id',
            'installed' => 'nullable|date',
        ]);

        if (empty($validated['installed'])) {
            $validated['installed'] = now();
        }

        SystemDevice::create($validated);

        return redirect()->route('refrigeration-systems.show', $request->refrigeration_system_id)
            ->with('success', 'Device attached successfully.');
    }

    public function edit(SystemDevice $systemDevice)
    {
        $devices = Device::all();
        return view('system-devices.edit', compact('systemDevice', 'devices'));
    }

    public function update(Request $request, SystemDevice $systemDevice)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:45',
            'device_id' => 'required|exists:devices,id',
            'installed' => 'required|date',
        ]);

        $systemDevice->update($validated);

        return redirect()->route('refrigeration-systems.show', $systemDevice->refrigeration_system_id)
            ->with('success', 'Device updated successfully.');
    }

    public function destroy(SystemDevice $systemDevice)
    {
        $systemId = $systemDevice->refrigeration_system_id;
        $systemDevice->delete();

        return redirect()->route('refrigeration-systems.show', $systemId)
            ->with('success', 'Device detached successfully.');
    }
}
