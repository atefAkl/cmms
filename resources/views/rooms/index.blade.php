@extends('layouts.app')

@section('content')
    <div class="mb-6 flex justify-between items-center px-4 sm:px-0">
        <div>
            <h1 class="text-2xl font-black text-gray-900">Cold Storage Rooms</h1>
            <p class="text-gray-500 text-sm">Monitor and manage all cold storage facilities.</p>
        </div>
        <a href="{{ route('rooms.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-bold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-lg shadow-indigo-600/20">
            Add Room
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
                        <th class="px-6 py-4 text-xs font-black uppercase tracking-widest text-gray-400">Name</th>
                        <th class="px-6 py-4 text-xs font-black uppercase tracking-widest text-gray-400">Target Temp</th>
                        <th class="px-6 py-4 text-xs font-black uppercase tracking-widest text-gray-400">Min / Max</th>
                        <th class="px-6 py-4 text-xs font-black uppercase tracking-widest text-gray-400">Equipment</th>
                        <th class="px-6 py-4 text-xs font-black uppercase tracking-widest text-gray-400 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($rooms as $room)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-900">{{ $room->name }}</div>
                                <div class="text-xs text-gray-400 mt-0.5">ID: {{ $room->id }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-100 italic">
                                    {{ number_format($room->target_temperature, 2) }}°C
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-xs font-medium text-gray-500">{{ number_format($room->min_temperature, 2) }}°C</span>
                                <span class="mx-1 text-gray-300">/</span>
                                <span class="text-xs font-medium text-gray-500">{{ number_format($room->max_temperature, 2) }}°C</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-xs font-semibold text-gray-600">
                                    {{ $room->compressors?->count() ?? 0 }} Comp. / {{ $room->evaporator ? 1 : 0 }} Evap.
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('rooms.edit', $room) }}" class="p-1 px-3 text-xs font-black uppercase tracking-tight text-indigo-600 hover:bg-indigo-50 rounded-lg transition">Edit</a>
                                    <form method="POST" action="{{ route('rooms.destroy', $room) }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1 px-3 text-xs font-black uppercase tracking-tight text-red-600 hover:bg-red-50 rounded-lg transition" onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-200 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                    <p class="text-gray-400 font-medium">No storage rooms found</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">
        {{ $rooms->links() }}
    </div>
@endsection

