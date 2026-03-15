<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manager Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- KPIs -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-sm text-gray-500">Active Alerts</h3>
                    <p class="text-3xl font-bold text-red-600">{{ $stats['active_alerts'] }}</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-sm text-gray-500">Equipment Health</h3>
                    <p class="text-3xl font-bold text-green-600">{{ $stats['equipment_health'] }}%</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-sm text-gray-500">Maintenance Backlog</h3>
                    <p class="text-3xl font-bold text-yellow-600">{{ $stats['maintenance_backlog'] }}</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-sm text-gray-500">Monthly Cost (SAR)</h3>
                    <p class="text-3xl font-bold text-gray-800">{{ number_format($stats['monthly_maintenance_cost'], 2) }}</p>
                </div>
            </div>

            <!-- Charts -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Temperature Trends -->
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Temperature Trends (Avg Last 7 Days)</h3>
                    <canvas id="tempChart"></canvas>
                </div>

                <!-- Maintenance Costs -->
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Maintenance Costs (Last 6 Months)</h3>
                    <canvas id="costChart"></canvas>
                </div>
            </div>

            <!-- Rooms -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium mb-4">Rooms Status</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @foreach($stats['rooms'] as $room)
                            <div class="border p-4 rounded {{ $room->status == 'active' ? 'border-green-400 bg-green-50' : 'border-red-400 bg-red-50' }}">
                                <h4 class="font-bold">{{ $room->name }}</h4>
                                <p>Temp: {{ count($room->temperatureReadings ?? []) ? $room->temperatureReadings->last()->temperature : 'N/A' }} / Target: {{ $room->target_temperature }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            
        </div>
    </div>

    <!-- Chart.js integration -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Temperature Chart
            new Chart(document.getElementById('tempChart'), {
                type: 'line',
                data: {
                    labels: {!! json_encode($stats['temperature_trends']['labels']) !!},
                    datasets: [{
                        label: 'Avg Temperature (°C)',
                        data: {!! json_encode($stats['temperature_trends']['data']) !!},
                        borderColor: '#3b82f6',
                        tension: 0.1
                    }]
                }
            });

            // Cost Chart
            new Chart(document.getElementById('costChart'), {
                type: 'bar',
                data: {
                    labels: {!! json_encode($stats['maintenance_costs_chart']['labels']) !!},
                    datasets: [{
                        label: 'Cost',
                        data: {!! json_encode($stats['maintenance_costs_chart']['data']) !!},
                        backgroundColor: '#10b981'
                    }]
                }
            });
        });
    </script>
</x-app-layout>
