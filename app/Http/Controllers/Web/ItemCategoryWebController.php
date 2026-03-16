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
        $categories = ItemCategory::where([])->with('parent', 'children')->get();
        $allCategories = ItemCategory::withCount('items')->latest()->paginate(10);
        return view('item-categories.index', compact('categories', 'allCategories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:item_categories,name',
            'parent_id' => 'nullable|exists:item_categories,id',
            'description' => 'nullable|string'
        ]);
        $level = ItemCategory::calcLevel($request->parent_id);
        if ($level > 2) {
            return back()->withInput()->withErrors(['parent_id' => 'Category level can only have products.'])->with([
                'opened' => 'create-category',
            ]);
        }
        $validated['slug'] = Str::slug($validated['name']);
        $validated['level'] = $level;

        try {
            ItemCategory::create($validated);
            return back()->with('success', 'Category created successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->with([
                'error' => 'Category creation failed: ' . $e->getMessage(),
                'opened' => 'create-category'
            ]);
        }

    }

    public function update(Request $request, ItemCategory $itemCategory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:item_categories,name,' . $itemCategory->id,
            'parent_id' => 'nullable|exists:item_categories,id',
            'description' => 'nullable|string'
        ]);
        $level = ItemCategory::calcLevel($request->parent_id);
        if ($level > 2) {
            return back()->withInput($request->all() + ['category_id' => $itemCategory->id])->withErrors(['parent_id' => 'Category level can only have products.'])->with([
                'opened' => 'edit-category-' . $itemCategory->id,
            ]);
        }
        $validated['slug'] = Str::slug($validated['name']);
        $validated['level'] = $level;

        try {
            $itemCategory->update($validated);
            return back()->with('success', 'Category updated successfully.');
        } catch (\Exception $e) {
            return back()->withInput($request->all() + ['category_id' => $itemCategory->id])->with([
                'error' => 'Category update failed: ' . $e->getMessage(),
                'opened' => 'edit-category-' . $itemCategory->id
            ]);
        }
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
