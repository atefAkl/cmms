<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Warehouse Stock Tracking') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow xl:rounded-2xl border border-gray-100 overflow-hidden">
                
                <div class="p-6 border-b border-gray-200 bg-gray-50/50 flex flex-col md:flex-row justify-between items-center gap-4">
                    <form method="GET" action="{{ route('warehouse-stocks.index') }}" class="flex w-full md:w-auto gap-3 items-center">
                        <label class="text-sm font-medium text-gray-700 shrink-0">Filter by Building/Warehouse:</label>
                        <select name="warehouse_id" onchange="this.form.submit()" class="block w-full md:w-64 rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="">-- All Warehouses --</option>
                            @foreach($warehouses as $warehouse)
                                <option value="{{ $warehouse->id }}" {{ $warehouseId == $warehouse->id ? 'selected' : '' }}>
                                    {{ $warehouse->name }}
                                </option>
                            @endforeach
                        </select>
                    </form>

                    <div class="text-sm text-gray-500">
                        Showing actual physical stock across matched facilities
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-indigo-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold text-indigo-800 uppercase tracking-wider">Item Name / Category</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-indigo-800 uppercase tracking-wider">SKU / Ref</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-indigo-800 uppercase tracking-wider">Warehouse Assigned</th>
                                <th class="px-6 py-4 text-right text-xs font-bold text-indigo-800 uppercase tracking-wider">On-Hand Qty</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($stocks as $stock)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-gray-900">
                                            {{ $stock->item->name ?? 'Unknown Item' }}
                                        </div>
                                        <div class="text-xs text-gray-500 mt-0.5">
                                            {{ optional($stock->item->category)->name ?? 'Uncategorized' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-mono">
                                        {{ $stock->item->reference_number ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                            {{ $stock->warehouse->name ?? 'Unknown Warehouse' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <div class="text-lg font-black {{ $stock->current_stock <= ($stock->item->min_stock_level ?? 0) ? 'text-red-600' : 'text-green-600' }}">
                                            {{ number_format($stock->current_stock, 2) }}
                                        </div>
                                        <div class="text-xs text-gray-500 uppercase">{{ $stock->item->uom ?? 'units' }}</div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center">
                                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                        </div>
                                        <h3 class="text-lg font-medium text-gray-900 text-center">No Stock Found</h3>
                                        <p class="mt-1 text-sm text-gray-500 text-center">There are no approved purchase orders adding inventory to the system yet.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($stocks->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $stocks->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
