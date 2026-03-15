<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center text-center sm:text-left">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Global Inventory') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('item-categories.index') }}" class="bg-white border text-gray-700 px-4 py-2 rounded-lg font-bold shadow-sm hover:bg-gray-50 transition">Manage Categories</a>
                <a href="{{ route('inventory-items.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow-lg flex items-center transition">
                    + New Item
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl sm:rounded-2xl border border-gray-100 overflow-hidden">
                <div class="p-4 sm:p-8">
                    <!-- Desktop Table -->
                    <div class="hidden md:block overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="bg-gray-50 border-b border-gray-100">
                                    <th class="p-4 text-xs font-bold text-gray-400 uppercase tracking-widest">Item / Category</th>
                                    <th class="p-4 text-xs font-bold text-gray-400 uppercase tracking-widest text-center">Type</th>
                                    <th class="p-4 text-xs font-bold text-gray-400 uppercase tracking-widest text-center">Reference</th>
                                    <th class="p-4 text-xs font-bold text-gray-400 uppercase tracking-widest text-center">In Stock</th>
                                    <th class="p-4 text-xs font-bold text-gray-400 uppercase tracking-widest text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($items as $item)
                                    <tr class="border-b transition hover:bg-gray-50 border-gray-50">
                                        <td class="p-4">
                                            <div class="flex flex-col">
                                                <span class="text-gray-900 font-bold">{{ $item->name }}</span>
                                                <span class="text-[10px] text-gray-400 font-black uppercase">{{ $item->category->name }}</span>
                                            </div>
                                        </td>
                                        <td class="p-4 text-center">
                                            <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase border {{ $item->type === 'part' ? 'border-blue-200 text-blue-600 bg-blue-50' : 'border-purple-200 text-purple-600 bg-purple-50' }}">
                                                {{ $item->type }}
                                            </span>
                                        </td>
                                        <td class="p-4 text-center font-mono text-xs text-gray-500">{{ $item->reference_number ?? $item->part_number ?? '---' }}</td>
                                        <td class="p-4 text-center">
                                            <div class="flex flex-col items-center">
                                                <span class="text-lg font-black {{ $item->stock <= $item->min_stock_level ? 'text-red-600' : 'text-gray-900' }}">
                                                    {{ $item->stock }}
                                                </span>
                                                <span class="text-[10px] text-gray-400 font-bold uppercase">{{ $item->uom }}</span>
                                            </div>
                                        </td>
                                        <td class="p-4 text-right space-x-2">
                                            <a href="{{ route('inventory-items.edit', $item) }}" class="text-blue-600 hover:text-blue-900 font-bold text-sm">Edit</a>
                                            <form action="{{ route('inventory-items.destroy', $item) }}" method="POST" class="inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-700 font-bold text-sm" onclick="return confirm('Remove item?')">Del</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile Card Stack -->
                    <div class="md:hidden space-y-4">
                        @foreach($items as $item)
                            <div class="bg-gray-50 rounded-2xl p-4 border border-gray-100 shadow-sm flex justify-between items-center">
                                <div class="flex flex-col">
                                    <span class="text-[9px] font-black text-indigo-400 uppercase tracking-widest mb-1">{{ $item->category->name }}</span>
                                    <h4 class="font-bold text-gray-900 text-lg leading-tight">{{ $item->name }}</h4>
                                    <div class="flex items-center mt-2 space-x-2">
                                        <span class="px-2 py-0.5 rounded text-[9px] font-black uppercase border {{ $item->type === 'part' ? 'border-blue-200 text-blue-600 bg-blue-50' : 'border-purple-200 text-purple-600 bg-purple-50' }}">
                                            {{ $item->type }}
                                        </span>
                                        <span class="text-[10px] font-mono text-gray-400">{{ $item->reference_number ?? 'N/A' }}</span>
                                    </div>
                                </div>
                                <div class="flex flex-col items-end">
                                    <div class="text-right mb-3">
                                        <span class="block text-2xl font-black leading-none {{ $item->stock <= $item->min_stock_level ? 'text-red-500' : 'text-gray-900' }}">
                                            {{ $item->stock }}
                                        </span>
                                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">{{ $item->uom }}</span>
                                    </div>
                                    <div class="flex space-x-2">
                                        <a href="{{ route('inventory-items.edit', $item) }}" class="p-2 bg-white border border-gray-200 rounded-lg text-blue-600 shadow-sm">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-6">
                        {{ $items->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
