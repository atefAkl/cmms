<x-app-layout>
    <div class="py-2">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <x-page-header 
                title="{{ __('Register Industrial Item') }}" 
                description="Define technical specifications and initial stock levels."
                :backRoute="route('inventory-items.index')"
            />

            <form action="{{ route('inventory-items.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                @csrf
                
                <!-- Section 1: Core Identification -->
                <div class="bg-white mb-3 shadow-md sm:rounded-2xl border border-gray-100 overflow-hidden">
                    <div class="px-6 py-3 bg-gray-200 border-b border-gray-100 flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <i class="fa fa-sliders"></i>
                            <h3 class="font-black text-gray-800 uppercase tracking-tighter">1. Core Identification</h3>
                        </div>
                    </div>
                    <!-- Section 1: 3 columns -->
                    <div class="p-8 grid grid-cols-1 md:grid-cols-3 gap-6 bg-emerald-50/20">
                        
                    <div class="col-span-2">
                        <div class="grid grid-cols-2 gap-6">
                            <div class="col-span-1">
                                <x-input-label for="name" value="Item / Product Name" />
                                <x-text-input :value="old('name')" name="name" type="text" placeholder="e.g. Compressor Bitzer 4DC-5.2Y" class="mt-1 block w-full text-sm font-bold" required />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <div class="col-span-1">
                                <x-input-label for="brand" value="Brand / Manufacturer" />
                                <x-text-input :value="old('brand')" name="brand" type="text" placeholder="e.g. Bitzer, Danfoss, Copeland" class="mt-1 block w-full text-sm" />
                                <x-input-error :messages="$errors->get('brand')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="category_id" value="Item Category" />
                                <select :value="old('category_id')" name="category_id" class="mt-1 block w-full text-sm border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                    <option value="" disabled selected>Select Category</option>
                                    @foreach($categories as $category)
                                        <option {{ old('category_id') == $category->id ? 'selected' : '' }} value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="type" value="Item Classification" />
                                <select :value="old('type')" name="type" class="mt-1 block w-full text-sm border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    @foreach($types as $type)
                                        <option value="{{ $type }}">{{ ucfirst($type) }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('type')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="reference_number" value="Internal Asset / SKU Ref" />
                                <x-text-input :value="old('reference_number')" name="reference_number" type="text" placeholder="INV-REF-001" class="mt-1 block w-full text-sm font-mono" />
                                <x-input-error :messages="$errors->get('reference_number')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="part_number" value="Manufacturer Part #" />
                                <x-text-input :value="old('part_number')" name="part_number" type="text" placeholder="PN-9988-77" class="mt-1 block w-full text-sm font-mono" />
                                <x-input-error :messages="$errors->get('part_number')" class="mt-2" />
                            </div>
                        </div>
                    </div>

                    <div class="col-span-1">
                        <x-input-label for="image" value="Product Image" />
                        <div class="mt-2 flex items-center space-x-4">
                            <div id="image-preview" style="max-height: 10rem" class="rounded-xmd bg-gray-100 border-2 border-dashed border-gray-300 flex items-center justify-center overflow-hidden">
                                <i class="fa fa-file-image p-3 fa-5x text-gray-400 cursor-pointer"></i>
                            </div>
                        </div>
                        <input type="file" name="image" onchange="previewImage(this)" class="block w-full text-xs text-gray-500 mt-2"/>
                        <x-input-error :messages="$errors->get('image')" class="mt-2" />
                        <script>
                            function previewImage(input) {
                                if (input.files && input.files[0]) {
                                    var reader = new FileReader();
                                    reader.onload = function(e) {
                                        document.getElementById('image-preview').innerHTML = '<img src="' + e.target.result + '" class="h-full w-full object-fit">';
                                    };
                                    reader.readAsDataURL(input.files[0]);
                                }
                            }
                        </script>
                    </div>
                        
                        

                       
                    <div class="col-span-2">
                        <x-input-label for="description" value="Description" />
                        <x-text-input :value="old('description')" name="description" type="text" placeholder="Brief description of the item" class="mt-1 block w-full text-sm" />
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="model_number" value="Model Number" />
                        <x-text-input :value="old('model_number')" name="model_number" type="text" placeholder="4DC-5.2Y" class="mt-1 block w-full text-sm font-mono" />
                        <x-input-error :messages="$errors->get('model_number')" class="mt-2" />
                    </div>
                        
                    </div>
                </div>

                <!-- Section 2: Technical Specifications (HVAC/Cooling focus) -->
                <div class="bg-white mb-3 shadow-md sm:rounded-2xl border border-gray-100 overflow-hidden">
                    <div class="px-6 py-3 bg-gray-200 border-b border-gray-100 flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <i class="fa fa-power-off"></i>
                            <h3 class="font-black text-gray-800 uppercase tracking-tighter">2. Technical Specifications</h3>
                        </div>
                    </div>
                    <div class="p-8 grid grid-cols-1 md:grid-cols-4 gap-6 bg-emerald-50/20">
                        <div>
                            <x-input-label for="tech_specs[refrigerant]" value="Refrigerant Type" />
                            <select name="tech_specs[refrigerant]" class="mt-1 block w-full text-sm border-gray-300 rounded-lg shadow-sm">
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
                            <select name="tech_specs[voltage]" class="mt-1 block w-full text-sm border-gray-300 rounded-lg shadow-sm">
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
                            <select name="tech_specs[phase]" class="mt-1 block w-full text-sm border-gray-300 rounded-lg shadow-sm">
                                <option value="">N/A</option>
                                <option {{ old('tech_specs.phase') == '1-Phase' ? 'selected' : '' }} value="1-Phase">Single Phase</option>
                                <option {{ old('tech_specs.phase') == '3-Phase' ? 'selected' : '' }} value="3-Phase">Three Phase</option>
                            </select>
                            <x-input-error :messages="$errors->get('tech_specs.phase')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="tech_specs[capacity]" value="Capacity (HP or BTU)" />
                            <x-text-input :value="old('tech_specs.capacity')" name="tech_specs[capacity]" type="text" placeholder="e.g. 5 HP / 30,000 BTU" class="mt-1 block w-full text-sm" />
                            <x-input-error :messages="$errors->get('tech_specs.capacity')" class="mt-2" />
                        </div>

                        <div class="md:col-span-2">
                            <x-input-label for="tech_specs[dimensions]" value="Dimensions (cm)" />
                            <x-text-input :value="old('tech_specs.dimensions')" name="tech_specs[dimensions]" type="text" placeholder="L x W x H" class="mt-1 block w-full text-sm font-mono" />
                            <x-input-error :messages="$errors->get('tech_specs.dimensions')" class="mt-2" />
                        </div>

                        <div class="md:col-span-2">
                            <x-input-label for="tech_specs[weight]" value="Weight (Kg)" />
                            <x-text-input :value="old('tech_specs.weight')" name="tech_specs[weight]" type="number" step="0.1" placeholder="0.00" class="mt-1 block w-full text-sm" />
                            <x-input-error :messages="$errors->get('tech_specs.weight')" class="mt-2" />
                        </div>
                    </div>
                </div>
            
                    <!-- Section 3: 4 columns -->
                    <!-- Section 2: Technical Specifications (HVAC/Cooling focus) -->
                <div class="bg-white shadow-md sm:rounded-2xl border border-gray-100 overflow-hidden">
                    <div class="px-6 py-3 bg-gray-200 border-b border-gray-100 flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <i class="fa fa-dollar-sign"></i>
                            <h3 class="font-black text-gray-800 uppercase tracking-tighter">3. Initial Stock Levels</h3>
                        </div>
                    </div>
                    <div class="p-8 grid grid-cols-1 md:grid-cols-4 gap-6 bg-emerald-50/20">
                        <div>
                            <x-input-label for="uom" value="UoM" />
                            <select name="uom" class="mt-1 block w-full text-sm border-gray-300 rounded-lg shadow-sm" required>
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
                            <x-text-input :value="old('stock')" name="stock" type="number" step="0.01" value="0.00" class="mt-1 block w-full text-sm font-black text-blue-600" required />
                            <x-input-error :messages="$errors->get('stock')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="min_stock_level" value="Safety Stock Level" />
                            <x-text-input :value="old('min_stock_level')" name="min_stock_level" type="number" step="0.01" value="1.00" class="mt-1 block w-full text-sm font-black text-red-500" required />
                            <x-input-error :messages="$errors->get('min_stock_level')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="cost" value="Unit Cost (Estimated)" />
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">$</span>
                                </div>
                                <input :value="old('cost')" type="number" name="cost" step="0.01" value="0.00" class="block w-full pl-7 text-sm border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500 font-bold" required />
                            </div>
                            <x-input-error :messages="$errors->get('cost')" class="mt-2" />
                        </div>
                    </div>
                </div>

                {{-- ============================================================ --}}
                {{-- Section 4: Custom / Dynamic Attributes                        --}}
                {{-- Alpine.js manages a dynamic array of {key, value, unit} rows. --}}
                {{-- The rows are serialised into the hidden `attributes_json` field --}}
                {{-- before the form is submitted.                                  --}}
                {{-- ============================================================ --}}
                <div
                    class="bg-white mb-3 shadow-md sm:rounded-2xl border border-gray-100 overflow-hidden"
                    x-data="{
                        attrs: [{ key: '', value: '', unit: '' }],

                        addRow() {
                            this.attrs.push({ key: '', value: '', unit: '' });
                        },

                        removeRow(index) {
                            if (this.attrs.length > 1) {
                                this.attrs.splice(index, 1);
                            } else {
                                // Keep at least one empty row for UX clarity
                                this.attrs = [{ key: '', value: '', unit: '' }];
                            }
                        }
                    }"
                >
                    {{-- Section header (same style as other sections) --}}
                    <div class="px-6 py-3 bg-gray-200 border-b border-gray-100 flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <i class="fa fa-tags"></i>
                            <h3 class="font-black text-gray-800 uppercase tracking-tighter">4. Custom Attributes</h3>
                        </div>
                        {{-- "+" Add Row button --}}
                        <button
                            type="button"
                            @click="addRow()"
                            class="flex items-center gap-1 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs py-1.5 px-4 rounded-full transition"
                        >
                            <i class="fa fa-plus text-xs"></i>
                            Add Attribute
                        </button>
                    </div>

                    <div class="p-6 space-y-3 bg-emerald-50/20">
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
                                        class="block w-full text-sm border-gray-300 rounded-lg shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                                        dir="auto"
                                    />
                                </div>
                                {{-- Value --}}
                                <div class="col-span-4">
                                    <input
                                        type="text"
                                        x-model="attr.value"
                                        placeholder="e.g. 5"
                                        class="block w-full text-sm border-gray-300 rounded-lg shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
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
                    <x-secondary-button x-on:click="window.history.back()" class="mr-3 py-4 px-8 border-none text-gray-400">Discard</x-secondary-button>
                    <button type="submit" class="bg-gray-900 border-b-4 border-gray-950 hover:bg-black text-white font-black py-4 px-12 rounded-2xl shadow-2xl transition transform active:scale-95 active:border-b-0">
                        Finalize & Register Item
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
