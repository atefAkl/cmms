<x-app-layout>
    <x-page-header 
        title="Maintenance Orders" 
        description="Track and manage service requests and repairs."
        :actionUrl="route('maintenance.create')"
        actionLabel="Create Work Order"
    />

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-200">
        <div class="p-0 text-gray-900 overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="px-4 py-3 text-[10px] font-black uppercase tracking-widest text-gray-400">Task Information</th>
                        <th class="px-4 py-3 text-[10px] font-black uppercase tracking-widest text-gray-400">Location / Room</th>
                        <th class="px-4 py-3 text-[10px] font-black uppercase tracking-widest text-gray-400">Technician</th>
                        <th class="px-4 py-3 text-[10px] font-black uppercase tracking-widest text-gray-400">Status</th>
                        <th class="px-4 py-3 text-[10px] font-black uppercase tracking-widest text-gray-400 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($tasks as $task)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-4 py-1">
                                <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-1">#{{ $task->id }}</span>
                                <div class="font-bold text-gray-900 line-clamp-1 italic text-sm">"{{ \Illuminate\Support\Str::limit($task->issue_description, 40) }}"</div>
                            </td>
                            <td class="px-4 py-1">
                                <div class="font-semibold text-gray-700">{{ $task->room ? $task->room->name : 'N/A' }}</div>
                            </td>
                            <td class="px-4 py-1">
                                <div class="flex items-center">
                                    <div class="h-8 w-8 rounded-lg bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 font-black text-xs mr-3">
                                        {{ strtoupper(substr($task->technician->name ?? 'U', 0, 1)) }}
                                    </div>
                                    <span class="text-sm font-bold text-gray-700">{{ $task->technician ? $task->technician->name : 'Unassigned' }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-1">
                                <span class="px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest 
                                    {{ $task->status === 'completed' ? 'bg-green-100 text-green-700' : 
                                       ($task->status === 'in_progress' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-700') }}">
                                    {{ str_replace('_', ' ', $task->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-1 text-right">
                                <a href="{{ route('maintenance.edit', $task) }}" class="p-1 px-4 text-xs font-black uppercase tracking-tight text-indigo-600 hover:bg-indigo-50 rounded-lg transition">Manage</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-100 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                                    <p class="text-gray-400 font-medium italic">No active work orders found</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($tasks->hasPages())
        <div class="mt-6">
            {{ $tasks->links() }}
        </div>
    @endif
</x-app-layout>

