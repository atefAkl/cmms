<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Maintenance Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Today's Inspections -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Today's Inspections</h3>
                    @if($stats['today_inspections']->count() > 0)
                        <ul class="space-y-3">
                            @foreach($stats['today_inspections'] as $inspection)
                                <li class="border-b pb-2">
                                    <span class="font-bold">Room {{ $inspection->room_id }}</span> - 
                                    <span class="text-sm text-gray-600">{{ $inspection->status }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-gray-500">No inspections scheduled for today.</p>
                    @endif
                </div>

                <!-- Open Alerts -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Open Alerts</h3>
                    @if($stats['open_alerts']->count() > 0)
                        <ul class="space-y-3">
                            @foreach($stats['open_alerts'] as $alert)
                                <li class="p-3 bg-red-50 border border-red-200 rounded text-red-800">
                                    <span class="font-bold">{{ ucfirst($alert->severity) }}</span>: {{ $alert->message }}
                                    <span class="text-xs float-right">{{ $alert->created_at->diffForHumans() }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-gray-500">No open alerts.</p>
                    @endif
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
                                    <tr class="hover:bg-gray-50">
                                        <td class="border-b p-3">#{{ $task->id }}</td>
                                        <td class="border-b p-3">
                                            {{ $task->room ? "Room: " . $task->room->name : '' }}
                                            {{ $task->compressor ? "Compressor: " . $task->compressor->id : '' }}
                                        </td>
                                        <td class="border-b p-3">{{ $task->issue_description }}</td>
                                        <td class="border-b p-3">
                                            <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">
                                                {{ ucfirst($task->status) }}
                                            </span>
                                        </td>
                                        <td class="border-b p-3">
                                            <a href="#" class="text-indigo-600 hover:underline">View</a>
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
