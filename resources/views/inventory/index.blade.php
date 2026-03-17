<x-app-layout>
    <x-page-header 
        title="Inventory Items" 
        description="Manage spare parts, consumables, and supply stock levels."
        :actionUrl="route('inventory.create')"
        actionLabel="Add New Part"
    />

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-200">
        <div class="p-0 text-gray-900 overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="px-4 py-3 text-[10px] font-black uppercase tracking-widest text-gray-400">Part Info</th>
                        <th class="px-4 py-3 text-[10px] font-black uppercase tracking-widest text-gray-400">Stock Level</th>
                        <th class="px-4 py-3 text-[10px] font-black uppercase tracking-widest text-gray-400">Supplier</th>
                        <th class="px-4 py-3 text-[10px] font-black uppercase tracking-widest text-gray-400">Unit Cost</th>
                        <th class="px-4 py-3 text-[10px] font-black uppercase tracking-widest text-gray-400 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($parts as $part)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-4 py-1">
                                <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-1">{{ $part->part_number }}</span>
                                <div class="font-bold text-gray-900">{{ $part->name }}</div>
                            </td>
                            <td class="px-4 py-1">
                                <div class="flex items-center gap-2">
                                    <span class="text-sm font-black {{ $part->stock <= $part->min_stock_level ? 'text-red-500' : 'text-green-600' }}">
                                        {{ $part->stock }} 
                                    </span>
                                    <span class="text-[10px] text-gray-400 font-bold uppercase">(Min: {{ $part->min_stock_level }})</span>
                                </div>
                                <div class="w-24 bg-gray-100 h-1.5 rounded-full mt-2 overflow-hidden">
                                    <div class="h-full {{ $part->stock <= $part->min_stock_level ? 'bg-red-500' : 'bg-green-500' }}" 
                                         style="width: {{ min(100, ($part->stock / max(1, $part->min_stock_level * 2)) * 100) }}%"></div>
                                </div>
                            </td>
                            <td class="px-4 py-1">
                                <div class="text-sm font-bold text-gray-700">{{ $part->supplier ? $part->supplier->name : 'N/A' }}</div>
                            </td>
                            <td class="px-4 py-1">
                                <div class="text-sm font-black text-gray-900">{{ number_format($part->cost, 2) }} <span class="text-[10px] text-gray-400">SAR</span></div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('inventory.edit', $part) }}" class="p-1 px-3 text-xs font-black uppercase tracking-tight text-indigo-600 hover:bg-indigo-50 rounded-lg transition">Edit</a>
                                    <form method="POST" action="{{ route('inventory.destroy', $part) }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1 px-3 text-xs font-black uppercase tracking-tight text-red-600 hover:bg-red-50 rounded-lg transition" onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500 italic bg-gray-50/50">No parts found in inventory</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($parts->hasPages())
        <div class="mt-6">
            {{ $parts->links() }}
        </div>
    @endif
</x-app-layout>

