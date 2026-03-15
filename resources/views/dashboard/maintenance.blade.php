<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Maintenance Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Today's PM Tasks -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100">
                    <div class="px-6 py-4 bg-indigo-50/50 border-b border-indigo-100">
                        <h3 class="font-bold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                            Preventive Maintenance Today
                        </h3>
                    </div>
                    <div class="p-6">
                        @forelse($stats['today_pm'] as $pm)
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl mb-3 last:mb-0 border border-gray-100">
                                <div>
                                    <p class="text-sm font-bold text-gray-900">{{ $pm->schedule->title }}</p>
                                    <p class="text-[10px] text-gray-500 uppercase font-bold tracking-tighter">{{ $pm->schedule->equipment->name ?? 'N/A' }}</p>
                                </div>
                                <a href="#" class="text-xs font-black text-indigo-600 uppercase tracking-widest hover:text-indigo-800 transition">Start</a>
                            </div>
                        @empty
                            <div class="text-center py-6">
                                <p class="text-sm text-gray-400 italic">No PM scheduled for today.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Today's Inspections -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 border-l-4 border-emerald-500 pl-4">Daily Inspections</h3>
                    @forelse($stats['today_inspections'] as $inspection)
                        <div class="border-b last:border-0 border-gray-50 pb-3 mb-3 last:pb-0 last:mb-0 flex justify-between items-center">
                            <div>
                                <p class="text-sm font-bold text-gray-900">Room {{ $inspection->room_id }}</p>
                                <p class="text-[10px] text-gray-400 uppercase font-black">{{ $inspection->date }}</p>
                            </div>
                            <span class="px-2 py-0.5 bg-emerald-100 text-emerald-700 rounded text-[10px] font-black uppercase">{{ $inspection->result }}</span>
                        </div>
                    @empty
                        <p class="text-sm text-gray-400 italic text-center py-4">No daily inspections today.</p>
                    @endforelse
                </div>

                <!-- Open Alerts -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100 p-6 md:col-span-2">
                    <h3 class="text-lg font-bold text-gray-900 mb-6 flex justify-between items-center">
                        Active Critical Alerts
                        <span class="bg-red-500 text-white text-[10px] font-black px-2 py-1 rounded-full">{{ $stats['open_alerts']->count() }} Total</span>
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @forelse($stats['open_alerts'] as $alert)
                            <div class="p-4 bg-red-50 border-l-4 border-red-500 rounded-lg relative">
                                <p class="text-sm font-bold text-red-900 pr-12">{{ $alert->message }}</p>
                                <p class="text-[10px] text-red-400 uppercase font-black mt-2">{{ $alert->created_at->diffForHumans() }}</p>
                                <div class="absolute top-4 right-4">
                                    <span class="text-[10px] font-black uppercase py-0.5 px-2 bg-red-200 text-red-700 rounded">{{ $alert->severity }}</span>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-400 italic text-center py-4 col-span-2">No active alerts. System stable.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Assigned Tasks -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium mb-4">My Assigned Maintenance Tasks</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr>
                                    <th class="border-b p-3">ID</th>
                                    <th class="border-b p-3">Equipment</th>
                                    <th class="border-b p-3">Issue Description</th>
                                    <th class="border-b p-3">Status</th>
                                    <th class="border-b p-3">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($stats['assigned_tasks'] as $task)
                                    <tr class="hover:bg-gray-50 border-b border-gray-50 last:border-0">
                                        <td class="p-4 font-bold text-gray-900">#{{ $task->id }}</td>
                                        <td class="p-4">
                                            <div class="text-sm">
                                                <p class="font-bold text-gray-900">{{ $task->room->name ?? 'Room' }}</p>
                                                <p class="text-[10px] text-gray-400 font-black uppercase tracking-tighter">{{ $task->asset->name ?? 'No Asset Linked' }} ({{ $task->refrigerationSystem->name ?? 'N/A' }})</p>
                                            </div>
                                        </td>
                                        <td class="p-4 text-sm text-gray-600 italic">"{{ $task->issue_description }}"</td>
                                        <td class="p-4">
                                            <span class="px-2 py-0.5 bg-indigo-100 text-indigo-700 rounded-full text-[10px] font-black uppercase tracking-widest">
                                                {{ $task->status }}
                                            </span>
                                            <p class="text-[8px] font-black text-gray-400 mt-1 uppercase">{{ $task->maintenance_type }} task</p>
                                        </td>
                                        <td class="p-4">
                                            <a href="#" class="text-xs font-black text-indigo-600 hover:text-indigo-900 uppercase tracking-widest transition">Process</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center p-4 text-gray-500">No assigned tasks.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</x-app-layout>
