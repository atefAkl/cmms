@extends('layouts.app')

@section('content')
    <div class="mb-6 flex justify-between items-center px-4 sm:px-0">
        <div>
            <h1 class="text-2xl font-black text-gray-900">PM Schedules</h1>
            <p class="text-gray-500 text-sm">Design and monitor preventive maintenance routines.</p>
        </div>
        <a href="{{ route('pm-schedules.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-bold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition shadow-lg shadow-indigo-600/20">
            Create Schedule
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
                        <th class="px-6 py-4 text-xs font-black uppercase tracking-widest text-gray-400">Routine Description</th>
                        <th class="px-6 py-4 text-xs font-black uppercase tracking-widest text-gray-400">Equipment Type</th>
                        <th class="px-6 py-4 text-xs font-black uppercase tracking-widest text-gray-400">Priority</th>
                        <th class="px-6 py-4 text-xs font-black uppercase tracking-widest text-gray-400">Timing</th>
                        <th class="px-6 py-4 text-xs font-black uppercase tracking-widest text-gray-400 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($schedules as $schedule)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-900">{{ $schedule->description }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-xs font-bold text-gray-500 uppercase tracking-tight">{{ class_basename($schedule->equipment_type) }} #{{ $schedule->equipment_id }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest 
                                    {{ $schedule->priority == 'critical' ? 'bg-red-100 text-red-700' : ($schedule->priority == 'high' ? 'bg-orange-100 text-orange-700' : 'bg-gray-100 text-gray-700') }}">
                                    {{ $schedule->priority }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <span class="text-xs font-bold text-gray-700 italic">{{ $schedule->interval_days }} Day Interval</span>
                                    <span class="text-[10px] uppercase font-black tracking-tighter {{ now()->greaterThanOrEqualTo($schedule->next_due) ? 'text-red-500' : 'text-gray-400' }}">
                                        Due: {{ $schedule->next_due->format('Y-m-d') }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('pm-schedules.edit', $schedule) }}" class="p-1 px-3 text-xs font-black uppercase tracking-tight text-indigo-600 hover:bg-indigo-50 rounded-lg transition">Edit</a>
                                    <form method="POST" action="{{ route('pm-schedules.destroy', $schedule) }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1 px-3 text-xs font-black uppercase tracking-tight text-red-600 hover:bg-red-50 rounded-lg transition" onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500 italic bg-gray-50/50">No preventive maintenance schedules defined</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">
        {{ $schedules->links() }}
    </div>
@endsection

