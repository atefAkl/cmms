<?php

namespace App\Services;

use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\InventoryItem;
use App\Models\InventoryTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProcurementService
{
    /**
     * Create a new purchase record header.
     */
    public function createPurchaseOrder(array $data)
    {
        return PurchaseOrder::create([
            'supplier_id' => $data['supplier_id'] ?? null,
            'warehouse_id' => $data['warehouse_id'],
            'reference_number' => $data['reference_number'] ?? null,
            'transaction_date' => $data['transaction_date'] ?? now(),
            'notes' => $data['notes'] ?? null,
            'status' => 'pending',
            'payment_status' => $data['payment_status'] ?? 'unpaid',
            'total_cost' => 0
        ]);
    }

    /**
     * Add an item to a purchase order and update inventory.
     */
    public function addItemToOrder(PurchaseOrder $order, array $itemData)
    {
        return DB::transaction(function () use ($order, $itemData) {
            $item = PurchaseOrderItem::create([
                'purchase_order_id' => $order->id,
                'inventory_item_id' => $itemData['inventory_item_id'],
                'quantity' => $itemData['quantity'],
                'unit_cost' => $itemData['unit_cost'],
                'serial_number' => $itemData['serial_number'] ?? null,
            ]);

            // Update order total
            $order->increment('total_cost', $itemData['quantity'] * $itemData['unit_cost']);

            // If order is received or auto-received, update stock
            if (in_array($order->status, ['ordered', 'received'])) {
                $this->incrementStock($order->warehouse_id, $itemData['inventory_item_id'], $itemData['quantity'], $order);
            }

            return $item;
        });
    }

    /**
     * Update an existing item in a purchase order and adjust stock if received.
     */
    public function updateItemInOrder(PurchaseOrder $order, PurchaseOrderItem $item, array $itemData)
    {
        return DB::transaction(function () use ($order, $item, $itemData) {
            $oldQuantity = $item->quantity;
            $oldTotal = $item->quantity * $item->unit_cost;
            
            $item->update($itemData);
            
            $newQuantity = $item->quantity;
            $newTotal = $item->quantity * $item->unit_cost;
            $diffTotal = $newTotal - $oldTotal;

            // Update order total
            $order->increment('total_cost', $diffTotal);

            // If order is received, adjust stock by the difference
            if (in_array($order->status, ['ordered', 'received'])) {
                $diffQty = $newQuantity - $oldQuantity;
                if ($diffQty != 0) {
                    $this->incrementStock($order->warehouse_id, $item->inventory_item_id, $diffQty, $order);
                }
            }

            return $item;
        });
    }

    /**
     * Remove an item from a purchase order and reverse stock if received.
     */
    public function removeItemFromOrder(PurchaseOrder $order, PurchaseOrderItem $item)
    {
        return DB::transaction(function () use ($order, $item) {
            $qty = $item->quantity;
            $total = $item->quantity * $item->unit_cost;

            // If order is received, reverse stock
            if (in_array($order->status, ['ordered', 'received'])) {
                $this->incrementStock($order->warehouse_id, $item->inventory_item_id, -$qty, $order);
            }

            // Update order total
            $order->decrement('total_cost', $total);

            $item->delete();
            return true;
        });
    }

    /**
     * Finalize order and update stock if not already updated.
     */
    public function markAsReceived(PurchaseOrder $order)
    {
        if ($order->status === 'received') return $order;
        
        if ($order->approval_status !== 'approved') {
            throw new \Exception("Cannot receive an order that has not been approved.");
        }

        return DB::transaction(function () use ($order) {
            $order->update(['status' => 'received']);

            foreach ($order->items as $item) {
                $item->update(['is_under_edit' => false]);
                $this->incrementStock($order->warehouse_id, $item->inventory_item_id, $item->quantity, $order);
            }

            return $order;
        });
    }

    /**
     * Enable editing mode for a received purchase order.
     */
    public function enableEditing(PurchaseOrder $order)
    {
        return DB::transaction(function () use ($order) {
            // 1. Subtract current contents from stock
            foreach ($order->items as $item) {
                $inventoryItem = InventoryItem::find($item->inventory_item_id, ['*']);
                if ($inventoryItem) {
                    $inventoryItem->decrement('stock', $item->quantity);
                }
            }

            // 2. Delete all inventory transactions for this PO
            InventoryTransaction::where('reference_type', '=', PurchaseOrder::class, 'and')
                ->where('reference_id', '=', $order->id, 'and')
                ->delete();

            // 3. Mark all items as under edit
            $order->items()->update(['is_under_edit' => true]);

            // 4. Update status to editing
            $order->update(['status' => 'editing']);
            
            return $order;
        });
    }

    /**
     * Approve a purchase order.
     */
    public function approveOrder(PurchaseOrder $order)
    {
        return $order->update(['approval_status' => 'approved']);
    }

    /**
     * Reject a purchase order.
     */
    public function rejectOrder(PurchaseOrder $order)
    {
        return $order->update(['approval_status' => 'rejected']);
    }

    /**
     * Helper to increment stock and log transaction.
     */
    protected function incrementStock($warehouseId, $itemId, $quantity, $order)
    {
        $inventoryItem = InventoryItem::findOrFail($itemId);
        
        // Update general stock count (for now, assuming simple additive stock)
        $inventoryItem->increment('stock', $quantity);

        // Log transaction
        InventoryTransaction::create([
            'inventory_item_id' => $itemId,
            'warehouse_id' => $warehouseId,
            'type' => 'in',
            'quantity' => $quantity,
            'reference_type' => PurchaseOrder::class,
            'reference_id' => $order->id
        ]);
    }
}
