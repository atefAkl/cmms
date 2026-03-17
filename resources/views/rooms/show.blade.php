<x-app-layout>
    <div class="pb-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-page-header 
                title="Room: {{ $room->name }}" 
                description="Manage warehouses and assets linked to this storage facility."
                :backRoute="route('rooms.index')"
            />

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100">
                <div class="p-6 text-gray-900">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-800">Linked Warehouses</h3>
                    </div>

                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-100">
                                <th class="px-4 py-3 text-[10px] font-black uppercase tracking-widest text-gray-400">Name</th>
                                <th class="px-4 py-3 text-[10px] font-black uppercase tracking-widest text-gray-400">Location</th>
                                <th class="px-4 py-3 text-[10px] font-black uppercase tracking-widest text-gray-400 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($warehouses as $warehouse)
                                <tr class="hover:bg-gray-50/50 transition border-b border-gray-50 last:border-0">
                                    <td class="px-4 py-1">
                                        <div class="font-bold text-gray-900">{{ $warehouse->name }}</div>
                                        <div class="text-[10px] text-gray-400">ID: #{{ $warehouse->id }}</div>
                                    </td>
                                    <td class="px-4 py-1">
                                        <div class="text-sm text-gray-500">{{ $warehouse->location ?? 'N/A' }}</div>
                                    </td>
                                    <td class="px-4 py-1 text-right">
                                        <div class="flex justify-end gap-2">
                                            <a href="{{ route('warehouses.edit', $warehouse) }}"
                                                class="p-1 px-3 text-xs font-black uppercase tracking-tight text-indigo-600 hover:bg-indigo-50 rounded-lg transition">Edit</a>
                                            <form method="POST" action="{{ route('warehouses.destroy', $warehouse) }}"
                                                class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="p-1 px-3 text-xs font-black uppercase tracking-tight text-red-600 hover:bg-red-50 rounded-lg transition"
                                                    onclick="return confirm('Are you sure?')">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <i class="fa fa-warehouse text-4xl text-gray-100 mb-4"></i>
                                            <p class="text-gray-400 font-medium">No warehouses linked to this room</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    @if($warehouses->hasPages())
                        <div class="mt-4">
                            {{ $warehouses->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>