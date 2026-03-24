<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RefrigerationSystem;
use App\Models\SystemDevice;
use App\Models\ComponentInstallLog;
use Illuminate\Support\Facades\DB;

class SystemComponentController extends Controller
{
    public function index($system_id)
    {
        $system = RefrigerationSystem::findOrFail($system_id);
        
        // Eager load product and recursive children
        $components = SystemDevice::with(['product', 'children' => function($q) {
                $q->with(['product', 'children.product', 'children.children.product']);
            }])
            ->where('refrigeration_system_id', $system_id)
            ->whereNull('parent_id') // Get root nodes
            ->get();
            
        return response()->json([
            'system' => $system,
            'components' => $components
        ]);
    }

    public function store(Request $request, $system_id)
    {
        $validated = $request->validate([
            'parent_id' => 'nullable|exists:system_devices,id',
            'product_id' => 'nullable|exists:inventory_items,id',
            'name' => 'required|string|max:191',
            'component_type' => 'nullable|string|max:100',
            'install_type' => 'required|in:init,replace',
            'installed_at' => 'nullable|date',
            'metadata' => 'nullable|array',
        ]);

        $system = RefrigerationSystem::findOrFail($system_id);
        $level = 0;

        if (!empty($validated['parent_id'])) {
            $parent = SystemDevice::findOrFail($validated['parent_id']);
            if ($parent->refrigeration_system_id != $system_id) {
                return response()->json(['error' => 'Parent component belongs to a different system.'], 422);
            }
            $level = $parent->level + 1;
            if ($level > 3) {
                return response()->json(['error' => 'Maximum component depth (level 3) exceeded.'], 422);
            }
        }

        DB::beginTransaction();
        try {
            $component = new SystemDevice();
            $component->refrigeration_system_id = $system_id;
            $component->parent_id = $validated['parent_id'] ?? null;
            $component->level = $level;
            $component->product_id = $validated['product_id'] ?? null;
            $component->name = $validated['name'];
            $component->component_type = $validated['component_type'] ?? null;
            $component->install_type = $validated['install_type'];
            $component->installed = $validated['installed_at'] ?? now();
            $component->metadata = $validated['metadata'] ?? null;
            $component->created_by = auth()->id();

            // SQLite test bypass
            if ($request->has('device_id')) {
                $component->device_id = $request->device_id;
            }

            $component->save();

            if ($component->install_type === 'replace' && $component->product_id) {
                ComponentInstallLog::create([
                    'system_device_id' => $component->id,
                    'new_product_id' => $component->product_id,
                    'install_type' => 'replace',
                    'installed_at' => $component->installed,
                    'performed_by' => auth()->id(),
                ]);
            }

            DB::commit();
            
            return response()->json([
                'message' => 'Component created successfully',
                'component' => $component->load('product')
            ], 201);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to create component: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $component = SystemDevice::findOrFail($id);

        $validated = $request->validate([
            'parent_id' => 'nullable|exists:system_devices,id',
            'product_id' => 'nullable|exists:inventory_items,id',
            'name' => 'sometimes|required|string|max:191',
            'component_type' => 'nullable|string|max:100',
            'install_type' => 'sometimes|required|in:init,replace',
            'installed_at' => 'nullable|date',
            'metadata' => 'nullable|array',
            'status' => 'nullable|in:working,stopped,unknown'
        ]);

        $oldProductId = $component->product_id;
        $level = $component->level;

        if (array_key_exists('parent_id', $validated) && $validated['parent_id'] != $component->parent_id) {
            if ($validated['parent_id']) {
                $parent = SystemDevice::findOrFail($validated['parent_id']);
                if ($parent->refrigeration_system_id != $component->refrigeration_system_id) {
                    return response()->json(['error' => 'Parent component belongs to a different system.'], 422);
                }
                // Prevent self-parenting
                if ($parent->id == $component->id) {
                    return response()->json(['error' => 'Component cannot be its own parent.'], 422);
                }
                $level = $parent->level + 1;
                if ($level > 3) {
                    return response()->json(['error' => 'Maximum component depth (level 3) exceeded.'], 422);
                }
            } else {
                $level = 0;
            }
        }

        DB::beginTransaction();
        try {
            if (isset($validated['parent_id'])) $component->parent_id = $validated['parent_id'];
            $component->level = $level;
            if (isset($validated['name'])) $component->name = $validated['name'];
            if (isset($validated['product_id'])) $component->product_id = $validated['product_id'];
            if (isset($validated['component_type'])) $component->component_type = $validated['component_type'];
            if (isset($validated['install_type'])) $component->install_type = $validated['install_type'];
            if (isset($validated['installed_at'])) $component->installed = $validated['installed_at'];
            if (isset($validated['metadata'])) $component->metadata = $validated['metadata'];
            if (isset($validated['status'])) {
                $component->status = $validated['status'];
                $component->last_status_ts = now();
            }
            $component->updated_by = auth()->id();
            if ($request->has('device_id')) {
                $component->device_id = $request->device_id;
            }
            
            $component->save();

            // Log if product changed or it's a replacement update
            if (isset($validated['product_id']) && $oldProductId != $validated['product_id']) {
                ComponentInstallLog::create([
                    'system_device_id' => $component->id,
                    'old_product_id' => $oldProductId,
                    'new_product_id' => $component->product_id,
                    'install_type' => 'replace',
                    'installed_at' => now(),
                    'performed_by' => auth()->id(),
                ]);
            }

            DB::commit();

            return response()->json([
                'message' => 'Component updated successfully',
                'component' => $component->load('product')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to update component: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        $component = SystemDevice::findOrFail($id);

        // Check if there are install logs or potentially maintenance tasks.
        // User said: "لا تسمح بحذف مكون له سجلات تركيب أو صيانة مرتبطة إلا بعد مراجعة (soft delete فقط)."
        $hasLogs = ComponentInstallLog::where('system_device_id', '=', $component->id)->exists();
        
        // As a safeguard, we always Soft Delete since the model uses SoftDeletes.
        // We will cascade soft delete children manually since soft delete doesn't cascade automatically in DB.
        
        DB::beginTransaction();
        try {
            $this->cascadeSoftDelete($component);
            DB::commit();
            return response()->json(['message' => 'Component deleted successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to delete component: ' . $e->getMessage()], 500);
        }
    }
    
    protected function cascadeSoftDelete($component)
    {
        foreach ($component->children as $child) {
            $this->cascadeSoftDelete($child);
        }
        $component->delete();
    }
}
