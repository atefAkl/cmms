<x-app-layout>
    <div class="py-2">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            <x-page-header
                title="{{ $inventoryItem->name }}"
                description="View full item details and custom attributes."
                :backRoute="route('inventory-items.index')"
            />

            {{-- ============================================================ --}}
            {{-- Action Bar                                                    --}}
            {{-- ============================================================ --}}
            <div class="flex items-center gap-3 mb-4">
                <a
                    href="{{ route('inventory-items.edit', $inventoryItem) }}"
                    class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm py-2.5 px-5 rounded-xl shadow transition"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit Item
                </a>

                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $inventoryItem->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-600' }}">
                    {{ $inventoryItem->is_active ? 'Active' : 'Inactive' }}
                </span>

                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600 uppercase">
                    {{ $inventoryItem->type }}
                </span>
            </div>

            <div class="space-y-6">

                {{-- ============================================================ --}}
                {{-- Section 1: Core Identification                               --}}
                {{-- ============================================================ --}}
                <div class="bg-white shadow-md sm:rounded-2xl border border-gray-100 overflow-hidden">
                    <div class="px-6 py-3 bg-gray-200 border-b border-gray-100 flex items-center gap-3">
                        <i class="fa fa-sliders text-gray-600"></i>
                        <h3 class="font-black text-gray-800 uppercase tracking-tighter">1. Core Identification</h3>
                    </div>

                    <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6 bg-emerald-50/20">

                        {{-- Product Image --}}
                        <div class="flex flex-col items-center justify-start">
                            @if($inventoryItem->image)
                                <img
                                    src="{{ Storage::url($inventoryItem->image) }}"
                                    alt="{{ $inventoryItem->name }}"
                                    class="h-36 w-full object-contain rounded-xl border border-gray-200 bg-gray-50"
                                >
                            @else
                                <div class="h-36 w-full flex items-center justify-center bg-gray-100 rounded-xl border border-dashed border-gray-300">
                                    <i class="fa fa-file-image fa-3x text-gray-300"></i>
                                </div>
                            @endif
                        </div>

                        {{-- Core Fields --}}
                        <div class="md:col-span-2 grid grid-cols-2 gap-4">
                            <div class="col-span-2">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-1">Item Name</label>
                                <p class="font-black text-gray-900 text-lg border-l-4 border-emerald-500 pl-3 leading-tight">{{ $inventoryItem->name }}</p>
                            </div>
                            <div>
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-1">Brand / Manufacturer</label>
                                <p class="font-bold text-gray-800 text-sm">{{ $inventoryItem->brand ?? '—' }}</p>
                            </div>
                            <div>
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-1">Category</label>
                                <p class="font-bold text-gray-800 text-sm">{{ $inventoryItem->category?->name ?? '—' }}</p>
                            </div>
                            <div>
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-1">Reference #</label>
                                <p class="font-mono text-sm text-gray-700">{{ $inventoryItem->reference_number ?? '—' }}</p>
                            </div>
                            <div>
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-1">Part #</label>
                                <p class="font-mono text-sm text-gray-700">{{ $inventoryItem->part_number ?? '—' }}</p>
                            </div>
                            <div>
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-1">Model #</label>
                                <p class="font-mono text-sm text-gray-700">{{ $inventoryItem->model_number ?? '—' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ============================================================ --}}
                {{-- Section 2: Technical Specs                                   --}}
                {{-- ============================================================ --}}
                @if($inventoryItem->tech_specs && count(array_filter($inventoryItem->tech_specs)))
                <div class="bg-white shadow-md sm:rounded-2xl border border-gray-100 overflow-hidden">
                    <div class="px-6 py-3 bg-gray-200 border-b border-gray-100 flex items-center gap-3">
                        <i class="fa fa-power-off text-gray-600"></i>
                        <h3 class="font-black text-gray-800 uppercase tracking-tighter">2. Technical Specifications</h3>
                    </div>
                    <div class="p-6 grid grid-cols-2 md:grid-cols-4 gap-4 bg-emerald-50/20">
                        @foreach($inventoryItem->tech_specs as $specKey => $specValue)
                            @if(!empty($specValue))
                                <div class="bg-white rounded-xl p-3 border border-gray-100 shadow-sm">
                                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">{{ ucfirst($specKey) }}</p>
                                    <p class="text-sm font-bold text-gray-800">{{ $specValue }}</p>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- ============================================================ --}}
                {{-- Section 3: Inventory Controls                                --}}
                {{-- ============================================================ --}}
                <div class="bg-slate-200 shadow-md sm:rounded-2xl border border-gray-100 overflow-hidden">
                    <div class="px-6 py-3 bg-slate-100 border-b border-gray-100 flex items-center gap-3">
                        <i class="fa fa-dollar-sign text-gray-600"></i>
                        <h3 class="font-black text-gray-800 uppercase tracking-tighter">3. Inventory Controls</h3>
                    </div>
                    <div class="p-6 grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="bg-white rounded-xl p-4 border border-gray-100 shadow-sm text-center">
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Current Stock</p>
                            <p class="text-2xl font-black text-blue-600">{{ number_format($inventoryItem->stock, 2) }}</p>
                            <p class="text-xs text-gray-400 uppercase">{{ $inventoryItem->uom }}</p>
                        </div>
                        <div class="bg-white rounded-xl p-4 border border-gray-100 shadow-sm text-center">
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Min. Stock</p>
                            <p class="text-2xl font-black {{ $inventoryItem->stock <= $inventoryItem->min_stock_level ? 'text-red-500' : 'text-gray-700' }}">
                                {{ number_format($inventoryItem->min_stock_level, 2) }}
                            </p>
                            <p class="text-xs text-gray-400 uppercase">{{ $inventoryItem->uom }}</p>
                        </div>
                        <div class="bg-white rounded-xl p-4 border border-gray-100 shadow-sm text-center">
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Unit Cost</p>
                            <p class="text-2xl font-black text-emerald-600">{{ number_format($inventoryItem->cost, 2) }}</p>
                            <p class="text-xs text-gray-400">per {{ $inventoryItem->uom }}</p>
                        </div>
                        <div class="bg-white rounded-xl p-4 border border-gray-100 shadow-sm text-center">
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Total Value</p>
                            <p class="text-2xl font-black text-gray-800">{{ number_format($inventoryItem->stock * $inventoryItem->cost, 2) }}</p>
                            <p class="text-xs text-gray-400">estimated</p>
                        </div>
                    </div>
                </div>

                {{-- ============================================================ --}}
                {{-- Section 4: Custom Attributes                                 --}}
                {{-- Only rendered when the item has saved attributes.            --}}
                {{-- ============================================================ --}}
                @if($inventoryItem->attributes && count($inventoryItem->attributes) > 0)
                <div class="bg-white shadow-md sm:rounded-2xl border border-teal-100 overflow-hidden">
                    <div class="px-6 py-3 bg-teal-50 border-b border-teal-100 flex items-center gap-3">
                        <div class="bg-teal-600 p-1.5 rounded-lg text-white">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                        </div>
                        <h3 class="font-black text-gray-800 uppercase tracking-tighter">4. Custom Attributes</h3>
                        <span class="ml-auto text-xs text-teal-600 font-semibold bg-teal-100 px-2 py-0.5 rounded-full">
                            {{ count($inventoryItem->attributes) }} {{ Str::plural('attribute', count($inventoryItem->attributes)) }}
                        </span>
                    </div>

                    <div class="p-6">
                        {{-- Header row --}}
                        <div class="grid grid-cols-12 gap-3 text-xs font-bold text-gray-400 uppercase tracking-widest px-3 pb-2 border-b border-gray-100 mb-2">
                            <div class="col-span-4">Attribute</div>
                            <div class="col-span-5">Value</div>
                            <div class="col-span-3">Unit</div>
                        </div>

                        {{-- Attribute rows --}}
                        @foreach($inventoryItem->attributes as $attrKey => $attrData)
                            <div class="grid grid-cols-12 gap-3 items-center px-3 py-3 rounded-xl {{ $loop->even ? 'bg-gray-50' : '' }}">
                                {{-- Key --}}
                                <div class="col-span-4">
                                    <span class="font-bold text-gray-700 text-sm" dir="auto">{{ $attrKey }}</span>
                                </div>
                                {{-- Value --}}
                                <div class="col-span-5">
                                    <span class="text-sm text-gray-600 font-medium" dir="auto">{{ $attrData[0] ?? '—' }}</span>
                                </div>
                                {{-- Unit (optional — only shown if present) --}}
                                <div class="col-span-3">
                                    @if(!empty($attrData[1]))
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-700" dir="auto">
                                            {{ $attrData[1] }}
                                        </span>
                                    @else
                                        <span class="text-gray-300 text-sm">—</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

            </div>{{-- end space-y-6 --}}

            <div class="py-8"></div>

        </div>
    </div>
</x-app-layout>
