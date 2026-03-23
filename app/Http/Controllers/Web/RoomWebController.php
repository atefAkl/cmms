<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;
use App\Models\RoomLayout;
use App\Models\Warehouse;
use App\Http\Requests\StoreRoomRequest;
use Illuminate\Support\Str;
use App\Http\Requests\UpdateRoomRequest;
use App\Models\RefrigerationSystem;

class RoomWebController extends Controller
{
    public function index()
    {
        $rooms = Room::with('activeProfileAssignment', 'coolingSystems.assets')->paginate(10);
        return view('rooms.index', compact('rooms'));
    }

    public function create()
    {
        $warehouses = Warehouse::all();
        $layouts = RoomLayout::where('is_active', true)->get();
        return view('rooms.create', compact('warehouses', 'layouts'));
    }

    public function store(StoreRoomRequest $request)
    {
        $validated = $request->validated();
        $data = $validated + [
            'slug' => Str::slug($request->name),
            'created_by' => auth()->user()->id,
            'updated_by' => auth()->user()->id,
            'status' => 'stopped',
            'is_active' => 1
        ];
        try {
            Room::create($data);
            return redirect()->route('rooms.index')->with('success', 'Room created successfully.');
        }
        catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Failed to create room: ' . $e->getMessage());
        }
    }

    public function edit(Room $room)
    {
        $warehouses = Warehouse::all();
        $layouts = RoomLayout::where('is_active', true)->get();
        return view('rooms.edit', compact('room', 'warehouses', 'layouts'));
    }

    public function update(UpdateRoomRequest $request, Room $room)
    {
        $room->update($request->validated());
        return redirect()->route('rooms.index')->with('success', 'Room updated successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Room $room)
    {
        $room->load('layout');

        $room->refrigerationSystems()->with('assets')->get();
        $room->coolingSystems()->with('assets')->get();
        $coolingSystems = RefrigerationSystem::all();
        $profiles = \App\Models\TemperatureProfile::orderBy('name')->get();
        $timeline = app(\App\Services\RoomTemperatureAssignmentService::class)->getTimeline($room);
        $activeProfile = $room->activeProfileAssignment;

        return view('rooms.show', compact('room', 'profiles', 'timeline', 'activeProfile', 'coolingSystems'));
    }

    public function destroy(Room $room)
    {
        $room->delete();
        return redirect()->route('rooms.index')->with('success', 'Room deleted successfully.');
    }
}
