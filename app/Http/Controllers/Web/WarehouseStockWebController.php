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
            'inventory_item_id',
            'warehouse_id',
            DB::raw('SUM(CASE WHEN type IN ("in", "adjustment_up", "return") THEN quantity ELSE -quantity END) as current_stock')
        )
        ->with(['item.category', 'warehouse'])
        ->groupBy('inventory_item_id', 'warehouse_id')
        ->havingRaw('SUM(CASE WHEN type IN ("in", "adjustment_up", "return") THEN quantity ELSE -quantity END) != 0');

        if ($warehouseId) {
            $query->where('warehouse_id', $warehouseId);
        }

        $stocks = $query->paginate(20);

        return view('inventory.warehouse-stock', compact('stocks', 'warehouses', 'warehouseId'));
    }
}
