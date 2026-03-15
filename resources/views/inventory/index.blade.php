@extends('layouts.app')

@section('content')
    <div class="mb-6 flex justify-between items-center px-4 sm:px-0">
        <div>
            <h1 class="text-2xl font-black text-gray-900">Inventory Items</h1>
            <p class="text-gray-500 text-sm">Manage spare parts, consumables, and supply stock levels.</p>
        </div>
        <a href="{{ route('inventory.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-bold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition shadow-lg shadow-indigo-600/20">
            Add New Part
        </a>
    </div>

    @if (session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-100 rounded-xl text-sm font-bold text-green-700 flex items-center gap-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-200">
        <div class="p-0 text-gray-900 overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="px-6 py-4 text-xs font-black uppercase tracking-widest text-gray-400">Part Info</th>
                        <th class="px-6 py-4 text-xs font-black uppercase tracking-widest text-gray-400">Stock Level</th>
                        <th class="px-6 py-4 text-xs font-black uppercase tracking-widest text-gray-400">Supplier</th>
                        <th class="px-6 py-4 text-xs font-black uppercase tracking-widest text-gray-400">Unit Cost</th>
                        <th class="px-6 py-4 text-xs font-black uppercase tracking-widest text-gray-400 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($parts as $part)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-6 py-4">
                                <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-1">{{ $part->part_number }}</span>
                                <div class="font-bold text-gray-900">{{ $part->name }}</div>
                            </td>
                            <td class="px-6 py-4">
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
                            <td class="px-6 py-4">
                                <div class="text-sm font-bold text-gray-700">{{ $part->supplier ? $part->supplier->name : 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4">
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

    <div class="mt-6">
        {{ $parts->links() }}
    </div>
@endsection

