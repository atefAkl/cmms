<x-app-layout>
    <div class="py-2">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <x-page-header 
                title="{{ __('Update Item:') }} {{ $inventoryItem->name }}" 
                description="Modify industrial specifications or stock control parameters."
                :backRoute="route('inventory-items.index')"
            />

            <form action="{{ route('inventory-items.update', $inventoryItem) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                @csrf
                @method('PATCH')
                
                <!-- Section 1: Core Identification -->
                <div class="bg-white shadow-xl sm:rounded-2xl border border-gray-100 overflow-hidden">
                    <div class="px-8 py-5 bg-gray-50 border-b border-gray-100 flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="bg-blue-600 p-2 rounded-lg text-white">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </div>
                            <h3 class="font-black text-gray-800 uppercase tracking-tighter">1. Core Identification</h3>
                        </div>
                        <div class="flex items-center">
                             <x-input-label for="is_active" value="Active Status" class="mr-3 mb-0" />
                             <select name="is_active" class="text-xs font-bold py-1 px-3 border-gray-300 rounded-full">
                                <option value="1" {{ $inventoryItem->is_active ? 'selected' : '' }}>Enabled</option>
                                <option value="0" {{ !$inventoryItem->is_active ? 'selected' : '' }}>Disabled</option>
                             </select>
                        </div>
                    </div>
                    
                    <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                        <div class="col-span-1">
                            <x-input-label for="image" value="Product Image" />
                            <div class="mt-2 flex items-center space-x-6">
                                <div class="shrink-0">
                                    <div id="image-preview" class="h-24 w-24 object-cover rounded-xl bg-gray-100 border-2 border-dashed border-gray-300 flex items-center justify-center overflow-hidden">
                                        @if($inventoryItem->image)
                                            <img src="{{ Storage::url($inventoryItem->image) }}" class="h-full w-full object-cover">
                                        @else
                                            <svg class="h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        @endif
                                    </div>
                                </div>
                                <label class="block">
                                    <span class="sr-only">Choose product photo</span>
                                    <input type="file" name="image" onchange="previewImage(this)" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"/>
                                </label>
                            </div>
                            <x-input-error :messages="$errors->get('image')" class="mt-2" />
                        </div>

                        <div class="col-span-1">
                            <!-- Placeholder for layout balance -->
                        </div>

                        <script>
                            function previewImage(input) {
                                if (input.files && input.files[0]) {
                                    var reader = new FileReader();
                                    reader.onload = function(e) {
                                        document.getElementById('image-preview').innerHTML = '<img src="' + e.target.result + '" class="h-full w-full object-cover">';
                                    };
                                    reader.readAsDataURL(input.files[0]);
                                }
                            }
                        </script>
                        <div class="col-span-2">
                            <x-input-label for="name" value="Item / Product Name" />
                            <x-text-input name="name" type="text" :value="$inventoryItem->name" class="mt-1 block w-full text-lg font-bold" required />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="brand" value="Brand / Manufacturer" />
                            <x-text-input name="brand" type="text" :value="$inventoryItem->brand" class="mt-1 block w-full" />
                            <x-input-error :messages="$errors->get('brand')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="category_id" value="Item Category" />
                            <select name="category_id" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ $inventoryItem->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="reference_number" value="Internal Asset / SKU Ref" />
                            <x-text-input name="reference_number" type="text" :value="$inventoryItem->reference_number" class="mt-1 block w-full font-mono text-sm" />
                            <x-input-error :messages="$errors->get('reference_number')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="part_number" value="Manufacturer Part #" />
                            <x-text-input name="part_number" type="text" :value="$inventoryItem->part_number" class="mt-1 block w-full font-mono text-sm" />
                            <x-input-error :messages="$errors->get('part_number')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="model_number" value="Model Number" />
                            <x-text-input name="model_number" type="text" :value="$inventoryItem->model_number" class="mt-1 block w-full font-mono text-sm" />
                            <x-input-error :messages="$errors->get('model_number')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="type" value="Item Classification" />
                            <select name="type" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                @foreach($types as $type)
                                    <option value="{{ $type }}" {{ $inventoryItem->type === $type ? 'selected' : '' }}>{{ ucfirst($type) }}</option>
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
                    
                    @php $specs = $inventoryItem->tech_specs ?? []; @endphp
                    <div class="p-8 grid grid-cols-1 md:grid-cols-3 gap-x-8 gap-y-6">
                        <div>
                            <x-input-label for="tech_specs[refrigerant]" value="Refrigerant Type" />
                            <select name="tech_specs[refrigerant]" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm">
                                <option value="">N/A</option>
                                @foreach(['R404A', 'R134a', 'R448A', 'R22', 'R290'] as $r)
                                    <option value="{{ $r }}" {{ ($specs['refrigerant'] ?? '') === $r ? 'selected' : '' }}>{{ $r }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <x-input-label for="tech_specs[voltage]" value="Voltage" />
                            <select name="tech_specs[voltage]" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm">
                                <option value="">N/A</option>
                                @foreach(['220-240V', '380-420V', '460V', '12V/24V DC'] as $v)
                                    <option value="{{ $v }}" {{ ($specs['voltage'] ?? '') === $v ? 'selected' : '' }}>{{ $v }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('tech_specs.voltage')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="tech_specs[phase]" value="Phase" />
                            <select name="tech_specs[phase]" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm">
                                <option value="">N/A</option>
                                <option value="1-Phase" {{ ($specs['phase'] ?? '') === '1-Phase' ? 'selected' : '' }}>Single Phase</option>
                                <option value="3-Phase" {{ ($specs['phase'] ?? '') === '3-Phase' ? 'selected' : '' }}>Three Phase</option>
                            </select>
                            <x-input-error :messages="$errors->get('tech_specs.phase')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="tech_specs[capacity]" value="Capacity (HP or BTU)" />
                            <x-text-input name="tech_specs[capacity]" type="text" :value="$specs['capacity'] ?? ''" placeholder="e.g. 5 HP / 30,000 BTU" class="mt-1 block w-full" />
                            <x-input-error :messages="$errors->get('tech_specs.capacity')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="tech_specs[dimensions]" value="Dimensions (cm)" />
                            <x-text-input name="tech_specs[dimensions]" type="text" :value="$specs['dimensions'] ?? ''" placeholder="L x W x H" class="mt-1 block w-full font-mono text-sm" />
                            <x-input-error :messages="$errors->get('tech_specs.dimensions')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="tech_specs[weight]" value="Weight (Kg)" />
                            <x-text-input name="tech_specs[weight]" type="number" step="0.1" :value="$specs['weight'] ?? ''" placeholder="0.00" class="mt-1 block w-full" />
                            <x-input-error :messages="$errors->get('tech_specs.weight')" class="mt-2" />
                        </div>
                    </div>
                </div>

                <!-- Section 3: Inventory Settings -->
                <div class="bg-slate-200 shadow-md sm:rounded-2xl border border-gray-100 overflow-hidden">
                    <div class="px-8 py-5 bg-slate-50 border-b border-gray-100 flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="bg-slate-600 p-2 rounded-lg text-white">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <h3 class="font-black text-gray-800 uppercase tracking-tighter">3. Inventory Controls</h3>
                        </div>
                    </div>
                    
                    <div class="p-8 grid grid-cols-3 md:grid-cols-3 gap-6 bg-emerald-50/20">
                        <div>
                            <x-input-label for="uom" value="UoM" />
                            <select name="uom" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm" required>
                                <option value="unit" {{ $inventoryItem->uom === 'unit' ? 'selected' : '' }}>Piece / Unit</option>
                                <option value="kg" {{ $inventoryItem->uom === 'kg' ? 'selected' : '' }}>Kilogram (Kg)</option>
                                <option value="liter" {{ $inventoryItem->uom === 'liter' ? 'selected' : '' }}>Liter (L)</option>
                                <option value="drum" {{ $inventoryItem->uom === 'drum' ? 'selected' : '' }}>Drum</option>
                                <option value="meter" {{ $inventoryItem->uom === 'meter' ? 'selected' : '' }}>Meter (m)</option>
                            </select>
                            <x-input-error :messages="$errors->get('uom')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="min_stock_level" value="Safety Stock Level" />
                            <x-text-input name="min_stock_level" type="number" step="0.01" :value="$inventoryItem->min_stock_level" class="mt-1 block w-full font-black text-red-500" required />
                            <x-input-error :messages="$errors->get('min_stock_level')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="cost" value="Unit Cost (Estimated)" />
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">$</span>
                                </div>
                                <x-text-input name="cost" type="number" step="0.01" :value="$inventoryItem->cost" class="mt-1 block w-full pl-7 border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500 font-bold" required />
                                <x-input-error :messages="$errors->get('cost')" class="mt-2" />
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ============================================================ --}}
                {{-- Section 4: Custom / Dynamic Attributes                        --}}
                {{-- Pre-populate from the stored attributes JSON (edit mode).     --}}
                {{-- ============================================================ --}}
                @php
                    // Convert stored {key:[value,unit?]} → [{key,value,unit}] for Alpine.js
                    $storedAttributes = $inventoryItem->attributes ?? [];
                    $initialAttrs = [];
                    foreach ($storedAttributes as $attrKey => $attrData) {
                        $initialAttrs[] = [
                            'key'   => $attrKey,
                            'value' => $attrData[0] ?? '',
                            'unit'  => $attrData[1] ?? '',
                        ];
                    }
                    // Always show at least one empty row if no attributes saved yet
                    if (empty($initialAttrs)) {
                        $initialAttrs = [['key' => '', 'value' => '', 'unit' => '']];
                    }
                @endphp

                <div
                    class="bg-white shadow-xl sm:rounded-2xl border border-gray-100 overflow-hidden"
                    x-data="{
                        attrs: {{ Js::from($initialAttrs) }},

                        addRow() {
                            this.attrs.push({ key: '', value: '', unit: '' });
                        },

                        removeRow(index) {
                            if (this.attrs.length > 1) {
                                this.attrs.splice(index, 1);
                            } else {
                                this.attrs = [{ key: '', value: '', unit: '' }];
                            }
                        }
                    }"
                >
                    {{-- Section header --}}
                    <div class="px-8 py-5 bg-gray-50 border-b border-gray-100 flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="bg-teal-600 p-2 rounded-lg text-white">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                </svg>
                            </div>
                            <h3 class="font-black text-gray-800 uppercase tracking-tighter">4. Custom Attributes</h3>
                        </div>
                        {{-- "+" Add Row button --}}
                        <button
                            type="button"
                            @click="addRow()"
                            class="flex items-center gap-1 bg-teal-600 hover:bg-teal-700 text-white font-bold text-xs py-1.5 px-4 rounded-full transition"
                        >
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/>
                            </svg>
                            Add Attribute
                        </button>
                    </div>

                    <div class="p-8 space-y-3 bg-emerald-50/20">
                        {{-- Column headers --}}
                        <div class="grid grid-cols-12 gap-3 text-xs font-bold text-gray-500 uppercase tracking-widest px-1">
                            <div class="col-span-4">Attribute Key</div>
                            <div class="col-span-4">Value</div>
                            <div class="col-span-3">Unit <span class="text-gray-300 font-normal">(optional)</span></div>
                            <div class="col-span-1"></div>
                        </div>

                        {{-- Dynamic rows --}}
                        <template x-for="(attr, index) in attrs" :key="index">
                            <div class="grid grid-cols-12 gap-3 items-center">
                                {{-- Key --}}
                                <div class="col-span-4">
                                    <input
                                        type="text"
                                        x-model="attr.key"
                                        placeholder="e.g. القوة"
                                        class="block w-full text-sm border-gray-300 rounded-lg shadow-sm focus:border-teal-500 focus:ring-teal-500"
                                        dir="auto"
                                    />
                                </div>
                                {{-- Value --}}
                                <div class="col-span-4">
                                    <input
                                        type="text"
                                        x-model="attr.value"
                                        placeholder="e.g. 5"
                                        class="block w-full text-sm border-gray-300 rounded-lg shadow-sm focus:border-teal-500 focus:ring-teal-500"
                                        dir="auto"
                                    />
                                </div>
                                {{-- Unit (optional) --}}
                                <div class="col-span-3">
                                    <input
                                        type="text"
                                        x-model="attr.unit"
                                        placeholder="e.g. حصان"
                                        class="block w-full text-sm border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        dir="auto"
                                    />
                                </div>
                                {{-- Remove row button --}}
                                <div class="col-span-1 flex justify-center">
                                    <button
                                        type="button"
                                        @click="removeRow(index)"
                                        class="text-red-400 hover:text-red-600 transition text-lg font-bold leading-none"
                                        title="Remove row"
                                    >✕</button>
                                </div>
                            </div>
                        </template>

                        {{-- Hidden field: serialised JSON sent to the controller --}}
                        <input type="hidden" name="attributes_json" :value="JSON.stringify(attrs)">
                    </div>
                </div>

                <div class="flex items-center justify-end pt-4 pb-12">
                    <x-secondary-button x-on:click="window.history.back()" class="mr-3 py-4 px-8 border-none text-gray-400">Cancel</x-secondary-button>
                    <button type="submit" class="bg-indigo-600 border-b-4 border-indigo-900 hover:bg-indigo-700 text-white font-black py-4 px-12 rounded-2xl shadow-2xl transition transform active:scale-95 active:border-b-0">
                        Commit Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
