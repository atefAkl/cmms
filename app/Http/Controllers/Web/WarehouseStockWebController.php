<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Warehouse;
use App\Models\InventoryItem;
use App\Models\InventoryTransaction;
use Illuminate\Support\Facades\DB;

class WarehouseStockWebController extends Controller
{
    public function index(Request $request)
    {
        $warehouseId = $request->query('warehouse_id');
        $warehouses = Warehouse::all();

        $query = InventoryTransaction::select(
            'inventory_transactions.inventory_item_id',
            'inventory_transactions.warehouse_id',
            DB::raw('SUM(CASE WHEN inventory_transactions.type IN ("in", "adjustment_up", "return") THEN inventory_transactions.quantity ELSE -inventory_transactions.quantity END) as current_stock')
        )
        ->leftJoin('purchase_orders', function($join) {
            $join->on('inventory_transactions.reference_id', '=', 'purchase_orders.id')
                 ->where('inventory_transactions.reference_type', '=', \App\Models\PurchaseOrder::class);
        })
        ->where(function($q) {
            $q->whereNull('purchase_orders.id')
              ->orWhere('purchase_orders.status', '!=', 'editing');
        })
        ->with(['item.category', 'warehouse'])
        ->groupBy('inventory_transactions.inventory_item_id', 'inventory_transactions.warehouse_id')
        ->havingRaw('SUM(CASE WHEN inventory_transactions.type IN ("in", "adjustment_up", "return") THEN inventory_transactions.quantity ELSE -inventory_transactions.quantity END) != 0');

        if ($warehouseId) {
            $query->where('inventory_transactions.warehouse_id', $warehouseId);
        }

        $stocks = $query->paginate(20);

        return view('inventory.warehouse-stock', compact('stocks', 'warehouses', 'warehouseId'));
    }
}
