<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Rooms Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">All Cold Storage Rooms</h3>
                        <a href="{{ route('rooms.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('Add Room') }}
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
                                    <th class="border-b p-3">Name</th>
                                    <th class="border-b p-3">Target Temp</th>
                                    <th class="border-b p-3">Min / Max</th>
                                    <th class="border-b p-3">Equipment Count</th>
                                    <th class="border-b p-3 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($rooms as $room)
                                    <tr class="hover:bg-gray-50 border-b">
                                        <td class="p-3">{{ $room->name }}</td>
                                        <td class="p-3">{{ $room->target_temperature }}°C</td>
                                        <td class="p-3 text-sm text-gray-500">{{ $room->min_temperature }}°C / {{ $room->max_temperature }}°C</td>
                                        <td class="p-3 text-sm">
                                            {{ $room->compressors->count() }} Comp., {{ $room->evaporator ? 1 : 0 }} Evap.
                                        </td>
                                        <td class="p-3 text-right space-x-2">
                                            <a href="{{ route('rooms.edit', $room) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">Edit</a>
                                            <form method="POST" action="{{ route('rooms.destroy', $room) }}" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 text-sm font-medium" onclick="return confirm('Are you sure?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="p-4 text-center text-gray-500">No rooms found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $rooms->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
