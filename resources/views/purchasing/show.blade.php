<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Purchase Order Details') }} #{{ $purchaseOrder->reference_number }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('purchasing.index') }}" class="bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium py-2 px-4 rounded-xl shadow-sm transition">
                    &larr; Back
                </a>
                @if($purchaseOrder->approval_status === 'pending')
                <form action="{{ route('purchasing.approve', $purchaseOrder->id) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded-xl shadow transition" onclick="return confirm('Approve this Purchase Order? This will permanently add stock to the warehouse.')">
                        <svg class="w-5 h-5 inline mr-1 -mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Approve & Receive Inventory
                    </button>
                </form>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm" role="alert">
                    <p class="font-bold">Success</p>
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm" role="alert">
                    <p class="font-bold">Error</p>
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            <div class="bg-white shadow xl:rounded-2xl border border-gray-100 overflow-hidden mb-8">
                <div class="px-6 py-5 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        Purchase Information
                    </h3>
                    <div>
                        @if($purchaseOrder->approval_status === 'approved')
                            <span class="px-4 py-2 inline-flex text-sm leading-5 font-bold rounded-full bg-green-100 text-green-800 border border-green-200">
                                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Inventory Received
                            </span>
                        @else
                            <span class="px-4 py-2 inline-flex text-sm leading-5 font-bold rounded-full bg-yellow-100 text-yellow-800 border border-yellow-200">
                                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Pending Approval
                            </span>
                        @endif
                    </div>
                </div>
                <div class="px-6 py-5 grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div>
                        <div class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-1">Supplier</div>
                        <div class="text-base text-gray-900 font-semibold">{{ $purchaseOrder->supplier->name ?? 'N/A' }}</div>
                    </div>
                    <div>
                        <div class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-1">Destination Warehouse</div>
                        <div class="text-base text-gray-900 font-semibold flex items-center">
                            <svg class="w-5 h-5 text-indigo-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            {{ $purchaseOrder->warehouse->name ?? 'N/A' }}
                        </div>
                    </div>
                    <div>
                        <div class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-1">Transaction Date</div>
                        <div class="text-base text-gray-900">{{ \Carbon\Carbon::parse($purchaseOrder->transaction_date)->format('M d, Y') }}</div>
                    </div>
                    <div>
                        <div class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-1">Notes</div>
                        <div class="text-sm text-gray-700">{{ $purchaseOrder->notes ?: 'No notes provided.' }}</div>
                    </div>
                </div>
            </div>

            <div class="bg-white shadow xl:rounded-2xl border border-gray-100 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-200">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        Item Details
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider text-center">Reference / SKU</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Qty Received</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Cost</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total Amount</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($purchaseOrder->items as $orderItem)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $orderItem->item->name ?? 'Unknown Item' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center font-mono">
                                        {{ $orderItem->item->reference_number ?? '-----' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right font-bold">
                                        {{ number_format($orderItem->quantity, 2) }} <span class="text-gray-400 font-normal text-xs">{{ $orderItem->item->uom ?? '' }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
                                        $ {{ number_format($orderItem->unit_cost, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right font-bold bg-gray-50/50">
                                        $ {{ number_format($orderItem->quantity * $orderItem->unit_cost, 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50">
                            <tr>
                                <td colspan="4" class="px-6 py-4 whitespace-nowrap text-right text-sm font-bold text-gray-900 uppercase">Grand Total</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-lg font-black text-indigo-700">
                                    $ {{ number_format($purchaseOrder->total_cost, 2) }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
