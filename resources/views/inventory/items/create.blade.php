<x-app-layout>
    <div class="py-2">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <x-page-header 
                title="{{ __('Register Industrial Item') }}" 
                description="Define technical specifications and initial stock levels."
                :backRoute="route('inventory-items.index')"
            />

            <form action="{{ route('inventory-items.store') }}" method="POST" class="space-y-8">
                @csrf
                
                <!-- Section 1: Core Identification -->
                <div class="bg-white shadow-xl sm:rounded-2xl border border-gray-100 overflow-hidden">
                    <div class="px-8 py-5 bg-gray-50 border-b border-gray-100 flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="bg-blue-600 p-2 rounded-lg text-white">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 11h.01M7 15h.01M13 7h.01M13 11h.01M13 15h.01M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-2M17 9h4m-4 0v4m4 0h-4m4 0v4m-4 0h4"></path></svg>
                            </div>
                            <h3 class="font-black text-gray-800 uppercase tracking-tighter">1. Core Identification</h3>
                        </div>
                    </div>
                    
                    <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                        <div class="col-span-2">
                            <x-input-label for="name" value="Item / Product Name" />
                            <x-text-input :value="old('name')" name="name" type="text" placeholder="e.g. Compressor Bitzer 4DC-5.2Y" class="mt-1 block w-full text-lg font-bold" required />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="brand" value="Brand / Manufacturer" />
                            <x-text-input :value="old('brand')" name="brand" type="text" placeholder="e.g. Bitzer, Danfoss, Copeland" class="mt-1 block w-full" />
                            <x-input-error :messages="$errors->get('brand')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="category_id" value="Item Category" />
                            <select :value="old('category_id')" name="category_id" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                <option value="" disabled selected>Select Category</option>
                                @foreach($categories as $category)
                                    <option {{ old('category_id') == $category->id ? 'selected' : '' }} value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="reference_number" value="Internal Asset / SKU Ref" />
                            <x-text-input :value="old('reference_number')" name="reference_number" type="text" placeholder="INV-REF-001" class="mt-1 block w-full font-mono text-sm" />
                            <x-input-error :messages="$errors->get('reference_number')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="part_number" value="Manufacturer Part #" />
                            <x-text-input :value="old('part_number')" name="part_number" type="text" placeholder="PN-9988-77" class="mt-1 block w-full font-mono text-sm" />
                            <x-input-error :messages="$errors->get('part_number')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="model_number" value="Model Number" />
                            <x-text-input :value="old('model_number')" name="model_number" type="text" placeholder="4DC-5.2Y" class="mt-1 block w-full font-mono text-sm" />
                            <x-input-error :messages="$errors->get('model_number')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="type" value="Item Classification" />
                            <select :value="old('type')" name="type" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                @foreach($types as $type)
                                    <option value="{{ $type }}">{{ ucfirst($type) }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('type')" class="mt-2" />
                        </div>
                    </div>
                </div>

                <!-- Section 2: Technical Specifications (HVAC/Cooling focus) -->
                <div class="bg-white shadow-xl sm:rounded-2xl border border-gray-100 overflow-hidden">
                    <div class="px-8 py-5 bg-gray-50 border-b border-gray-100 flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="bg-indigo-600 p-2 rounded-lg text-white">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            </div>
                            <h3 class="font-black text-gray-800 uppercase tracking-tighter">2. Industrial Technical Specs</h3>
                        </div>
                    </div>
                    
                    <div class="p-8 grid grid-cols-1 md:grid-cols-3 gap-x-8 gap-y-6">
                        <div>
                            <x-input-label for="tech_specs[refrigerant]" value="Refrigerant Type" />
                            <select name="tech_specs[refrigerant]" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm">
                                <option value="">N/A</option>
                                <option {{ old('tech_specs.refrigerant') == 'R404A' ? 'selected' : '' }} value="R404A">R404A</option>
                                <option {{ old('tech_specs.refrigerant') == 'R134a' ? 'selected' : '' }} value="R134a">R134a</option>
                                <option {{ old('tech_specs.refrigerant') == 'R448A' ? 'selected' : '' }} value="R448A">R448A</option>
                                <option {{ old('tech_specs.refrigerant') == 'R22' ? 'selected' : '' }} value="R22">R22</option>
                                <option {{ old('tech_specs.refrigerant') == 'R290' ? 'selected' : '' }} value="R290">R290</option>
                            </select>
                            <x-input-error :messages="$errors->get('tech_specs.refrigerant')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="tech_specs[voltage]" value="Voltage" />
                            <select name="tech_specs[voltage]" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm">
                                <option {{ old('tech_specs.voltage') == '' ? 'selected' : '' }} value="">N/A</option>
                                <option {{ old('tech_specs.voltage') == '220-240V' ? 'selected' : '' }} value="220-240V">220-240V</option>
                                <option {{ old('tech_specs.voltage') == '380-420V' ? 'selected' : '' }} value="380-420V">380-420V</option>
                                <option {{ old('tech_specs.voltage') == '460V' ? 'selected' : '' }} value="460V">460V</option>
                                <option {{ old('tech_specs.voltage') == '12V/24V DC' ? 'selected' : '' }} value="12V/24V DC">12V/24V DC</option>
                            </select>
                            <x-input-error :messages="$errors->get('tech_specs.voltage')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="tech_specs[phase]" value="Phase" />
                            <select name="tech_specs[phase]" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm">
                                <option value="">N/A</option>
                                <option {{ old('tech_specs.phase') == '1-Phase' ? 'selected' : '' }} value="1-Phase">Single Phase</option>
                                <option {{ old('tech_specs.phase') == '3-Phase' ? 'selected' : '' }} value="3-Phase">Three Phase</option>
                            </select>
                            <x-input-error :messages="$errors->get('tech_specs.phase')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="tech_specs[capacity]" value="Capacity (HP or BTU)" />
                            <x-text-input :value="old('tech_specs.capacity')" name="tech_specs[capacity]" type="text" placeholder="e.g. 5 HP / 30,000 BTU" class="mt-1 block w-full" />
                            <x-input-error :messages="$errors->get('tech_specs.capacity')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="tech_specs[dimensions]" value="Dimensions (cm)" />
                            <x-text-input :value="old('tech_specs.dimensions')" name="tech_specs[dimensions]" type="text" placeholder="L x W x H" class="mt-1 block w-full font-mono text-sm" />
                            <x-input-error :messages="$errors->get('tech_specs.dimensions')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="tech_specs[weight]" value="Weight (Kg)" />
                            <x-text-input :value="old('tech_specs.weight')" name="tech_specs[weight]" type="number" step="0.1" placeholder="0.00" class="mt-1 block w-full" />
                            <x-input-error :messages="$errors->get('tech_specs.weight')" class="mt-2" />
                        </div>
                    </div>
                </div>

                <!-- Section 3: Inventory & Logistics -->
                <div class="bg-white shadow-xl sm:rounded-2xl border border-gray-100 overflow-hidden">
                    <div class="px-8 py-5 bg-gray-50 border-b border-gray-100 flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="bg-emerald-600 p-2 rounded-lg text-white">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <h3 class="font-black text-gray-800 uppercase tracking-tighter">3. Inventory & Logistics</h3>
                        </div>
                    </div>
                    
                    <div class="p-8 grid grid-cols-1 md:grid-cols-4 gap-6 bg-emerald-50/20">
                        <div>
                            <x-input-label for="uom" value="UoM" />
                            <select name="uom" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm" required>
                                <option {{ old('uom') == 'unit' ? 'selected' : '' }} value="unit">Piece / Unit</option>
                                <option {{ old('uom') == 'kg' ? 'selected' : '' }} value="kg">Kilogram (Kg)</option>
                                <option {{ old('uom') == 'liter' ? 'selected' : '' }} value="liter">Liter (L)</option>
                                <option {{ old('uom') == 'drum' ? 'selected' : '' }} value="drum">Drum</option>
                                <option {{ old('uom') == 'meter' ? 'selected' : '' }} value="meter">Meter (m)</option>
                            </select>
                            <x-input-error :messages="$errors->get('uom')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="stock" value="Initial Stock Quantity" />
                            <x-text-input :value="old('stock')" name="stock" type="number" step="0.01" value="0.00" class="mt-1 block w-full font-black text-blue-600" required />
                            <x-input-error :messages="$errors->get('stock')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="min_stock_level" value="Safety Stock Level" />
                            <x-text-input :value="old('min_stock_level')" name="min_stock_level" type="number" step="0.01" value="1.00" class="mt-1 block w-full font-black text-red-500" required />
                            <x-input-error :messages="$errors->get('min_stock_level')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="cost" value="Unit Cost (Estimated)" />
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">$</span>
                                </div>
                                <input :value="old('cost')" type="number" name="cost" step="0.01" value="0.00" class="block w-full pl-7 border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500 font-bold" required />
                            </div>
                            <x-input-error :messages="$errors->get('cost')" class="mt-2" />
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end pt-4 pb-12">
                    <x-secondary-button x-on:click="window.history.back()" class="mr-3 py-4 px-8 border-none text-gray-400">Discard</x-secondary-button>
                    <button type="submit" class="bg-gray-900 border-b-4 border-gray-950 hover:bg-black text-white font-black py-4 px-12 rounded-2xl shadow-2xl transition transform active:scale-95 active:border-b-0">
                        Finalize & Register Item
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
