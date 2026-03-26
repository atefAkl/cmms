<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\InventoryItem;
use App\Models\ItemCategory;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
            'name'             => 'required|string|max:255',
            'brand'            => 'nullable|string|max:255',
            'category_id'      => 'required|exists:item_categories,id',
            'type'             => 'required|in:part,consumable,tool,other',
            'uom'              => 'required|string|max:50',
            'part_number'      => 'nullable|string|max:255',
            'model_number'     => 'nullable|string|max:255',
            'reference_number' => 'nullable|string|max:255',
            'cost'             => 'required|numeric|min:0',
            'stock'            => 'required|numeric|min:0',
            'min_stock_level'  => 'required|numeric|min:0',
            'tech_specs'       => 'nullable|array',
            'image'            => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'attributes_json'  => 'nullable|string', // raw JSON from Alpine.js hidden field
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('inventory_items', 'public');
            $validated['image'] = $path;
        }

        // ------------------------------------------------------------------
        // Process dynamic attributes from Alpine.js JSON payload.
        // Input:  [{"key":"القوة","value":"5","unit":"حصان"}, ...]
        // Output: {"القوة":["5","حصان"], "قابل للطي":["نعم"]}
        // ------------------------------------------------------------------
        $validated['attributes'] = $this->parseAttributesJson($request->input('attributes_json'));
        unset($validated['attributes_json']); // not a DB column

        try{
            InventoryItem::create($validated);
            return redirect()->route('inventory-items.index')->with('success', 'Inventory item created.');
        }catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Failed to create inventory item. Please try again.');
        }
    }

    public function show(InventoryItem $inventoryItem)
    {
        return view('inventory.items.show', compact('inventoryItem'));
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
            'name'             => 'required|string|max:255',
            'brand'            => 'nullable|string|max:255',
            'category_id'      => 'required|exists:item_categories,id',
            'type'             => 'required|in:part,consumable,tool,other',
            'uom'              => 'required|string|max:50',
            'part_number'      => 'nullable|string|max:255',
            'model_number'     => 'nullable|string|max:255',
            'reference_number' => 'nullable|string|max:255',
            'cost'             => 'required|numeric|min:0',
            'min_stock_level'  => 'required|integer|min:0',
            'supplier_id'      => 'nullable|exists:suppliers,id',
            'is_active'        => 'required|boolean',
            'tech_specs'       => 'nullable|array',
            'image'            => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'attributes_json'  => 'nullable|string', // raw JSON from Alpine.js hidden field
        ]);

        if ($request->hasFile('image')) {
            if ($inventoryItem->image && Storage::disk('public')->exists($inventoryItem->image)) {
                Storage::disk('public')->delete($inventoryItem->image);
            }
            $path = $request->file('image')->store('inventory_items', 'public');
            $validated['image'] = $path;
        }

        // Process dynamic attributes (same logic as store)
        $validated['attributes'] = $this->parseAttributesJson($request->input('attributes_json'));
        unset($validated['attributes_json']); // not a DB column

        $inventoryItem->update($validated);

        return redirect()->route('inventory-items.index')->with('success', 'Inventory item updated.');
    }

    public function destroy(InventoryItem $inventoryItem)
    {
        $inventoryItem->delete();
        return redirect()->route('inventory-items.index')->with('success', 'Inventory item removed.');
    }

    // -------------------------------------------------------------------------
    // PRIVATE HELPERS
    // -------------------------------------------------------------------------

    /**
     * Convert the Alpine.js JSON array of attribute rows into the stored format.
     *
     * Input (JSON string):
     *   [{"key":"القوة","value":"5","unit":"حصان"},{"key":"قابل للطي","value":"نعم","unit":""}]
     *
     * Output (PHP array → stored as JSON):
     *   {"القوة":["5","حصان"],"قابل للطي":["نعم"]}
     *
     * Rules:
     *  - Rows with an empty or missing key are skipped.
     *  - Unit is only included when non-empty.
     */
    private function parseAttributesJson(?string $json): ?array
    {
        if (empty($json)) {
            return null;
        }

        $rows = json_decode($json, true);

        if (!is_array($rows)) {
            return null;
        }

        $result = [];

        foreach ($rows as $row) {
            $key   = trim($row['key']   ?? '');
            $value = trim($row['value'] ?? '');
            $unit  = trim($row['unit']  ?? '');

            // Skip rows that are missing a key
            if ($key === '') {
                continue;
            }

            // Build the value array: [value] or [value, unit]
            $entry = [$value];
            if ($unit !== '') {
                $entry[] = $unit;
            }

            $result[$key] = $entry;
        }

        return empty($result) ? null : $result;
    }
}
