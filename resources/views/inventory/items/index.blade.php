<x-app-layout>
    <div class="py-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-page-header 
                title="{{ __('Global Industrial Inventory') }}" 
                description="Manage your branded spare parts and HVAC components."
                actionUrl="{{ route('inventory-items.create') }}"
                actionLabel="Register New Item"
                actionIcon="fa fa-plus"
            />

            <div class="bg-white shadow-2xl sm:rounded-3xl border border-gray-100 overflow-hidden">
                <div class="p-4 sm:p-10">
                    <!-- Desktop Table -->
                    <div class="hidden md:block overflow-x-auto">
                        <table class="w-full text-left border-separate border-spacing-y-2">
                            <thead>
                                <tr class="text-gray-400">
                                    <th class="px-6 py-3 text-xs font-black uppercase tracking-widest">Brand / Item</th>
                                    <th class="px-6 py-3 text-xs font-black uppercase tracking-widest">Specs</th>
                                    <th class="px-6 py-3 text-xs font-black uppercase tracking-widest text-center">Ref / Model</th>
                                    <th class="px-6 py-3 text-xs font-black uppercase tracking-widest text-center">Stock</th>
                                    <th class="px-6 py-3 text-xs font-black uppercase tracking-widest text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($items as $item)
                                    <tr class="group bg-white hover:bg-gray-50 transition-all duration-200 shadow-sm rounded-xl overflow-hidden border border-transparent hover:border-gray-200">
                                        <td class="px-6 py-5 rounded-l-2xl">
                                            <div class="flex items-center">
                                                <div class="h-10 w-10 flex-shrink-0 bg-indigo-50 text-indigo-600 rounded-lg flex items-center justify-center font-black text-xs uppercase overflow-hidden">
                                                    @if($item->image)
                                                        <img src="{{ Storage::url($item->image) }}" class="h-full w-full object-cover">
                                                    @else
                                                        {{ substr($item->brand ?? $item->name, 0, 2) }}
                                                    @endif
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-black text-gray-900 leading-none mb-1">{{ $item->name }}</div>
                                                    <div class="flex items-center space-x-2">
                                                        <span class="text-[10px] text-gray-400 font-bold uppercase tracking-tighter">{{ $item->brand ?? 'Generic' }}</span>
                                                        <span class="text-[10px] text-indigo-400 font-black uppercase tracking-tighter">{{ $item->category->name }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-5">
                                            @php $specs = $item->tech_specs ?? []; @endphp
                                            <div class="flex flex-wrap gap-1">
                                                @if(!empty($specs['refrigerant']))
                                                    <span class="px-2 py-0.5 bg-emerald-50 text-emerald-600 text-[10px] font-black rounded border border-emerald-100">{{ $specs['refrigerant'] }}</span>
                                                @endif
                                                @if(!empty($specs['voltage']))
                                                    <span class="px-2 py-0.5 bg-blue-50 text-blue-600 text-[10px] font-black rounded border border-blue-100">{{ $specs['voltage'] }}</span>
                                                @endif
                                                @if($item->type === 'part')
                                                    <span class="px-2 py-0.5 bg-gray-50 text-gray-500 text-[10px] font-black rounded border border-gray-100">SPARE</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-5 text-center">
                                            <div class="text-xs font-mono text-gray-500 uppercase">{{ $item->model_number ?? '---' }}</div>
                                            <div class="text-[9px] text-gray-300 font-bold">{{ $item->reference_number ?? 'No Ref' }}</div>
                                        </td>
                                        <td class="px-6 py-5 text-center">
                                            <div class="inline-flex flex-col items-center">
                                                <span class="text-lg font-black {{ $item->stock <= $item->min_stock_level ? 'text-red-600' : 'text-gray-900' }}">
                                                    {{ number_format($item->stock, 2) }}
                                                </span>
                                                <span class="text-[10px] text-gray-400 font-bold uppercase">{{ $item->uom }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-5 text-right rounded-r-2xl">
                                            <div class="flex justify-end space-x-2 ">
                                                <a href="{{ route('inventory-items.show', $item) }}" class="p-2 text-indigo-600 shadow-sm">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                <a href="{{ route('inventory-items.edit', $item) }}" class="p-2 text-indigo-600 shadow-sm">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <form action="{{ route('inventory-items.destroy', $item) }}" method="POST" onsubmit="return confirm('Archive this item?');">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="p-2 text-red-600 shadow-sm">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile Cards -->
                    <div class="md:hidden space-y-4">
                        @foreach($items as $item)
                            <div class="bg-gray-50 rounded-2xl p-5 border border-gray-100 flex justify-between items-start">
                                <div>
                                    <span class="text-[9px] font-black text-indigo-400 uppercase tracking-widest block mb-1">{{ $item->brand ?? 'Generic' }} | {{ $item->category->name }}</span>
                                    <h4 class="font-bold text-gray-900 text-lg leading-tight mb-2">{{ $item->name }}</h4>
                                    <div class="text-[10px] font-mono text-gray-400">MOD: {{ $item->model_number ?? '---' }}</div>
                                </div>
                                <div class="text-right">
                                    <div class="text-xl font-black {{ $item->stock <= $item->min_stock_level ? 'text-red-500' : 'text-gray-900' }}">
                                        {{ number_format($item->stock, 2) }}
                                    </div>
                                    <div class="text-[9px] font-bold text-gray-400 uppercase">{{ $item->uom }}</div>
                                    <a href="{{ route('inventory-items.edit', $item) }}" class="mt-4 inline-block text-[10px] font-black bg-white px-3 py-1 rounded-full border border-gray-200 shadow-sm text-indigo-600 uppercase">Edit</a>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-10 border-t border-gray-100 pt-6">
                        {{ $items->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
