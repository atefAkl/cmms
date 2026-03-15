<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\MaintenanceTask;
use App\Models\Room;
use App\Models\User;
use App\Http\Requests\StoreMaintenanceRequest;
use App\Http\Requests\UpdateMaintenanceRequest;
use Illuminate\Http\Request;

class MaintenanceWebController extends Controller
{
    public function index()
    {
        $tasks = MaintenanceTask::with(['room', 'compressor', 'technician'])->latest()->paginate(10);
        return view('maintenance.index', compact('tasks'));
    }

    public function create()
    {
        $rooms = Room::all();
        $technicians = User::all();
        return view('maintenance.create', compact('rooms', 'technicians'));
    }

    public function store(StoreMaintenanceRequest $request)
    {
        MaintenanceTask::create($request->validated());
        return redirect()->route('maintenance.index')->with('success', 'Maintenance task created successfully.');
    }

    public function edit(MaintenanceTask $maintenance)
    {
        $rooms = Room::all();
        $technicians = User::all();
        return view('maintenance.edit', [
            'task' => $maintenance,
            'rooms' => $rooms,
            'technicians' => $technicians
        ]);
    }

    public function update(UpdateMaintenanceRequest $request, MaintenanceTask $maintenance)
    {
        $maintenance->update($request->validated());
        return redirect()->route('maintenance.index')->with('success', 'Maintenance task updated successfully.');
    }

    public function destroy(MaintenanceTask $maintenance)
    {
        $maintenance->delete();
        return redirect()->route('maintenance.index')->with('success', 'Maintenance task deleted successfully.');
    }
}
