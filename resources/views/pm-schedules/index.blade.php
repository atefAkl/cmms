<x-app-layout>
    <x-page-header 
        title="PM Schedules" 
        description="Design and monitor preventive maintenance routines."
        :actionUrl="route('pm-schedules.create')"
        actionLabel="Create Schedule"
    />

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-200">
        <div class="p-0 text-gray-900 overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="px-4 py-3 text-[10px] font-black uppercase tracking-widest text-gray-400">Routine Description</th>
                        <th class="px-4 py-3 text-[10px] font-black uppercase tracking-widest text-gray-400">Equipment Type</th>
                        <th class="px-4 py-3 text-[10px] font-black uppercase tracking-widest text-gray-400">Priority</th>
                        <th class="px-4 py-3 text-[10px] font-black uppercase tracking-widest text-gray-400">Timing</th>
                        <th class="px-4 py-3 text-[10px] font-black uppercase tracking-widest text-gray-400 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($schedules as $schedule)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-4 py-1">
                                <div class="font-bold text-gray-900">{{ $schedule->description }}</div>
                            </td>
                            <td class="px-4 py-1">
                                <span class="text-xs font-bold text-gray-500 uppercase tracking-tight">{{ class_basename($schedule->equipment_type) }} #{{ $schedule->equipment_id }}</span>
                            </td>
                            <td class="px-4 py-1">
                                <span class="px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest 
                                    {{ $schedule->priority == 'critical' ? 'bg-red-100 text-red-700' : ($schedule->priority == 'high' ? 'bg-orange-100 text-orange-700' : 'bg-gray-100 text-gray-700') }}">
                                    {{ $schedule->priority }}
                                </span>
                            </td>
                            <td class="px-4 py-1">
                                <div class="flex flex-col">
                                    <span class="text-xs font-bold text-gray-700 italic">{{ $schedule->interval_days }} Day Interval</span>
                                    <span class="text-[10px] uppercase font-black tracking-tighter {{ now()->greaterThanOrEqualTo($schedule->next_due) ? 'text-red-500' : 'text-gray-400' }}">
                                        Due: {{ $schedule->next_due->format('Y-m-d') }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-4 py-1 text-right">
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
                            <td colspan="5" class="px-4 py-12 text-center text-gray-500 italic bg-gray-50/50">No preventive maintenance schedules defined</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($schedules->hasPages())
        <div class="mt-6">
            {{ $schedules->links() }}
        </div>
    @endif
</x-app-layout>

