<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center px-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Purchase Record #{{ $procurement->reference_number ?? str_pad($procurement->id, 5, '0', STR_PAD_LEFT) }}
            </h2>
            <div class="flex space-x-2">
                @if($procurement->approval_status === 'pending')
                    <form action="{{ route('procurement.approve', $procurement) }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-bold shadow transition flex items-center">
                            Approve Order
                        </button>
                    </form>
                    <form action="{{ route('procurement.reject', $procurement) }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-bold shadow transition flex items-center">
                            Reject
                        </button>
                    </form>
                @endif

                @if($procurement->approval_status === 'approved' && $procurement->status === 'pending')
                    <form action="{{ route('procurement.receive', $procurement) }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-bold shadow transition flex items-center">
                            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Mark Received & Update Stock
                        </button>
                    </form>
                @endif
                <button class="bg-white border text-gray-700 px-4 py-2 rounded-lg font-bold shadow-sm transition hover:bg-gray-50">Print PDF</button>
            </div>
        </div>
    </x-slot>

    <div class="py-12 px-4 sm:px-0">
        <div class="max-w-7xl mx-auto space-y-8">
            <!-- Header Info -->
            <div class="grid grid-cols-1 md:grid-cols-5 gap-6 bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-1">Supplier</label>
                    <p class="font-bold text-gray-900 border-l-4 border-indigo-600 pl-3 leading-tight">{{ $procurement->supplier->name ?? 'Miscellaneous' }}</p>
                </div>
                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-1">Destination</label>
                    <p class="font-bold text-gray-900 border-l-4 border-indigo-600 pl-3 leading-tight">{{ $procurement->warehouse->name }}</p>
                </div>
                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-1">Date</label>
                    <p class="font-bold text-gray-900 border-l-4 border-indigo-600 pl-3 leading-tight">{{ $procurement->transaction_date }}</p>
                </div>
                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-1">Approval</label>
                    <span class="inline-flex px-2 py-0.5 text-[10px] font-black uppercase tracking-wider rounded-full {{ $procurement->approval_status === 'approved' ? 'bg-green-100 text-green-700' : ($procurement->approval_status === 'rejected' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                        {{ $procurement->approval_status }}
                    </span>
                </div>
                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-1">Status</label>
                    <span class="inline-flex px-2 py-0.5 text-[10px] font-black uppercase tracking-wider rounded-full {{ $procurement->status === 'received' ? 'bg-blue-100 text-blue-700' : 'bg-orange-100 text-orange-700' }}">
                        {{ $procurement->status }}
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                <!-- Items list -->
                <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 bg-gray-50 border-b border-gray-100 flex justify-between items-center">
                        <h3 class="font-bold text-gray-700 flex items-center">
                            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                            Purchased Items
                        </h3>
                        <span class="text-sm text-gray-500 font-bold uppercase">{{ $procurement->items->count() }} items total</span>
                    </div>
                    <div class="p-0">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="bg-gray-50 text-[10px] font-bold uppercase text-gray-400 tracking-wider">
                                    <th class="p-4 pl-8">Item</th>
                                    <th class="p-4 text-center">Quantity</th>
                                    <th class="p-4 text-right">Unit Price</th>
                                    <th class="p-4 text-right pr-8">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($procurement->items as $item)
                                    <tr class="border-b last:border-0 border-gray-50 font-medium">
                                        <td class="p-4 pl-8">
                                            <div class="flex flex-col">
                                                <span class="text-gray-900 font-bold leading-none mb-1">{{ $item->item->name }}</span>
                                                <span class="text-[10px] text-gray-500 uppercase">{{ $item->item->category->name }}</span>
                                            </div>
                                        </td>
                                        <td class="p-4 text-center">
                                            <span class="px-2 py-1 bg-gray-100 rounded text-gray-900 font-black">{{ $item->quantity }}</span>
                                            <span class="text-xs text-gray-500 ml-1">{{ $item->item->uom }}</span>
                                        </td>
                                        <td class="p-4 text-right text-gray-600 font-bold">{{ number_format($item->unit_cost, 2) }}</td>
                                        <td class="p-4 text-right pr-8 text-gray-900 font-black">{{ number_format($item->quantity * $item->unit_cost, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="bg-gray-900 text-white">
                                    <td colspan="3" class="p-6 pl-8 font-bold uppercase tracking-widest text-right">Grand Total</td>
                                    <td class="p-6 pr-8 text-right font-black text-xl">{{ number_format($procurement->total_cost, 2) }} <small class="text-xs">SAR</small></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <!-- Add Items Form -->
                @if($procurement->status === 'pending')
                    <div class="bg-white p-8 rounded-2xl shadow-xl border-2 border-indigo-100 sticky top-24">
                        <h3 class="font-black text-gray-900 text-lg mb-6 flex items-center">
                            <span class="bg-indigo-600 text-white w-6 h-6 rounded flex items-center justify-center text-xs mr-2">+</span>
                            Attach Item
                        </h3>
                        <form action="{{ route('procurement.addItem', $procurement) }}" method="POST" class="space-y-6 text-sm">
                            @csrf
                            <div>
                                <x-input-label for="inventory_item_id" value="Select Inventory Item" />
                                <select name="inventory_item_id" id="item_select" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">-- Choose Item --</option>
                                    @foreach($inventoryItems as $item)
                                        <option value="{{ $item->id }}" data-uom="{{ $item->uom }}" data-cost="{{ $item->cost }}">
                                            {{ $item->name }} (Ref: {{ $item->reference_number ?? 'N/A' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <x-input-label for="quantity" value="Quantity" />
                                    <div class="relative">
                                        <x-text-input name="quantity" type="number" step="0.01" value="1" class="mt-1 block w-full pr-12 font-black" required />
                                        <span class="absolute right-3 top-3 text-[10px] font-bold text-gray-400 uppercase uom-label">unit</span>
                                    </div>
                                </div>
                                <div>
                                    <x-input-label for="unit_cost" value="Price per Unit" />
                                    <x-text-input name="unit_cost" id="unit_cost_input" type="number" step="0.01" class="mt-1 block w-full font-black" required />
                                </div>
                            </div>

                            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-black py-4 rounded-xl shadow-lg transition duration-150 transform active:scale-95">
                                Add to Order
                            </button>
                        </form>
                        
                        <div class="mt-4 p-4 bg-yellow-50 rounded-lg border border-yellow-100 flex items-start">
                            <svg class="w-5 h-5 text-yellow-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span class="text-[10px] text-yellow-800 leading-tight">Items will be added to global stock only after marking the order as <b>Received</b>.</span>
                        </div>
                    </div>
                @else
                    <div class="bg-indigo-600 p-8 rounded-2xl shadow-xl text-white text-center">
                        <div class="bg-white/20 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold mb-2">Order Completed</h3>
                        <p class="text-indigo-100 text-sm">This purchase record is finalized. All stock levels have been updated accordingly.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        document.getElementById('item_select').addEventListener('change', function() {
            const selected = this.options[this.selectedIndex];
            if (selected.value) {
                document.querySelector('.uom-label').textContent = selected.dataset.uom;
                document.getElementById('unit_cost_input').value = selected.dataset.cost;
            }
        });
    </script>
</x-app-layout>
