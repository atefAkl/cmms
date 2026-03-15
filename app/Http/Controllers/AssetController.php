<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Room;
use App\Models\RefrigerationSystem;
use App\Services\AssetTreeService;
use Illuminate\Http\Request;

class AssetController extends Controller
{
    protected $treeService;

    public function __construct(AssetTreeService $treeService)
    {
        $this->treeService = $treeService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rooms = Room::with('refrigerationSystems.assets')->get();
        return view('assets.index', compact('rooms'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $rooms = Room::all();
        $systems = RefrigerationSystem::all();
        $parentAssets = Asset::all();
        $selectedSystemId = $request->system_id;
        $selectedParentId = $request->parent_id;

        return view('assets.create', compact('rooms', 'systems', 'parentAssets', 'selectedSystemId', 'selectedParentId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string',
            'refrigeration_system_id' => 'required|exists:refrigeration_systems,id',
            'parent_id' => 'nullable|exists:assets,id',
            'status' => 'required|string',
            'manufacturer' => 'nullable|string',
            'model' => 'nullable|string',
            'serial_number' => 'nullable|string',
            'install_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        Asset::create($validated);

        return redirect()->route('assets.index')->with('success', 'Asset created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Asset $asset)
    {
        return view('assets.show', compact('asset'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Asset $asset)
    {
        $rooms = Room::all();
        $systems = RefrigerationSystem::all();
        $parentAssets = Asset::where('id', '!=', $asset->id)->get();
        return view('assets.edit', compact('asset', 'rooms', 'systems', 'parentAssets'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Asset $asset)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string',
            'refrigeration_system_id' => 'required|exists:refrigeration_systems,id',
            'parent_id' => 'nullable|exists:assets,id',
            'status' => 'required|string',
            'manufacturer' => 'nullable|string',
            'model' => 'nullable|string',
            'serial_number' => 'nullable|string',
            'install_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        if ($request->parent_id && $this->treeService->wouldCreateCircularReference($asset->id, (int)$request->parent_id)) {
            return back()->withErrors(['parent_id' => 'Circular reference detected. Parent cannot be a child of this asset.']);
        }

        $asset->update($validated);

        return redirect()->route('assets.index')->with('success', 'Asset updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Asset $asset)
    {
        $asset->delete();
        return redirect()->route('assets.index')->with('success', 'Asset deleted successfully.');
    }
}
