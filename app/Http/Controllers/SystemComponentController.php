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
            'warehouse_id' => 'required_with:product_id|nullable|exists:warehouses,id',
            'name' => 'required|string|max:191',
            'serial_number' => 'nullable|string|max:100|unique:system_devices,serial_number',
            'component_type' => 'nullable|string|max:100',
            'install_type' => 'required|in:init,replace',
            'installed_at' => 'nullable|date',
            'status' => 'nullable|string|max:50',
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
            $component->warehouse_id = $validated['warehouse_id'] ?? null;
            $component->name = $validated['name'];
            $component->serial_number = $validated['serial_number'] ?? null;
            $component->component_type = $validated['component_type'] ?? null;
            $component->install_type = $validated['install_type'];
            $component->installed = $validated['installed_at'] ?? now();
            $component->status = $validated['status'] ?? 'unknown';
            $component->metadata = $validated['metadata'] ?? null;
            $component->created_by = auth()->id();

            $component->save();

            // 1. DEDUCT FROM INVENTORY
            if ($component->product_id && $component->warehouse_id) {
                \App\Models\InventoryTransaction::create([
                    'inventory_item_id' => $component->product_id,
                    'warehouse_id' => $component->warehouse_id,
                    'type' => 'disbursement',
                    'quantity' => 1,
                    'reference' => 'INSTALL-SYS-' . $system_id,
                    'description' => 'Component installation: ' . $component->name . ' (S/N: ' . ($component->serial_number ?? 'N/A') . ')',
                    'user_id' => auth()->id(),
                ]);
            }

            // 2. LOG INSTALLATION
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
                'message' => 'Component registered and inventory updated.',
                'component' => $component->load('product')
            ], 201);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to register component: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $component = SystemDevice::findOrFail($id);

        $validated = $request->validate([
            'parent_id' => 'nullable|exists:system_devices,id',
            'product_id' => 'nullable|exists:inventory_items,id',
            'warehouse_id' => 'nullable|exists:warehouses,id',
            'name' => 'sometimes|required|string|max:191',
            'serial_number' => 'nullable|string|max:100|unique:system_devices,serial_number,' . $id,
            'component_type' => 'nullable|string|max:100',
            'install_type' => 'sometimes|required|in:init,replace',
            'installed_at' => 'nullable|date',
            'status' => 'nullable|in:working,stopped,unknown',
            'metadata' => 'nullable|array',
        ]);

        $oldProductId = $component->product_id;
        $level = $component->level;

        if (array_key_exists('parent_id', $validated) && $validated['parent_id'] != $component->parent_id) {
            if ($validated['parent_id']) {
                $parent = SystemDevice::findOrFail($validated['parent_id']);
                if ($parent->refrigeration_system_id != $component->refrigeration_system_id) {
                    return response()->json(['error' => 'Parent component belongs to a different system.'], 422);
                }
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
            if (isset($validated['serial_number'])) $component->serial_number = $validated['serial_number'];
            if (isset($validated['product_id'])) $component->product_id = $validated['product_id'];
            if (isset($validated['warehouse_id'])) $component->warehouse_id = $validated['warehouse_id'];
            if (isset($validated['component_type'])) $component->component_type = $validated['component_type'];
            if (isset($validated['install_type'])) $component->install_type = $validated['install_type'];
            if (isset($validated['installed_at'])) $component->installed = $validated['installed_at'];
            if (isset($validated['metadata'])) $component->metadata = $validated['metadata'];
            if (isset($validated['status'])) {
                $component->status = $validated['status'];
                $component->last_status_ts = now();
            }
            $component->updated_by = auth()->id();
            
            $component->save();

            // Log if product changed 
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

    /**
     * Handle removal, return to warehouse, or transfer.
     */
    public function disposition(Request $request, $id)
    {
        $component = SystemDevice::findOrFail($id);
        
        $validated = $request->validate([
            'action' => 'required|in:return_to_warehouse,transfer_to_system,scrap',
            'warehouse_id' => 'required_if:action,return_to_warehouse|exists:warehouses,id',
            'target_system_id' => 'required_if:action,transfer_to_system|exists:refrigeration_systems,id',
            'reason' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            switch ($validated['action']) {
                case 'return_to_warehouse':
                    if ($component->product_id) {
                        \App\Models\InventoryTransaction::create([
                            'inventory_item_id' => $component->product_id,
                            'warehouse_id' => $validated['warehouse_id'],
                            'type' => 'recovery', 
                            'quantity' => 1,
                            'reference' => 'RETURN-SYS-' . $component->refrigeration_system_id,
                            'description' => 'Component returned: ' . $component->name . ' (S/N: ' . ($component->serial_number ?? 'N/A') . ') - Reason: ' . ($validated['reason'] ?? 'None'),
                            'user_id' => auth()->id(),
                        ]);
                    }
                    // Cascade soft delete component as it's no longer in systems
                    $this->cascadeSoftDelete($component);
                    break;

                case 'transfer_to_system':
                    $component->refrigeration_system_id = $validated['target_system_id'];
                    $component->parent_id = null; // Reset hierarchy on transfer
                    $component->level = 0;
                    $component->save();
                    break;

                case 'scrap':
                    // Just soft delete
                    $this->cascadeSoftDelete($component);
                    break;
            }

            DB::commit();
            return response()->json(['message' => 'Component disposition completed successfully.']);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Disposition failed: ' . $e->getMessage()], 500);
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
