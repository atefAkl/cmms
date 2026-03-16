<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manager Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- KPIs -->
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 border-b-4 border-b-red-500">
                    <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-1">Active Alerts</h3>
                    <p class="text-3xl font-black text-gray-900">{{ $stats['active_alerts'] }}</p>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 border-b-4 border-b-green-500">
                    <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-1">System Health</h3>
                    <p class="text-3xl font-black text-gray-900">{{ $stats['equipment_health'] }}%</p>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 border-b-4 border-b-indigo-500">
                    <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-1">PM Tasks</h3>
                    <p class="text-3xl font-black text-gray-900">{{ $stats['upcoming_pm']->count() }}</p>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 border-b-4 border-b-orange-500">
                    <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-1 text-orange-600">Low Stock</h3>
                    <p class="text-3xl font-black text-gray-900">{{ $stats['low_stock_count'] }}</p>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 border-b-4 border-b-blue-500">
                    <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-1">Pending orders</h3>
                    <p class="text-3xl font-black text-gray-900">{{ $stats['pending_purchases_count'] }}</p>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 border-b-4 border-b-gray-800">
                    <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-1 text-gray-500">M. Cost</h3>
                    <p class="text-3xl font-black text-gray-900 leading-none">{{ number_format($stats['monthly_maintenance_cost'], 0) }}<span class="text-[10px] font-bold text-gray-400 ml-1">SAR</span></p>
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

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- PM Schedule Overview -->
                <div class="lg:col-span-1 bg-white shadow-sm sm:rounded-xl border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-50 bg-gray-50/50">
                        <h3 class="font-bold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                            Upcoming PM
                        </h3>
                    </div>
                    <div class="p-6">
                        @forelse($stats['upcoming_pm'] as $pm)
                            <div class="mb-4 last:mb-0 pb-4 last:pb-0 border-b last:border-0 border-gray-50">
                                <p class="text-sm font-bold text-gray-900">{{ $pm->schedule->title }}</p>
                                <div class="flex justify-between items-center mt-1">
                                    <span class="text-xs text-gray-500">{{ \Illuminate\Support\Carbon::parse($pm->scheduled_date)->format('M d, Y') }}</span>
                                    <span class="text-[10px] uppercase font-bold text-indigo-600 bg-indigo-50 px-1.5 py-0.5 rounded">{{ $pm->schedule->frequency_type }}</span>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500 italic text-center py-4">No upcoming PM tasks.</p>
                        @endforelse
                    </div>
                </div>

                <div class="lg:col-span-2 bg-white shadow-sm sm:rounded-xl border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-50 flex justify-between items-center bg-gray-50/50">
                        <h3 class="font-bold text-gray-900">Equipment Hierarchy & Status</h3>
                        <a href="{{ route('assets.index') }}" class="text-[10px] font-black uppercase tracking-widest text-indigo-600 hover:text-indigo-800 transition">Manage Assets →</a>
                    </div>
                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($stats['rooms'] as $room)
                            <div class="space-y-4">
                                <div class="flex items-center gap-2 pb-2 border-b border-gray-50">
                                    <span class="w-2 h-2 rounded-full {{ $room->status == 'active' ? 'bg-green-500' : 'bg-red-500' }}"></span>
                                    <h4 class="font-black text-gray-900 uppercase tracking-tighter">{{ $room->name }}</h4>
                                </div>
                                <div class="space-y-4">
                                    @foreach($room->refrigerationSystems as $system)
                                        <div class="pl-4 border-l-2 border-indigo-100">
                                            <div class="flex justify-between items-center mb-2">
                                                <span class="text-[10px] font-black uppercase tracking-widest text-indigo-400">System: {{ $system->name }}</span>
                                                <p class="text-xs text-gray-400 uppercase font-bold">Current Temp</p>
                                                <p class="text-xl font-black text-gray-900">{{ optional($room->sensors->last())->temperature ?? 'N/A' }}°C</p>
                                            </div>
                                            <div class="space-y-1">
                                                @php $topAssets = $system->assets->whereNull('parent_id')->take(3); @endphp
                                                @foreach($topAssets as $asset)
                                                    <div class="flex items-center justify-between text-xs">
                                                        <span class="text-gray-700 font-bold flex items-center gap-1">
                                                            <span class="w-1 h-1 rounded-full bg-gray-400"></span>
                                                            {{ $asset->name }}
                                                        </span>
                                                        <span class="text-[9px] font-black uppercase {{ $asset->status == 'active' ? 'text-green-500' : 'text-red-500' }}">{{ $asset->status }}</span>
                                                    </div>
                                                    @if($asset->children->count() > 0)
                                                        <div class="pl-4 text-[10px] text-gray-400 italic">
                                                            + {{ $asset->children->count() }} components
                                                        </div>
                                                    @endif
                                                @endforeach
                                                @if($system->assets->whereNull('parent_id')->count() > 3)
                                                    <div class="text-[9px] text-gray-400 pl-2">... and {{ $system->assets->whereNull('parent_id')->count() - 3 }} more</div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
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
