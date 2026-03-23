<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Room;
use App\Models\RefrigerationSystem;
use App\Services\AssetService;
use Illuminate\Http\Request;

class AssetController extends Controller
{
    protected $assetService;

    public function __construct(AssetService $assetService)
    {
        $this->assetService = $assetService;
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
            'item_category_id' => 'nullable|exists:item_categories,id',
            'inventory_item_id' => 'nullable|exists:inventory_items,id',
            'refrigeration_system_id' => 'required|exists:refrigeration_systems,id',
            'parent_id' => 'nullable|exists:assets,id',
            'status' => 'required|string',
            'install_date' => 'nullable|date',
        ]);

        // Default install_date to system creation date if not provided
        if (empty($validated['install_date'])) {
            $system = RefrigerationSystem::find($validated['refrigeration_system_id']);
            $validated['install_date'] = $system->created_at->format('Y-m-d');
        }

        try {
            $this->assetService->createAsset($validated);
            return back()->with('success', 'Asset successfully added to system structure.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
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
            'item_category_id' => 'nullable|exists:item_categories,id',
            'inventory_item_id' => 'nullable|exists:inventory_items,id',
            'refrigeration_system_id' => 'required|exists:refrigeration_systems,id',
            'parent_id' => 'nullable|exists:assets,id',
            'status' => 'required|string',
            'install_date' => 'nullable|date',
        ]);

        try {
            $this->assetService->updateAsset($asset, $validated);
            return back()->with('success', 'Asset hierarchy successfully updated.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Asset $asset)
    {
        try {
            $this->assetService->deleteAsset($asset);
            return back()->with('success', 'Asset and children deleted cleanly.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
