<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\SparePart;
use App\Models\Supplier;
use App\Http\Requests\StoreInventoryRequest;
use App\Http\Requests\UpdateInventoryRequest;
use Illuminate\Http\Request;

class InventoryWebController extends Controller
{
    public function index()
    {
        $parts = SparePart::with('supplier')->latest()->paginate(10);
        return view('inventory.index', compact('parts'));
    }

    public function create()
    {
        $suppliers = Supplier::all();
        return view('inventory.create', compact('suppliers'));
    }

    public function store(StoreInventoryRequest $request)
    {
        SparePart::create($request->validated());
        return redirect()->route('inventory.index')->with('success', 'Spare part added to inventory.');
    }

    public function edit(SparePart $inventory)
    {
        $suppliers = Supplier::all();
        return view('inventory.edit', [
            'part' => $inventory,
            'suppliers' => $suppliers
        ]);
    }

    public function update(UpdateInventoryRequest $request, SparePart $inventory)
    {
        $inventory->update($request->validated());
        return redirect()->route('inventory.index')->with('success', 'Spare part updated.');
    }

    public function destroy(SparePart $inventory)
    {
        $inventory->delete();
        return redirect()->route('inventory.index')->with('success', 'Spare part removed from inventory.');
    }
}
