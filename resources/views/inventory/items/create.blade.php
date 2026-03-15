<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Define New Item') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl sm:rounded-2xl border border-gray-100 overflow-hidden">
                <form action="{{ route('inventory-items.store') }}" method="POST" class="p-10">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                        <div class="col-span-2">
                            <x-input-label for="name" value="Item Display Name" />
                            <x-text-input name="name" type="text" placeholder="e.g. Shell Gadus S2 V220, Compressor Filter, Freon R404A" class="mt-1 block w-full text-lg" required />
                        </div>
                        
                        <div>
                            <x-input-label for="category_id" value="Category" />
                            <select name="category_id" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <x-input-label for="type" value="Item Classification" />
                            <select name="type" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                @foreach($types as $type)
                                    <option value="{{ $type }}">{{ ucfirst($type) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 border-t border-gray-50 pt-8">
                        <div>
                            <x-input-label for="uom" value="Unit of Measure" />
                            <select name="uom" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm">
                                <option value="unit">Unit / Piece</option>
                                <option value="kg">Kilogram (kg)</option>
                                <option value="liter">Liter (L)</option>
                                <option value="meter">Meter (m)</option>
                                <option value="drum">Drum</option>
                            </select>
                        </div>
                        
                        <div>
                            <x-input-label for="reference_number" value="SKU / Internal Ref" />
                            <x-text-input name="reference_number" type="text" placeholder="REF-001" class="mt-1 block w-full" />
                        </div>
                        
                        <div>
                            <x-input-label for="part_number" value="Manufacturer Part #" />
                            <x-text-input name="part_number" type="text" placeholder="OEM-12345" class="mt-1 block w-full" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 border-t border-gray-50 pt-8 bg-gray-50/50 p-6 rounded-xl">
                        <div>
                            <x-input-label for="stock" value="Current Opening Stock" />
                            <x-text-input name="stock" type="number" step="0.01" value="0" class="mt-1 block w-full font-black text-blue-600" required />
                        </div>
                        
                        <div>
                            <x-input-label for="min_stock_level" value="Minimum Alert Level" />
                            <x-text-input name="min_stock_level" type="number" value="1" class="mt-1 block w-full font-black text-red-500" required />
                        </div>
                        
                        <div>
                            <x-input-label for="cost" value="Standard Unit Cost" />
                            <x-text-input name="cost" type="number" step="0.01" value="0" class="mt-1 block w-full font-black" required />
                        </div>
                    </div>

                    <div class="flex items-center justify-end pt-8 border-t border-gray-100">
                        <x-secondary-button x-on:click="window.history.back()" class="mr-3">Discard</x-secondary-button>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-black py-4 px-10 rounded-2xl shadow-xl transition transform active:scale-95">
                            Register Item
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
