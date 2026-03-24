<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\ItemWorkRegistry;
use App\Models\Asset;
use App\Models\AssetComponent;
use App\Models\RefrigerationSystem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemWorkRegistryController extends Controller
{
    public function index(Request $request)
    {
        $query = ItemWorkRegistry::with(['item', 'creator']);

        if ($request->has('system_id')) {
            // Filter by components/assets of the system
            $assetIds = Asset::where('refrigeration_system_id', $request->system_id)->pluck('id');
            $componentIds = AssetComponent::whereIn('asset_id', $assetIds)->pluck('id');
            
            $query->where(function($q) use ($assetIds, $componentIds) {
                $q->where(function($sq) use ($assetIds) {
                    $sq->where('item_type', Asset::class)->whereIn('item_id', $assetIds);
                })->orWhere(function($sq) use ($componentIds) {
                    $sq->where('item_type', AssetComponent::class)->whereIn('item_id', $componentIds);
                });
            });
        }

        if ($request->has('date')) {
            $query->whereDate('created_at', $request->date);
        }

        if ($request->has('shift')) {
            $query->where('shift', $request->shift);
        }

        return response()->json($query->latest()->paginate(20));
    }

    public function components($system_id)
    {
        $system = RefrigerationSystem::with(['assets.components'])->findOrFail($system_id);

        $components = $system->assets->flatMap(function($asset) {
            return $asset->components->map(function($comp) use ($asset) {
                $comp->parent_asset_name = $asset->name;
                $comp->last_registry = ItemWorkRegistry::where('item_type', AssetComponent::class)
                    ->where('item_id', $comp->id)
                    ->latest()
                    ->first();
                return $comp;
            });
        });

        return response()->json([
            'system_name' => $system->name,
            'components' => $components
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'system_id' => 'required|exists:refrigeration_systems,id',
            'shift' => 'required|in:mss,mes,ess,ees',
            'register_type' => 'required|in:auto,manually',
            'components' => 'required|array',
            'components.*.component_id' => 'required',
            'components.*.status' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            foreach ($data['components'] as $compData) {
                // Determine if it's an Asset or AssetComponent (defaulting to AssetComponent for this flow)
                $type = AssetComponent::class; 
                
                ItemWorkRegistry::create([
                    'item_id' => $compData['component_id'],
                    'item_type' => $type,
                    'status' => $compData['status'],
                    'shift' => $data['shift'],
                    'register_type' => $data['register_type'],
                    'created_by' => auth()->id(),
                    'notes' => $data['notes'] ?? null,
                ]);

                // Update last status on component
                AssetComponent::where('id', $compData['component_id'])->update([
                    'last_status' => $compData['status'],
                    'last_status_ts' => now(),
                ]);
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Status registered successfully.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
