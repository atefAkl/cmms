<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Maintenance Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-blue-500">
                    <div class="text-sm text-gray-500 uppercase">My Open Tasks</div>
                    <div class="text-3xl font-bold text-gray-800">{{ $openTasks->count() }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-red-500">
                    <div class="text-sm text-gray-500 uppercase">Recent System Alerts</div>
                    <div class="text-3xl font-bold text-gray-800">{{ $recentAlerts->count() }}</div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-bold mb-4">Pending Tasks</h3>
                    <ul class="list-disc pl-5">
                    @forelse($openTasks as $task)
                        <li class="mb-2">Task #{{ $task->id }} - {{ $task->issue_description }} <span class="bg-yellow-200 text-yellow-800 text-xs px-2 py-1 rounded">{{ $task->status }}</span></li>
                    @empty
                        <li class="text-gray-500">No open maintenance tasks.</li>
                    @endforelse
                    </ul>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-bold mb-4">Recent Alerts</h3>
                    <ul class="list-disc pl-5">
                    @forelse($recentAlerts as $alert)
                        <li class="mb-2 text-red-600">Room {{ $alert->room_id }} Temp: {{ $alert->temperature }}&deg;C <span class="text-xs uppercase ml-2 text-gray-500">{{ $alert->severity }}</span></li>
                    @empty
                        <li class="text-gray-500">No recent alerts.</li>
                    @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
