<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Item:') }} {{ $inventoryItem->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl sm:rounded-2xl border border-gray-100 overflow-hidden">
                <form action="{{ route('inventory-items.update', $inventoryItem) }}" method="POST" class="p-10">
                    @csrf @method('PATCH')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                        <div class="col-span-2">
                            <x-input-label for="name" value="Item Display Name" />
                            <x-text-input name="name" type="text" :value="$inventoryItem->name" class="mt-1 block w-full text-lg font-bold" required />
                        </div>
                        
                        <div>
                            <x-input-label for="category_id" value="Category" />
                            <select name="category_id" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm" required>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ $inventoryItem->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <x-input-label for="is_active" value="Item Status" />
                            <select name="is_active" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm">
                                <option value="1" {{ $inventoryItem->is_active ? 'selected' : '' }}>Available / Active</option>
                                <option value="0" {{ !$inventoryItem->is_active ? 'selected' : '' }}>Disabled / Archived</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 border-t border-gray-50 pt-8">
                        <div>
                            <x-input-label for="type" value="Classification" />
                            <select name="type" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm">
                                @foreach($types as $type)
                                    <option value="{{ $type }}" {{ $inventoryItem->type === $type ? 'selected' : '' }}>{{ ucfirst($type) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <x-input-label for="uom" value="Unit of Measure" />
                            <x-text-input name="uom" type="text" :value="$inventoryItem->uom" class="mt-1 block w-full" required />
                        </div>
                        <div>
                            <x-input-label for="reference_number" value="SKU / Internal Ref" />
                            <x-text-input name="reference_number" type="text" :value="$inventoryItem->reference_number" class="mt-1 block w-full" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8 border-t border-gray-50 pt-8 bg-gray-50/50 p-6 rounded-xl">
                        <div>
                            <x-input-label for="min_stock_level" value="Minimum Alert Level" />
                            <x-text-input name="min_stock_level" type="number" :value="$inventoryItem->min_stock_level" class="mt-1 block w-full font-black text-red-500" required />
                        </div>
                        <div>
                            <x-input-label for="cost" value="Standart Unit Cost" />
                            <x-text-input name="cost" type="number" step="0.01" :value="$inventoryItem->cost" class="mt-1 block w-full font-black text-gray-900" required />
                        </div>
                    </div>

                    <div class="flex items-center justify-end pt-8 border-t border-gray-100">
                        <x-secondary-button x-on:click="window.history.back()" class="mr-3">Cancel</x-secondary-button>
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-black py-4 px-10 rounded-2xl shadow-xl transition transform active:scale-95">
                            Update Item Details
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
