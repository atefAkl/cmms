<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\ItemCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ItemCategoryWebController extends Controller
{
    public function index()
    {
        $categories = ItemCategory::withCount('items')->latest()->paginate(10);
        return view('item-categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:item_categories,name',
            'description' => 'nullable|string'
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        ItemCategory::create($validated);

        return back()->with('success', 'Category created successfully.');
    }

    public function update(Request $request, ItemCategory $itemCategory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:item_categories,name,' . $itemCategory->id,
            'description' => 'nullable|string'
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $itemCategory->update($validated);

        return back()->with('success', 'Category updated successfully.');
    }

    public function destroy(ItemCategory $itemCategory)
    {
        if ($itemCategory->items()->exists()) {
            return back()->with('error', 'Cannot delete category with active items.');
        }

        $itemCategory->delete();
        return back()->with('success', 'Category deleted.');
    }
}
