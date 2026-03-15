<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\InventoryItem;
use App\Models\ItemCategory;
use App\Models\Supplier;
use Illuminate\Http\Request;

class InventoryItemWebController extends Controller
{
    public function index()
    {
        $items = InventoryItem::with(['category', 'supplier'])->latest()->paginate(10);
        return view('inventory.items.index', compact('items'));
    }

    public function create()
    {
        $categories = ItemCategory::all();
        $suppliers = Supplier::all();
        $types = ['part', 'consumable', 'tool', 'other'];
        return view('inventory.items.create', compact('categories', 'suppliers', 'types'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:item_categories,id',
            'type' => 'required|in:part,consumable,tool,other',
            'uom' => 'required|string|max:50',
            'part_number' => 'nullable|string|max:255',
            'reference_number' => 'nullable|string|max:255',
            'cost' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'min_stock_level' => 'required|integer|min:0',
            'supplier_id' => 'nullable|exists:suppliers,id',
        ]);

        InventoryItem::create($validated);

        return redirect()->route('inventory-items.index')->with('success', 'Inventory item created.');
    }

    public function edit(InventoryItem $inventoryItem)
    {
        $categories = ItemCategory::all();
        $suppliers = Supplier::all();
        $types = ['part', 'consumable', 'tool', 'other'];
        return view('inventory.items.edit', compact('inventoryItem', 'categories', 'suppliers', 'types'));
    }

    public function update(Request $request, InventoryItem $inventoryItem)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:item_categories,id',
            'type' => 'required|in:part,consumable,tool,other',
            'uom' => 'required|string|max:50',
            'part_number' => 'nullable|string|max:255',
            'reference_number' => 'nullable|string|max:255',
            'cost' => 'required|numeric|min:0',
            'min_stock_level' => 'required|integer|min:0',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'is_active' => 'required|boolean'
        ]);

        $inventoryItem->update($validated);

        return redirect()->route('inventory-items.index')->with('success', 'Inventory item updated.');
    }

    public function destroy(InventoryItem $inventoryItem)
    {
        $inventoryItem->delete();
        return redirect()->route('inventory-items.index')->with('success', 'Inventory item removed.');
    }
}
