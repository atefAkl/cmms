<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\PurchaseOrder;
use App\Models\Supplier;
use App\Models\Warehouse;
use App\Models\InventoryItem;
use App\Services\ProcurementService;
use Illuminate\Http\Request;

class ProcurementWebController extends Controller
{
    protected $procurementService;

    public function __construct(ProcurementService $procurementService)
    {
        $this->procurementService = $procurementService;
    }

    public function index()
    {
        $purchases = PurchaseOrder::with(['supplier', 'warehouse'])->latest()->paginate(10);
        return view('procurement.index', compact('purchases'));
    }

    public function create()
    {
        $suppliers = Supplier::all();
        $warehouses = Warehouse::all();
        return view('procurement.create', compact('suppliers', 'warehouses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => 'nullable|exists:suppliers,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'reference_number' => 'nullable|string',
            'transaction_date' => 'required|date',
            'payment_status' => 'required|in:unpaid,partially_paid,paid',
            'notes' => 'nullable|string'
        ]);

        $order = $this->procurementService->createPurchaseOrder($validated);

        return redirect()->route('procurement.show', $order->id)->with('success', 'Purchase header created. add items below.');
    }

    public function show(PurchaseOrder $procurement)
    {
        $procurement->load(['items.item', 'supplier', 'warehouse']);
        $inventoryItems = InventoryItem::where('is_active', true)->get();
        $suppliers = Supplier::all();
        $warehouses = Warehouse::all();
        return view('procurement.show', compact('procurement', 'inventoryItems', 'suppliers', 'warehouses'));
    }

    public function update(Request $request, PurchaseOrder $procurement)
    {
        $validated = $request->validate([
            'supplier_id' => 'nullable|exists:suppliers,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'reference_number' => 'nullable|string',
            'transaction_date' => 'required|date',
            'payment_status' => 'required|in:unpaid,partially_paid,paid',
            'notes' => 'nullable|string'
        ]);

        $procurement->update($validated);

        return back()->with('success', 'Order header updated.');
    }

    public function addItem(Request $request, PurchaseOrder $procurement)
    {
        $rules = [
            'inventory_item_id' => 'required|exists:inventory_items,id',
            'quantity' => 'required|numeric|min:0.01',
            'unit_cost' => 'required|numeric|min:0',
            'serial_number' => 'nullable|string|unique:purchase_order_items,serial_number'
        ];

        // If serial is provided, force quantity to 1
        if ($request->filled('serial_number')) {
            $request->merge(['quantity' => 1]);
        }

        $validated = $request->validate($rules);

        $item = $this->procurementService->addItemToOrder($procurement, $validated);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'item' => [
                    'id' => $item->id,
                    'inventory_item_id' => $item->inventory_item_id,
                    'name' => $item->item->name,
                    'category_name' => $item->item->category->name,
                    'uom' => $item->item->uom,
                    'quantity' => (float)$item->quantity,
                    'unit_cost' => (float)$item->unit_cost,
                    'serial_number' => $item->serial_number
                ]
            ]);
        }

        return back()->with('success', 'Item added.');
    }

    public function updateItem(Request $request, PurchaseOrder $procurement, $itemId)
    {
        $item = \App\Models\PurchaseOrderItem::where('purchase_order_id', $procurement->id)
            ->where('id', $itemId)
            ->firstOrFail();

        $rules = [
            'inventory_item_id' => 'required|exists:inventory_items,id',
            'quantity' => 'required|numeric|min:0.01',
            'unit_cost' => 'required|numeric|min:0',
            'serial_number' => 'nullable|string|unique:purchase_order_items,serial_number,' . $item->id
        ];

        // If serial is provided, force quantity to 1
        if ($request->filled('serial_number')) {
            $request->merge(['quantity' => 1]);
        }

        $itemData = $request->validate($rules);
        $this->procurementService->updateItemInOrder($procurement, $item, $itemData);

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Item updated.');
    }

    public function print(PurchaseOrder $procurement)
    {
        $procurement->load(['items.item', 'supplier', 'warehouse']);
        return view('procurement.print', compact('procurement'));
    }

    public function markAsReceived(PurchaseOrder $procurement)
    {
        try {
            $this->procurementService->markAsReceived($procurement);
            return back()->with('success', 'Order marked as received and stock updated.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function approve(PurchaseOrder $procurement)
    {
        $this->procurementService->approveOrder($procurement);
        return back()->with('success', 'Order approved. It can now be received.');
    }

    public function reject(PurchaseOrder $procurement)
    {
        $this->procurementService->rejectOrder($procurement);
        return back()->with('success', 'Order rejected.');
    }

    public function deleteItem(Request $request, PurchaseOrder $procurement, $itemId)
    {
        $item = \App\Models\PurchaseOrderItem::where('purchase_order_id', $procurement->id)
            ->where('id', $itemId)
            ->firstOrFail();

        $this->procurementService->removeItemFromOrder($procurement, $item);

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Item removed.');
    }

    public function enableEditing(Request $request, \App\Models\PurchaseOrder $procurement)
    {
        $validated = $request->validate([
            'password' => 'required|string'
        ]);

        $correctPassword = \App\Models\SystemSetting::get('procurement_edit_password', 'admin123');

        if ($validated['password'] !== $correctPassword) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'كلمة المرور غير صحيحة'], 403);
            }
            return back()->with('error', 'كلمة المرور غير صحيحة');
        }

        $this->procurementService->enableEditing($procurement);

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'تم تفعيل وضع التعديل بنجاح. الفاتورة الآن مخفية من الرصيد مؤقتاً.');
    }
}
