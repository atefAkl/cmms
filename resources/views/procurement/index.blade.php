<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center text-center sm:text-left">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Procurement Management') }}
            </h2>
            <a href="{{ route('procurement.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg shadow-lg flex items-center transition">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                New Purchase Record
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex flex-col items-center">
                    <span class="text-gray-500 text-sm uppercase font-bold tracking-wider mb-2">Total Outstanding</span>
                    <span class="text-3xl font-black text-gray-900">{{ number_format($purchases->where('payment_status', 'unpaid')->sum('total_cost'), 2) }} <small class="text-base font-normal">SAR</small></span>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex flex-col items-center">
                    <span class="text-gray-500 text-sm uppercase font-bold tracking-wider mb-2">Pending Orders</span>
                    <span class="text-3xl font-black text-indigo-600">{{ $purchases->where('status', 'pending')->count() }}</span>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex flex-col items-center">
                    <span class="text-gray-500 text-sm uppercase font-bold tracking-wider mb-2">Completed Today</span>
                    <span class="text-3xl font-black text-green-600">{{ $purchases->where('status', 'received')->where('transaction_date', today()->toDateString())->count() }}</span>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100">
                <div class="p-4 sm:p-8">
                    <!-- Desktop Table -->
                    <div class="hidden md:block overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="bg-gray-50 border-b border-gray-100">
                                    <th class="p-4 text-xs font-bold text-gray-400 uppercase tracking-widest">Ref #</th>
                                    <th class="p-4 text-xs font-bold text-gray-400 uppercase tracking-widest">Supplier</th>
                                    <th class="p-4 text-xs font-bold text-gray-400 uppercase tracking-widest text-center">Date</th>
                                    <th class="p-4 text-xs font-bold text-gray-400 uppercase tracking-widest text-center">Status</th>
                                    <th class="p-4 text-xs font-bold text-gray-400 uppercase tracking-widest text-center">Totals</th>
                                    <th class="p-4 text-xs font-bold text-gray-400 uppercase tracking-widest text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($purchases as $order)
                                    <tr class="border-b transition hover:bg-gray-50 border-gray-50">
                                        <td class="p-4 font-bold text-gray-700">#{{ $order->reference_number ?? str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</td>
                                        <td class="p-4">
                                            <div class="flex items-center">
                                                <div class="w-8 h-8 bg-indigo-100 text-indigo-700 rounded-full flex items-center justify-center font-bold mr-3 text-xs">
                                                    {{ substr($order->supplier->name ?? '?', 0, 1) }}
                                                </div>
                                                <span class="text-gray-700 font-medium">{{ $order->supplier->name ?? 'Unknown / Misc' }}</span>
                                            </div>
                                        </td>
                                        <td class="p-4 text-center text-gray-500 text-sm">{{ $order->transaction_date }}</td>
                                        <td class="p-4 text-center">
                                            <div class="flex flex-col gap-1 items-center">
                                                <span class="inline-flex px-2 py-0.5 text-[10px] font-black uppercase tracking-wider rounded-full {{ $order->status === 'received' ? 'bg-green-100 text-green-700' : 'bg-orange-100 text-orange-700' }}">
                                                    {{ $order->status }}
                                                </span>
                                                <span class="text-[9px] text-gray-400 font-bold uppercase">{{ $order->payment_status }}</span>
                                            </div>
                                        </td>
                                        <td class="p-4 text-center font-black text-gray-900">{{ number_format($order->total_cost, 2) }} <small class="text-[10px] text-gray-400">SAR</small></td>
                                        <td class="p-4 text-right">
                                            <a href="{{ route('procurement.show', $order) }}" class="text-indigo-600 hover:text-indigo-900 font-bold text-sm">View</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile Card Stack -->
                    <div class="md:hidden space-y-4">
                        @foreach($purchases as $order)
                            <div class="bg-gray-50 rounded-2xl p-4 border border-gray-100 shadow-sm">
                                <div class="flex justify-between items-start mb-4">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-indigo-100 text-indigo-700 rounded-full flex items-center justify-center font-black mr-3">
                                            {{ substr($order->supplier->name ?? '?', 0, 1) }}
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-gray-900 leading-none">{{ $order->supplier->name ?? 'Unknown Supplier' }}</h4>
                                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">Ref #{{ $order->reference_number ?? $order->id }}</span>
                                        </div>
                                    </div>
                                    <span class="inline-flex px-2 py-0.5 text-[10px] font-black uppercase tracking-wider rounded-full {{ $order->status === 'received' ? 'bg-green-100 text-green-700' : 'bg-orange-100 text-orange-700' }}">
                                        {{ $order->status }}
                                    </span>
                                </div>
                                <div class="flex justify-between items-end">
                                    <div class="text-xs text-gray-500">
                                        <p class="font-bold uppercase tracking-tighter text-[10px] text-gray-400 mb-1">Date: {{ $order->transaction_date }}</p>
                                        <p class="font-black text-gray-900 text-lg">{{ number_format($order->total_cost, 2) }} <small class="text-xs font-normal text-gray-400">SAR</small></p>
                                    </div>
                                    <a href="{{ route('procurement.show', $order) }}" class="bg-indigo-600 text-white px-4 py-2 rounded-xl text-xs font-black uppercase tracking-widest shadow-md">
                                        Details
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-6">
                        {{ $purchases->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
