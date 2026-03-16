<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\MaintenanceTask;
use App\Models\Room;
use App\Models\Asset;
use App\Models\User;
use App\Http\Requests\StoreMaintenanceRequest;
use App\Http\Requests\UpdateMaintenanceRequest;
use Illuminate\Http\Request;

class MaintenanceWebController extends Controller
{
    public function index()
    {
        $query = MaintenanceTask::with(['room', 'asset', 'technician'])->latest();

        // If not a Manager, filter by technician_id
        if (!auth()->user()->hasRole('Manager')) {
            $query->where('technician_id', auth()->user()->id);
        }

        $tasks = $query->paginate(10);
        return view('maintenance.index', compact('tasks'));
    }

    public function create()
    {
        $rooms = Room::all();
        $assets = Asset::with('refrigerationSystem')->get();
        $technicians = User::all();
        return view('maintenance.create', compact('rooms', 'assets', 'technicians'));
    }

    public function store(StoreMaintenanceRequest $request)
    {
        MaintenanceTask::create($request->validated());
        return redirect()->route('maintenance.index')->with('success', 'Maintenance task created successfully.');
    }

    public function edit(MaintenanceTask $maintenance)
    {
        $rooms = Room::all();
        $assets = Asset::with('refrigerationSystem')->get();
        $technicians = User::all();
        return view('maintenance.edit', [
            'task' => $maintenance,
            'rooms' => $rooms,
            'assets' => $assets,
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
