<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Inventory Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Spare Parts Inventory</h3>
                        <a href="{{ route('inventory.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('Add Part') }}
                        </a>
                    </div>

                    @if (session('success'))
                        <div class="mb-4 font-medium text-sm text-green-600">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="border-b p-3">Part Number</th>
                                    <th class="border-b p-3">Name</th>
                                    <th class="border-b p-3">Stock Level</th>
                                    <th class="border-b p-3">Supplier</th>
                                    <th class="border-b p-3">Cost (SAR)</th>
                                    <th class="border-b p-3 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($parts as $part)
                                    <tr class="hover:bg-gray-50 border-b">
                                        <td class="p-3">{{ $part->part_number }}</td>
                                        <td class="p-3">{{ $part->name }}</td>
                                        <td class="p-3">
                                            <span class="{{ $part->stock <= $part->min_stock_level ? 'text-red-600 font-bold' : 'text-green-600' }}">
                                                {{ $part->stock }} 
                                            </span>
                                            <span class="text-xs text-gray-500">(Min: {{ $part->min_stock_level }})</span>
                                        </td>
                                        <td class="p-3 text-sm text-gray-600">{{ $part->supplier ? $part->supplier->name : 'N/A' }}</td>
                                        <td class="p-3">{{ number_format($part->cost, 2) }}</td>
                                        <td class="p-3 text-right space-x-2">
                                            <a href="{{ route('inventory.edit', $part) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">Edit</a>
                                            <form method="POST" action="{{ route('inventory.destroy', $part) }}" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 text-sm font-medium" onclick="return confirm('Are you sure?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="p-4 text-center text-gray-500">No spare parts found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $parts->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
