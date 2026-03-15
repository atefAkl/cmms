<?php

namespace App\Services;

use App\Models\InventoryItem;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class InventoryService
{
    /**
     * Checks all inventory items against their minimum stock levels.
     * Generates Purchase Orders for those below the threshold.
     */
    public function checkLowStockAndReorder()
    {
        // Get items where stock is at or below minimum level AND min_stock_level is strictly > 0
        $lowStockItems = InventoryItem::whereColumn('stock', '<=', 'min_stock_level')
                                   ->where('min_stock_level', '>', 0)
                                   ->whereNotNull('supplier_id')
                                   ->get();

        if ($lowStockItems->isEmpty()) {
            Log::info('Inventory check completed. No low stock items found.');
            return;
        }

        // Group by supplier
        $itemsBySupplier = $lowStockItems->groupBy('supplier_id');

        foreach ($itemsBySupplier as $supplierId => $items) {
            
            // Check if there's already a pending PO for this supplier
            $existingPo = PurchaseOrder::where('supplier_id', $supplierId)
                                       ->where('status', 'pending')
                                       ->first();

            DB::beginTransaction();
            try {
                $po = $existingPo ?? PurchaseOrder::create([
                    'supplier_id' => $supplierId,
                    'status' => 'pending',
                    'total_cost' => 0
                ]);

                foreach ($items as $item) {
                    // Check if this item is already in the pending PO
                    $poItemExists = PurchaseOrderItem::where('purchase_order_id', $po->id)
                                                     ->where('inventory_item_id', $item->id)
                                                     ->exists();
                    if ($poItemExists) {
                        continue;
                    }

                    // Calculate reorder quantity. Let's aim to restock to twice the minimum stock level
                    $reorderQuantity = ($item->min_stock_level * 2) - $item->stock;
                    if ($reorderQuantity <= 0) $reorderQuantity = $item->min_stock_level; // safety fallback
                    
                    $unitCost = $item->cost;

                    PurchaseOrderItem::create([
                        'purchase_order_id' => $po->id,
                        'inventory_item_id' => $item->id,
                        'quantity' => $reorderQuantity,
                        'unit_cost' => $unitCost
                    ]);

                    $po->total_cost += ($reorderQuantity * $unitCost);
                }
                
                $po->save();
                DB::commit();

                Log::info("Purchase Order ID {$po->id} generated for Supplier ID {$supplierId}");
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error("Failed to generate PO for Supplier {$supplierId}. Error: " . $e->getMessage());
            }
        }
    }
}
