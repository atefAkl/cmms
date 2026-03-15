<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manager Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-red-500">
                    <div class="text-sm text-gray-500 uppercase">Active Alerts</div>
                    <div class="text-3xl font-bold text-gray-800">{{ $activeAlerts }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-yellow-500">
                    <div class="text-sm text-gray-500 uppercase">Active Maintenance Tasks</div>
                    <div class="text-3xl font-bold text-gray-800">{{ $activeMaintenance }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-green-500">
                    <div class="text-sm text-gray-500 uppercase">Maintenance Cost</div>
                    <div class="text-3xl font-bold text-gray-800">${{ number_format($totalCost, 2) }}</div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold mb-4">Rooms Status</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($rooms as $room)
                            <div class="p-4 border rounded shadow-sm" x-data="{ open: false }">
                                <h4 class="font-bold">{{ $room->name }}</h4>
                                <p class="text-sm text-gray-500 flex justify-between">
                                    <span>Target: {{ $room->target_temperature }}&deg;C</span>
                                    <span>Status: <span class="text-green-600 font-bold">OK</span></span>
                                </p>
                                <button @click="open = !open" class="mt-2 text-blue-500 text-sm focus:outline-none">View Equipment</button>
                                <div x-show="open" class="mt-4 text-sm" style="display: none;">
                                    <p><strong>Compressors:</strong> {{ $room->compressors->count() }}</p>
                                    <p><strong>Evaporators:</strong> {{ $room->evaporator ? 1 : 0 }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</x-app-layout>
