<?php

namespace App\Services;

use App\Models\InventoryItem;
use App\Models\InventoryTransaction;
use App\Models\Warehouse;
use Illuminate\Support\Facades\DB;

class InventoryItemService
{
    /**
     * Adjust stock manually or automatically.
     */
    public function adjustStock(InventoryItem $item, float $quantity, string $type, ?int $warehouseId = null, ?string $referenceType = null, ?int $referenceId = null): InventoryTransaction
    {
        return DB::transaction(function () use ($item, $quantity, $type, $warehouseId, $referenceType, $referenceId) {
            
            // Log transaction
            $transaction = InventoryTransaction::create([
                'inventory_item_id' => $item->id,
                'warehouse_id' => $warehouseId,
                'type' => $type,
                'quantity' => $quantity,
                'reference_type' => $referenceType,
                'reference_id' => $referenceId,
            ]);

            if ($type === 'in') {
                $item->increment('stock', $quantity);
            } else {
                if ($item->stock < $quantity) {
                    throw new \Exception("Insufficient stock for item: {$item->name}");
                }
                $item->decrement('stock', $quantity);
            }

            return $transaction;
        });
    }
}
