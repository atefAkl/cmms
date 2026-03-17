<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Str;

class WarehouseWebController extends Controller
{
    public function index()
    {
        $warehouses = Warehouse::latest()->paginate(10);
        return view('warehouses.index', compact('warehouses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'max_room_count' => 'nullable|numeric|max:255',
            'max_path_count' => 'nullable|numeric|max:255',
            'wh_width' => 'nullable|numeric|max:255',
            'wh_length' => 'nullable|numeric|max:255',
            'diameter_unit' => 'nullable|in:mm,cm,m,in,ft',
            'door_dimensions' => 'nullable|string|max:255',
        ]);

        $validated['is_active'] = $request->is_active ?? 1;
        $validated['branch_id'] = $request->branch_id ?? 1;
        $validated['slug'] = Str::slug($request->name);
        $validated['diameter'] = $request->wh_width . 'x' . $request->wh_length;

        // Use collect() to easily exclude temporary fields from the array
        $data = collect($validated)->except(['wh_width', 'wh_length'])->toArray();

        try {
            Warehouse::create($data);
            return back()->with('success', 'Warehouse created successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Warehouse creation failed: ' . $e->getMessage());
        }
    }

    public function update(Request $request, Warehouse $warehouse)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string'
        ]);

        $warehouse->update($validated);

        return back()->with('success', 'Warehouse updated successfully.');
    }

    public function destroy(Warehouse $warehouse)
    {
        // Add check for inventory transactions before deletion if needed
        $warehouse->delete();
        return back()->with('success', 'Warehouse deleted.');
    }
}
