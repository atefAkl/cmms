<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\InventoryTransaction;
use App\Models\Supplier;
use App\Models\Warehouse;
use App\Models\InventoryItem;
use Illuminate\Support\Facades\DB;

class PurchaseOrderWebController extends Controller
{
    public function index()
    {
        $orders = PurchaseOrder::with(['supplier', 'warehouse'])->latest()->paginate(15);
        return view('purchasing.index', compact('orders'));
    }

    public function create()
    {
        $suppliers = Supplier::all();
        $warehouses = Warehouse::all();
        $items = InventoryItem::where('is_active', '=', true)->get();
        
        return view('purchasing.create', compact('suppliers', 'warehouses', 'items'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'reference_number' => 'nullable|string|max:255',
            'transaction_date' => 'required|date',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.inventory_item_id' => 'required|exists:inventory_items,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_cost' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $totalCost = 0;
            foreach ($validated['items'] as $item) {
                $totalCost += ($item['quantity'] * $item['unit_cost']);
            }

            $order = PurchaseOrder::create([
                'supplier_id' => $validated['supplier_id'],
                'warehouse_id' => $validated['warehouse_id'],
                'reference_number' => $validated['reference_number'] ?? 'PO-' . time(),
                'transaction_date' => $validated['transaction_date'],
                'status' => 'pending',
                'approval_status' => 'pending',
                'payment_status' => 'unpaid',
                'total_cost' => $totalCost,
                'notes' => $validated['notes'],
            ]);

            foreach ($validated['items'] as $itemData) {
                PurchaseOrderItem::create([
                    'purchase_order_id' => $order->id,
                    'inventory_item_id' => $itemData['inventory_item_id'],
                    'quantity' => $itemData['quantity'],
                    'unit_cost' => $itemData['unit_cost'],
                ]);
            }

            DB::commit();
            return redirect()->route('purchasing.index')->with('success', 'Purchase Order created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error creating Purchase Order: ' . $e->getMessage());
        }
    }

    public function show(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->load(['supplier', 'warehouse', 'items.item']);
        return view('purchasing.show', compact('purchaseOrder'));
    }

    public function approve(PurchaseOrder $purchaseOrder)
    {
        if ($purchaseOrder->approval_status === 'approved') {
            return back()->with('error', 'Purchase Order is already approved.');
        }

        DB::beginTransaction();
        try {
            // Mark as approved and completed
            $purchaseOrder->update([
                'approval_status' => 'approved',
                'status' => 'completed'
            ]);

            // Create Inventory Transactions and update total stock
            foreach ($purchaseOrder->items as $orderItem) {
                // 1. Log the transaction for the specific warehouse
                InventoryTransaction::create([
                    'inventory_item_id' => $orderItem->inventory_item_id,
                    'warehouse_id' => $purchaseOrder->warehouse_id,
                    'type' => 'in',
                    'quantity' => $orderItem->quantity,
                    'reference_type' => PurchaseOrder::class,
                    'reference_id' => $purchaseOrder->id,
                ]);

                // 2. Update global stock on the InventoryItem (if desired)
                $inventoryItem = $orderItem->item;
                $inventoryItem->stock += $orderItem->quantity;
                // Optionally update the average cost here
                $inventoryItem->save();
            }

            DB::commit();
            return redirect()->route('purchasing.show', $purchaseOrder->id)->with('success', 'Purchase Order approved and inventory updated.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error approving Purchase Order: ' . $e->getMessage());
        }
    }
}
