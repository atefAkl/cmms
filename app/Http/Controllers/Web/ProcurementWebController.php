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
        return view('procurement.show', compact('procurement', 'inventoryItems'));
    }

    public function addItem(Request $request, PurchaseOrder $procurement)
    {
        $validated = $request->validate([
            'inventory_item_id' => 'required|exists:inventory_items,id',
            'quantity' => 'required|numeric|min:0.01',
            'unit_cost' => 'required|numeric|min:0'
        ]);

        $this->procurementService->addItemToOrder($procurement, $validated);

        return back()->with('success', 'Item added to purchase order.');
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
}
