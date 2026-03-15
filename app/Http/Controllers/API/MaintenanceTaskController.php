<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\MaintenanceTask;
use App\Http\Resources\API\MaintenanceTaskResource;
use Illuminate\Http\Request;

class MaintenanceTaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $tasks = MaintenanceTask::with(['room', 'compressor', 'technician'])->latest()->paginate(10);
        return MaintenanceTaskResource::collection($tasks);
    }

    /**
     * Get tasks assigned to the authenticated technician.
     */
    public function myTasks(Request $request)
    {
        $tasks = MaintenanceTask::with(['room', 'compressor'])
            ->where('technician_id', auth()->user()->id)
            ->whereNotIn('status', ['completed', 'closed'])
            ->latest()
            ->get();
            
        return MaintenanceTaskResource::collection($tasks);
    }

    /**
     * Update task status (Start/Complete).
     */
    public function updateStatus(Request $request, MaintenanceTask $maintenanceTask)
    {
        $data = $request->validate([
            'status' => 'required|string|in:in_progress,completed,diagnosed',
            'started_at' => 'nullable|date',
            'completed_at' => 'nullable|date',
        ]);

        $maintenanceTask->update($data);

        return new MaintenanceTaskResource($maintenanceTask->load(['room', 'compressor']));
    }

    /**
     * Record part usage for a task.
     */
    public function addPart(Request $request, MaintenanceTask $maintenanceTask)
    {
        $data = $request->validate([
            'inventory_item_id' => 'required|exists:inventory_items,id',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string'
        ]);

        $item = \App\Models\InventoryItem::findOrFail($data['inventory_item_id']);
        
        // Deduct stock
        $item->decrement('stock', $data['quantity']);

        // Link part to task
        $maintenanceTask->parts()->create([
            'inventory_item_id' => $data['inventory_item_id'],
            'quantity' => $data['quantity'],
            'unit_cost' => $item->cost,
            'notes' => $data['notes']
        ]);

        return response()->json(['message' => 'Part added and stock updated']);
    }
}
