<x-app-layout>
    <div class="pb-6">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
            <x-page-header title="Cold Storage Rooms" description="Monitor and manage all cold storage facilities."
                :actionUrl="route('rooms.create')" :actionLabel="__('New')" />

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100">
                <div class="p-0 text-gray-900 overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-100">
                                <th class="px-4 py-3 text-xs font-black uppercase tracking-widest text-gray-400">Name</th>
                                <th class="px-4 py-3 text-xs font-black uppercase tracking-widest text-gray-400">Target Temp
                                </th>
                                <th class="px-4 py-3 text-xs font-black uppercase tracking-widest text-gray-400">Min / Max
                                </th>
                                <th class="px-4 py-3 text-xs font-black uppercase tracking-widest text-gray-400">Equipment
                                </th>
                                <th class="px-4 py-3 text-xs font-black uppercase tracking-widest text-gray-400 text-right">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($rooms as $room)
                                <tr class="hover:bg-gray-50/50 transition">
                                    <td class="px-4 py-1">
                                        <div class="font-bold text-gray-900">{{ $room->name }}</div>
                                        <div class="text-[10px] text-gray-400 mt-0.5">ID: #{{ $room->id }}</div>
                                    </td>
                                    <td class="px-4 py-1">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-100 italic">
                                            {{ number_format($room->target_temperature, 2) }}°C
                                        </span>
                                    </td>
                                    <td class="px-4 py-1">
                                        <span
                                            class="text-xs font-medium text-gray-500">{{ number_format($room->min_temperature, 2) }}°C</span>
                                        <span class="mx-1 text-gray-300">/</span>
                                        <span
                                            class="text-xs font-medium text-gray-500">{{ number_format($room->max_temperature, 2) }}°C</span>
                                    </td>
                                    <td class="px-4 py-1">
                                        @php
                                            $totalAssets = $room->refrigerationSystems->sum(fn($system) => $system->assets->count());
                                            $topLevelAssets = $room->refrigerationSystems->sum(fn($system) => $system->assets->whereNull('parent_id')->count());
                                        @endphp
                                        <span class="text-xs font-semibold text-gray-600">
                                            {{ $room->refrigerationSystems->count() }} Systems / {{ $totalAssets }} Total Assets
                                        </span>
                                        <div class="text-[10px] text-gray-400 italic">({{ $topLevelAssets }} Main Units)</div>
                                    </td>
                                    <td class="px-4 py-1 text-right">
                                        <div class="flex justify-end gap-2">
                                            <a href="{{ route('rooms.show', $room) }}"
                                                class="p-1 px-3 text-xs font-black uppercase tracking-tight text-indigo-600 hover:bg-indigo-50 rounded-lg transition">Show</a>
                                            <a href="{{ route('rooms.edit', $room) }}"
                                                class="p-1 px-3 text-xs font-black uppercase tracking-tight text-indigo-600 hover:bg-indigo-50 rounded-lg transition">Edit</a>
                                            <form method="POST" action="{{ route('rooms.destroy', $room) }}" class="inline">
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
                                    <td colspan="5" class="px-4 py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <i class="fa fa-hospital text-4xl text-gray-100 mb-4"></i>
                                            <p class="text-gray-400 font-medium">No storage rooms found</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if($rooms->hasPages())
                <div class="mt-6">
                    {{ $rooms->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
ut>