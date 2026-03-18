<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;
use App\Models\RoomLayout;
use App\Models\Warehouse;

class RoomWebController extends Controller
{
    public function index()
    {
        $rooms = Room::with('refrigerationSystems.assets')->paginate(10);
        return view('rooms.index', compact('rooms'));
    }

    public function create()
    {
        $warehouses = Warehouse::all();
        $layouts = RoomLayout::where('is_active', true)->get();
        return view('rooms.create', compact('warehouses', 'layouts'));
    }

    public function store(\App\Http\Requests\StoreRoomRequest $request)
    {
        Room::create($request->validated());
        return redirect()->route('rooms.index')->with('success', 'Room created successfully.');
    }

    public function edit(Room $room)
    {
        return view('rooms.edit', compact('room'));
    }

    public function update(\App\Http\Requests\UpdateRoomRequest $request, Room $room)
    {
        $room->update($request->validated());
        return redirect()->route('rooms.index')->with('success', 'Room updated successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Room $room)
    {
        //
        $warehouses = $room->warehouses()->paginate(10);
        return view('rooms.show', compact('room', 'warehouses'));
    }

    public function destroy(Room $room)
    {
        $room->delete();
        return redirect()->route('rooms.index')->with('success', 'Room deleted successfully.');
    }
}
