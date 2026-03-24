<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Record New Purchase Order') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="purchaseOrderForm()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow border border-gray-100 sm:rounded-2xl p-8 relative">
                
                @if(session('error'))
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                        {{ session('error') }}
                    </div>
                @endif
                
                @if($errors->any())
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>- {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('purchasing.store') }}" method="POST">
                    @csrf
                    
                    <div class="flex flex-col md:flex-row gap-6 mb-8 border-b pb-6">
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-700">Supplier</label>
                            <select name="supplier_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option value="">Select Supplier</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-700">Warehouse Destination</label>
                            <select name="warehouse_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option value="">Select Warehouse</option>
                                @foreach($warehouses as $warehouse)
                                    <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="w-full md:w-1/4">
                            <label class="block text-sm font-medium text-gray-700">Date</label>
                            <input type="date" name="transaction_date" value="{{ date('Y-m-d') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        </div>
                    </div>

                    <div class="mb-6 flex justify-between items-center bg-gray-50 p-4 rounded-lg border border-gray-100">
                        <h3 class="text-lg font-bold text-gray-800">Purchased Items</h3>
                        <a href="{{ route('inventory-items.create') }}" target="_blank" class="text-sm text-indigo-600 font-semibold hover:text-indigo-800 flex items-center gap-1 bg-indigo-50 px-3 py-1.5 rounded-full transition-colors border border-indigo-100">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Define New Product
                        </a>
                    </div>
                    
                    <div class="bg-white rounded-lg border overflow-hidden mb-6">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">Qty</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-40">Unit Cost</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-40">Total</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider w-16"></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <template x-for="(row, index) in items" :key="index">
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <select x-model="row.inventory_item_id" :name="`items[${index}][inventory_item_id]`" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                                                <option value="" disabled selected>Select Product...</option>
                                                @foreach($items as $item)
                                                    <option value="{{ $item->id }}">{{ $item->name }} ({{ $item->reference_number ?? 'No Ref' }})</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <input type="number" x-model="row.quantity" :name="`items[${index}][quantity]`" step="0.01" min="0.01" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap relative">
                                            <div class="pointer-events-none absolute inset-y-0 left-0 pl-9 flex items-center">
                                                <span class="text-gray-500 sm:text-sm">$</span>
                                            </div>
                                            <input type="number" x-model="row.unit_cost" :name="`items[${index}][unit_cost]`" step="0.01" min="0" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm pl-7" required>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900 font-semibold" x-text="'$ ' + (row.quantity * row.unit_cost).toFixed(2)"></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <button type="button" @click="removeItem(index)" class="text-red-600 hover:text-red-900 focus:outline-none" :disabled="items.length === 1">
                                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                        
                        <div class="bg-gray-50 px-6 py-3 flex justify-between items-center border-t border-gray-200">
                            <button type="button" @click="addItem()" class="text-sm font-medium text-indigo-600 hover:text-indigo-500 focus:outline-none flex items-center">
                                <svg class="h-5 w-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                Add Another Item
                            </button>
                            
                            <div class="text-lg font-bold text-gray-900">
                                Grand Total: <span x-text="'$ ' + calculateTotal()"></span>
                            </div>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700">Notes / Invoice Reference</label>
                        <textarea name="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                    </div>

                    <div class="flex justify-end gap-3 pt-6 border-t border-gray-100">
                        <a href="{{ route('purchasing.index') }}" class="px-6 py-3 bg-white border border-gray-300 rounded-xl shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Cancel
                        </a>
                        <button type="submit" class="inline-flex justify-center px-6 py-3 border border-transparent text-sm font-medium rounded-xl shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Create Purchase Order
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('purchaseOrderForm', () => ({
                items: [
                    { inventory_item_id: '', quantity: 1, unit_cost: 0 }
                ],
                addItem() {
                    this.items.push({ inventory_item_id: '', quantity: 1, unit_cost: 0 });
                },
                removeItem(index) {
                    if (this.items.length > 1) {
                        this.items.splice(index, 1);
                    }
                },
                calculateTotal() {
                    let total = 0;
                    this.items.forEach(item => {
                        total += (parseFloat(item.quantity) || 0) * (parseFloat(item.unit_cost) || 0);
                    });
                    return total.toFixed(2);
                }
            }))
        })
    </script>
</x-app-layout>
