<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\RefrigerationSystem;
use App\Models\Room;

class RefrigerationSystemController extends Controller
{
    public function index()
    {
        $systems = RefrigerationSystem::with('room')->get();
        return view('refrigeration-systems.index', compact('systems'));
    }

    public function create()
    {
        $rooms = Room::all();
        return view('refrigeration-systems.create', compact('rooms'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'name' => 'required|string|max:255',
            'status' => 'required|in:active,inactive,maintenance',
            'installed_at' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        RefrigerationSystem::create($validated);

        return redirect()->route('refrigeration-systems.index')->with('success', 'Refrigeration System created successfully');
    }

    public function show(RefrigerationSystem $refrigerationSystem)
    {
        $refrigerationSystem->load(['room', 'systemDevices.device', 'temperatureReadings' => function($q) {
            $q->latest()->take(10);
        }]);
        return view('refrigeration-systems.show', compact('refrigerationSystem'));
    }

    public function edit(RefrigerationSystem $refrigerationSystem)
    {
        $rooms = Room::all();
        return view('refrigeration-systems.edit', compact('refrigerationSystem', 'rooms'));
    }

    public function update(Request $request, RefrigerationSystem $refrigerationSystem)
    {
        $validated = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'name' => 'required|string|max:255',
            'status' => 'required|in:active,inactive,maintenance',
            'installed_at' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $refrigerationSystem->update($validated);

        return redirect()->route('refrigeration-systems.index')->with('success', 'Refrigeration System updated successfully');
    }

    public function destroy(RefrigerationSystem $refrigerationSystem)
    {
        $refrigerationSystem->delete();
        return redirect()->route('refrigeration-systems.index')->with('success', 'Refrigeration System deleted successfully');
    }
}
