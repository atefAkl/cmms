<x-app-layout>

    <div x-data="roomDashboard()" x-init="initDashboard()" x-cloak class="min-h-screen bg-slate-50/50 pb-12">

        <!-- 1. Header Section (Sticky) -->
        <div class="">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <x-page-header title="{{ __('Room ' . $room->name . ' Details') }}"
                    description="Details of components and temperature monitor of the room."
                    :backRoute="route('rooms.index')">
                    <div class="flex gap-2">
                        <x-button size="sm" variant="outline" href="{{ route('rooms.edit', $room) }}"
                            title="Edit Room Specifications">
                            <i class="fa fa-edit me-3"></i>
                        </x-button>
                        <!-- trigger modal to add temperature profile -->
                        <x-button size="sm" variant="primary" x-data @click="$dispatch('open-profile-modal')"
                            title="Add Temperature Profile">
                            <i class="fa fa-thermometer-half me-3"></i>
                        </x-button>
                        <!-- trigger modal to add Cooling System -->
                        <x-button size="sm" variant="primary" x-data @click="$dispatch('open-cooling-modal')"
                            title="Add Cooling System">
                            <i class="fa fa-snowflake me-3"></i>
                        </x-button>
                    </div>
                </x-page-header>
                <div class="mb-6 flex flex-row sm:flex-row justify-between items-stretch gap-4 mt-4">
                    <!-- Current Temp Card -->

                    <div
                        class="dashboard-card bg-blue-100 border-blue-100 p-5 flex flex-col justify-between shadow shadow-gray-200 flex-1 w-full">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Live
                                Temp</span>
                            <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <div class="flex items-baseline gap-1 mt-1">
                            <span class="text-3xl font-black font-mono text-slate-900 leading-none"
                                x-text="temperature.toFixed(1)"></span>
                            <span class="text-sm font-bold text-slate-500">°C</span>
                        </div>
                    </div>

                    <!-- Status Card -->
                    <div
                        class="dashboard-card bg-green-50 border-green-100 p-5 flex flex-col justify-between shadow shadow-gray-200 flex-1 w-full">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">System
                                Status</span>
                            <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="flex items-center gap-2 mt-2">
                            <span class="relative flex h-3 w-3">
                                <span
                                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                            </span>
                            <span class="text-xl font-black text-slate-800 leading-none" x-text="status"></span>
                        </div>
                    </div>

                    <!-- Last Inspection Card -->
                    <div
                        class="dashboard-card bg-indigo-50 border-indigo-100 p-5 flex flex-col justify-between shadow shadow-gray-200 flex-1 w-full">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Last
                                Inspection</span>
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                                </path>
                            </svg>
                        </div>
                        <span class="text-lg font-bold text-slate-800 mt-2 leading-none" x-text="lastInspection"></span>
                    </div>

                </div>

                <!-- LEFT COLUMN (Span 8) -->
                <div class="lg:col-span-8 flex flex-col gap-6">


                    <!-- 2. Room Layout Visualization -->
                    <div class="dashboard-card overflow-hidden flex flex-col">
                        <div class="px-5 py-3 border-b border-slate-100 flex justify-between items-center bg-white">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4">
                                    </path>
                                </svg>
                                <h3 class="text-xs font-black uppercase tracking-widest text-slate-600">Spatial Layout
                                </h3>
                            </div>
                            <span
                                class="px-2 py-0.5 bg-slate-50 text-[10px] font-bold text-slate-500 rounded border border-slate-200">Overhead
                                View</span>
                        </div>
                        <div class="p-6 bg-white flex flex-col relative w-full items-center justify-center">
                            <div
                                class="w-full max-w-2xl relative rounded-xl min-h-[350px] aspect-[4/3] md:aspect-[16/9] flex items-center justify-center overflow-hidden border-2 border-slate-200 bg-white blueprint-bg shadow-inner group">

                                <!-- Dummy Outline for Room -->
                                <div class="absolute inset-8 border-4 border-slate-300 rounded-lg pointer-events-none">
                                </div>
                                <!-- Door element -->
                                <div
                                    class="absolute top-8 left-1/2 -ml-8 w-16 h-2 bg-white flex items-center justify-center">
                                    <div class="w-full h-1 bg-slate-300 dashed"></div>
                                </div>

                                <!-- Interactive Assets -->
                                <template x-for="asset in mapAssets" :key="asset.id">
                                    <div class="absolute w-6 h-6 -ml-3 -mt-3 rounded-full flex items-center justify-center cursor-pointer transition-all duration-300 z-10 hover:z-20 shadow-md ring-4 ring-white"
                                        :style="`left: ${asset.x}%; top: ${asset.y}%; transform: scale(${hoveredAsset === asset.id ? 1.3 : 1});`"
                                        @mouseenter="hoveredAsset = asset.id" @mouseleave="hoveredAsset = null"
                                        :class="statusColorClass(asset.status, true)">

                                        <span x-show="asset.status === 'operational'"
                                            class="absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-50 animate-ping"></span>

                                        <svg class="w-3 h-3 text-white relative z-10" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                        </svg>

                                        <!-- Tooltip -->
                                        <div x-show="hoveredAsset === asset.id"
                                            x-transition:enter="transition ease-out duration-100"
                                            x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                                            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                            x-transition:leave="transition ease-in duration-75"
                                            x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                                            x-transition:leave-end="opacity-0 translate-y-2 scale-95"
                                            class="absolute bottom-full mb-3 bg-slate-800 text-white min-w-[130px] rounded-lg shadow-xl pointer-events-none border border-slate-700 overflow-hidden">
                                            <div
                                                class="px-3 py-2 border-b border-slate-700/50 flex justify-between items-center gap-3">
                                                <span class="text-[10px] font-black tracking-widest uppercase"
                                                    x-text="asset.name"></span>
                                                <span class="w-1.5 h-1.5 rounded-full"
                                                    :class="statusColorClass(asset.status, true)"></span>
                                            </div>
                                            <div class="px-3 py-2 bg-slate-900 flex justify-between items-center">
                                                <span
                                                    class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">TargetTemp</span>
                                                <span class="text-xs font-mono font-bold text-slate-100"
                                                    x-text="asset.temp + '°C'"></span>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- 5. Temperature Monitoring Graph -->
                    <div class="dashboard-card overflow-hidden">
                        <div class="px-5 py-3 border-b border-slate-100 flex justify-between items-center bg-white">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z">
                                    </path>
                                </svg>
                                <h3 class="text-xs font-black uppercase tracking-widest text-slate-600">Temp Analytics
                                </h3>
                            </div>
                            <div class="flex gap-1.5 p-1 bg-slate-100 rounded-lg">
                                <button
                                    class="px-2 py-1 text-[9px] font-black uppercase tracking-wider text-slate-500 hover:text-slate-800 rounded transition">12H</button>
                                <button
                                    class="px-2 py-1 text-[9px] font-black uppercase tracking-wider bg-white shadow-sm text-indigo-600 rounded">24H</button>
                                <button
                                    class="px-2 py-1 text-[9px] font-black uppercase tracking-wider text-slate-500 hover:text-slate-800 rounded transition">7D</button>
                            </div>
                        </div>
                        <!-- Chart Stats -->
                        <div
                            class="flex items-center justify-around gap-1.5 bg-slate-50/50 border-b border-slate-100 py-3">
                            <div class="text-center px-2">
                                <div class="text-[9px] font-black uppercase tracking-widest text-slate-400"
                                    title="Target Temp from Active Profile">Best Target</div>
                                <div
                                    class="text-xl font-black font-mono {{ $activeProfile ? 'text-indigo-600' : 'text-slate-300' }} mt-0.5">
                                    {{ $activeProfile ? number_format($activeProfile->profile->target_temp, 1) . '°C' : 'N/A' }}
                                </div>
                            </div>
                            <div class="text-center px-2">
                                <div class="text-[9px] font-black uppercase tracking-widest text-slate-400"
                                    title="Min Allowed Temp from Active Profile">Allowed min</div>
                                <div
                                    class="text-xl font-black font-mono {{ $activeProfile ? 'text-cyan-600' : 'text-slate-300' }} mt-0.5">
                                    {{ $activeProfile ? number_format($activeProfile->profile->min_temp, 1) . '°C' : 'N/A' }}
                                </div>
                            </div>
                            <div class="text-center px-2">
                                <div class="text-[9px] font-black uppercase tracking-widest text-slate-400"
                                    title="Max Allowed Temp from Active Profile">Allowed max</div>
                                <div
                                    class="text-xl font-black font-mono {{ $activeProfile ? 'text-red-500' : 'text-slate-300' }} mt-0.5">
                                    {{ $activeProfile ? number_format($activeProfile->profile->max_temp, 1) . '°C' : 'N/A' }}
                                </div>
                            </div>

                            <!-- TODO: Replace below with live sensor analytics when available -->
                            <div class="text-center px-2 border-l border-slate-200 pl-4">
                                <div class="text-[9px] font-black uppercase tracking-widest text-slate-400">Min Recorded
                                </div>
                                <div class="text-xl font-black font-mono text-cyan-600 mt-0.5">-22.4°C</div>
                            </div>
                            <div class="text-center px-2">
                                <div class="text-[9px] font-black uppercase tracking-widest text-slate-400">Max Recorded
                                </div>
                                <div class="text-xl font-black font-mono text-indigo-500 mt-0.5">-16.8°C</div>
                            </div>
                            <div class="text-center px-2">
                                <div class="text-[9px] font-black uppercase tracking-widest text-slate-400">24h Average
                                </div>
                                <div class="text-xl font-black font-mono text-slate-800 mt-0.5">-18.6°C</div>
                            </div>
                        </div>
                        <!-- Canvas container mapped with Alpine x-ref to lazy load Chart.js -->
                        <div class="p-5 bg-white relative h-72 w-full" x-ref="tempChartContainer">
                            <canvas x-ref="tempChart" class="w-full h-full"></canvas>
                        </div>
                    </div>

                </div>

                <!-- RIGHT COLUMN (Span 4) -->
                <div class="lg:col-span-4 flex flex-col gap-6">

                    <!-- Temperature Profile Timeline -->
                    <div class="dashboard-card overflow-hidden">
                        <div class="px-5 py-3 border-b border-slate-100 flex justify-between items-center bg-white">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <h3 class="text-xs font-black uppercase tracking-widest text-slate-600">Active Profile
                                </h3>
                            </div>
                            <button x-data @click="$dispatch('open-profile-modal')"
                                class="text-[9px] font-black uppercase tracking-widest px-2 py-1 bg-indigo-50 text-indigo-600 hover:bg-indigo-100 rounded transition">Change</button>
                        </div>

                        <div class="p-5 bg-white">
                            @if($activeProfile)
                                <div class="flex flex-col mb-4">
                                    <span class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Currently
                                        Running</span>
                                    <span
                                        class="text-lg font-black text-slate-800">{{ $activeProfile->profile->name }}</span>
                                    <div class="flex items-center gap-3 mt-2">
                                        <div class="text-[10px] font-bold text-slate-400">Target: <span
                                                class="text-indigo-600 font-black">{{ $activeProfile->profile->target_temp }}°C</span>
                                        </div>
                                        <div
                                            class="px-1.5 py-0.5 rounded text-[9px] font-black uppercase {{ $activeProfile->profile->product_type === 'frozen' ? 'bg-blue-100 text-blue-600' : ($activeProfile->profile->product_type === 'chilled' ? 'bg-emerald-100 text-emerald-600' : 'bg-red-100 text-red-600') }}">
                                            {{ $activeProfile->profile->product_type }}
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="py-4 text-center">
                                    <span class="text-sm font-bold text-slate-400">No active profile assigned.</span>
                                </div>
                            @endif

                            <!-- Mini Timeline -->
                            <div class="mt-4 border-t border-slate-100 pt-4">
                                <h4 class="text-[9px] font-black uppercase tracking-widest text-slate-400 mb-3">
                                    Assignment History (Last 3)</h4>
                                <div
                                    class="space-y-3 relative before:absolute before:inset-0 before:ml-1.5 md:before:mx-auto md:before:translate-x-0 before:h-full before:w-0.5 before:bg-gradient-to-b before:from-transparent before:via-slate-200 before:to-transparent">
                                    @forelse($timeline->take(3) as $assignment)
                                        <div
                                            class="relative flex items-center justify-between md:justify-normal group is-active">
                                            <div class="w-[calc(100%-1.5rem)] pl-3">
                                                <div class="flex flex-col">
                                                    <span
                                                        class="text-[10px] font-black {{ is_null($assignment->end_date) ? 'text-indigo-500' : 'text-slate-500' }}">{{ $assignment->profile->name }}</span>
                                                    <span
                                                        class="text-[9px] font-bold text-slate-400">{{ $assignment->start_date->format('M d, Y') }}
                                                        -
                                                        {{ $assignment->end_date ? $assignment->end_date->format('M d, Y') : 'Present' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">No
                                            history available.</div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="dashboard-card overflow-hidden">
                        <div
                            class="px-5 py-3 border-b border-slate-100 bg-white shadow-sm flex justify-between items-center">
                            <h3 class="text-xs font-black uppercase tracking-widest text-slate-600">Room Specs</h3>
                            @if($room->layout)
                                <span
                                    class="text-[9px] font-black uppercase tracking-widest px-2 py-0.5 bg-slate-100 text-slate-500 rounded border border-slate-200">{{ $room->layout->name }}</span>
                            @endif
                        </div>
                        <div class="p-0 bg-white">
                            <ul class="divide-y divide-slate-100 grid grid-cols-2">
                                @if($room->layout && is_array($room->layout->layout_dimensions))
                                    @php
                                        $dim = $room->layout->layout_dimensions;
                                        $volume = ($dim['length'] ?? 0) * ($dim['width'] ?? 0) * ($dim['height'] ?? 0);
                                    @endphp
                                    <li
                                        class="px-5 py-3 flex justify-between items-center group hover:bg-slate-50 transition">
                                        <span
                                            class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Dimensions</span>
                                        <span class="text-sm font-black text-slate-800">{{ $dim['length'] ?? 0 }}m ×
                                            {{ $dim['width'] ?? 0 }}m × {{ $dim['height'] ?? 0 }}m</span>
                                    </li>
                                    <li
                                        class="px-5 py-3 flex justify-between items-center group hover:bg-slate-50 transition">
                                        <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Storage
                                            Volume</span>
                                        <span class="text-sm font-black text-slate-800">{{ number_format($volume, 0) }}
                                            m³</span>
                                    </li>
                                    <li
                                        class="px-5 py-3 flex justify-between items-center group hover:bg-slate-50 transition">
                                        <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Wall
                                            Type/Thick</span>
                                        <span class="text-sm font-black text-slate-800">PIR /
                                            {{ number_format($room->layout->wall_thickness * 10, 0) }}mm</span>
                                    </li>
                                    <li
                                        class="px-5 py-3 flex justify-between items-center group hover:bg-slate-50 transition">
                                        <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Main
                                            Door Pos</span>
                                        <span
                                            class="text-sm font-black text-slate-800">{{ ucfirst($room->layout->door_position ?? 'Center') }}</span>
                                    </li>
                                @else
                                    <li class="px-5 py-8 text-center text-slate-400 font-bold text-xs italic">
                                        No layout template assigned.
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>

                    <!-- 4. Refrigeration System Tree -->
                    <div class="dashboard-card flex flex-col max-h-[400px]">
                        <div
                            class="px-5 py-3 border-b border-slate-100 bg-white flex justify-between items-center shrink-0">
                            <h3 class="text-xs font-black uppercase tracking-widest text-slate-600">Assets Hierarchy
                            </h3>
                            <button
                                class="text-[9px] font-black uppercase tracking-widest text-indigo-500 hover:text-indigo-700">Expand
                                All</button>
                        </div>
                        <div class="p-3 bg-white overflow-y-auto grow custom-scrollbar">
                            <template x-for="node in systemTree" :key="node.id">
                                <div class="mb-1">
                                    <!-- Parent Node -->
                                    <button @click="node.expanded = !node.expanded"
                                        class="w-full flex items-center gap-2 p-2 focus:bg-slate-50 hover:bg-slate-50 rounded-lg transition text-left group border border-transparent hover:border-slate-100">
                                        <svg class="w-4 h-4 text-slate-400 transition-transform duration-200"
                                            :class="node.expanded ? 'rotate-90 text-indigo-500' : ''" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                d="M9 5l7 7-7 7"></path>
                                        </svg>
                                        <span
                                            class="w-2.5 h-2.5 rounded-full flex-shrink-0 shadow-sm border border-white"
                                            :class="statusColorClass(node.status, true)"></span>
                                        <span
                                            class="text-sm font-black text-slate-700 tracking-tight group-hover:text-slate-900 truncate"
                                            x-text="node.name"></span>
                                    </button>

                                    <!-- Children -->
                                    <div x-show="node.expanded" x-transition>
                                        <div class="ml-6 pl-3 border-l-2 border-slate-100 py-1 space-y-1">
                                            <template x-for="child in node.children" :key="child.id">
                                                <div
                                                    class="flex justify-between items-center p-1.5 hover:bg-slate-50 rounded-md transition cursor-pointer group">
                                                    <div class="flex items-center gap-2 overflow-hidden">
                                                        <span class="w-1.5 h-1.5 rounded-full flex-shrink-0"
                                                            :class="statusColorClass(child.status, true)"></span>
                                                        <span
                                                            class="text-[11px] font-bold text-slate-500 group-hover:text-slate-800 truncate"
                                                            x-text="child.name"></span>
                                                    </div>
                                                    <span
                                                        class="text-[9px] font-black uppercase tracking-wider px-1.5 py-0.5 rounded"
                                                        :class="child.status === 'operational' ? 'text-emerald-500 bg-emerald-50' : (child.status === 'warning' ? 'text-amber-600 bg-amber-50' : 'text-red-600 bg-red-50')"
                                                        x-text="child.status === 'operational' ? 'OK' : 'WARN'"></span>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- 6. Alerts Panel -->
                    <div class="dashboard-card flex flex-col">
                        <div
                            class="px-5 py-3 border-b border-slate-100 bg-white flex justify-between items-center bg-red-50/30">
                            <div class="flex items-center gap-2 text-red-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                    </path>
                                </svg>
                                <h3 class="text-xs font-black uppercase tracking-widest">Active Alerts</h3>
                            </div>
                            <span
                                class="px-2 py-0.5 text-[10px] font-black bg-white text-red-600 rounded-full border border-red-200 shadow-sm"
                                x-text="alerts.length"></span>
                        </div>
                        <div
                            class="p-0 bg-white divide-y divide-slate-100 max-h-[300px] overflow-y-auto custom-scrollbar">
                            <template x-for="alert in alerts" :key="alert.id">
                                <div class="px-5 py-4 hover:bg-slate-50 transition border-l-4 relative group"
                                    :class="alert.type === 'critical' ? 'border-red-500' : 'border-amber-400'">
                                    <div class="flex justify-between items-start gap-4">
                                        <div class="flex flex-col gap-1">
                                            <div class="text-[9px] font-black uppercase tracking-widest"
                                                :class="alert.type === 'critical' ? 'text-red-500' : 'text-amber-500'"
                                                x-text="alert.type"></div>
                                            <p class="text-xs font-bold text-slate-800 leading-snug"
                                                x-text="alert.message"></p>
                                        </div>
                                        <span class="text-[10px] font-bold text-slate-400 whitespace-nowrap mt-1"
                                            x-text="alert.time"></span>
                                    </div>
                                </div>
                            </template>
                            <div x-show="alerts.length === 0"
                                class="px-5 py-8 text-center text-sm font-bold text-slate-400 flex flex-col items-center">
                                <svg class="w-8 h-8 text-slate-200 mb-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                All systems normal.
                            </div>
                        </div>
                    </div>

                </div>

            </div>

            <!-- 7. Maintenance Section -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
                <div class="dashboard-card overflow-hidden">
                    <div class="px-5 py-4 border-b border-slate-100 bg-white flex justify-between items-center px-6">
                        <h3 class="text-xs font-black uppercase tracking-widest text-slate-600">Maintenance & Tasks</h3>
                        <a href="#"
                            class="text-[10px] font-black uppercase tracking-widest text-indigo-500 hover:text-indigo-700 transition flex items-center gap-1">
                            View All
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                                </path>
                            </svg>
                        </a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50/80 border-b border-slate-200">
                                    <th
                                        class="px-6 py-3 text-[9px] font-black uppercase tracking-widest text-slate-400 w-24">
                                        Type</th>
                                    <th
                                        class="px-6 py-3 text-[9px] font-black uppercase tracking-widest text-slate-400">
                                        Task Description</th>
                                    <th
                                        class="px-6 py-3 text-[9px] font-black uppercase tracking-widest text-slate-400">
                                        Assigned To</th>
                                    <th
                                        class="px-6 py-3 text-[9px] font-black uppercase tracking-widest text-slate-400">
                                        Due Date</th>
                                    <th
                                        class="px-6 py-3 text-[9px] font-black uppercase tracking-widest text-slate-400">
                                        Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white">
                                <template x-for="task in maintenanceTasks" :key="task.id">
                                    <tr class="hover:bg-slate-50 transition group">
                                        <td class="px-6 py-3 whitespace-nowrap">
                                            <span
                                                class="px-2 py-0.5 text-[9px] font-black uppercase tracking-widest rounded border"
                                                :class="task.type === 'Emergency' ? 'bg-red-50 text-red-600 border-red-100' : 'bg-indigo-50 text-indigo-600 border-indigo-100'"
                                                x-text="task.type"></span>
                                        </td>
                                        <td class="px-6 py-3">
                                            <span class="text-xs font-bold text-slate-800"
                                                x-text="task.description"></span>
                                        </td>
                                        <td class="px-6 py-3 whitespace-nowrap">
                                            <div class="flex items-center gap-2">
                                                <div class="w-5 h-5 rounded-full bg-slate-200 flex items-center justify-center text-[9px] font-black text-slate-600 border border-slate-300 shadow-sm"
                                                    x-text="task.assigneeInitials"></div>
                                                <span
                                                    class="text-xs font-bold text-slate-600 group-hover:text-slate-900 transition"
                                                    x-text="task.assignee"></span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-3 whitespace-nowrap">
                                            <span class="text-xs font-bold text-slate-500" x-text="task.dueDate"></span>
                                        </td>
                                        <td class="px-6 py-3 whitespace-nowrap">
                                            <div class="flex items-center gap-2">
                                                <span class="w-2 h-2 rounded-full"
                                                    :class="task.status === 'In Progress' ? 'bg-amber-400' : (task.status === 'Pending' ? 'bg-slate-300' : 'bg-emerald-500')"></span>
                                                <span
                                                    class="text-[10px] font-black uppercase tracking-wider text-slate-600"
                                                    x-text="task.status"></span>
                                            </div>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>

        <!-- Assign Profile Modal -->
        <div x-data="{ open: false }" x-show="open" @open-profile-modal.window="open = true" style="display: none;"
            class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">

            <div class="fixed inset-0 bg-slate-900/75 backdrop-blur-sm transition-opacity" @click="open = false"
                x-show="open" x-transition.opacity></div>

            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div x-show="open" x-transition.scale.origin.bottom
                    class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-slate-200">
                    <form action="{{ route('rooms.profiles.assign', $room) }}" method="POST">
                        @csrf
                        <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div
                                    class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-indigo-100 sm:mx-0 sm:h-10 sm:w-10">
                                    <svg class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                                    <h3 class="text-base font-black leading-6 text-slate-900 uppercase tracking-widest"
                                        id="modal-title">Assign Thermal Profile</h3>
                                    <div class="mt-4 space-y-4">
                                        <div>
                                            <x-input-label for="temperature_profile_id" value="Select Profile" />
                                            <select id="temperature_profile_id" name="temperature_profile_id" required
                                                class="rounded-md border-gray-300">
                                                <option value="">Choose a baseline...</option>
                                                @foreach($profiles as $profile)
                                                    <option value="{{ $profile->id }}">{{ $profile->name }}
                                                        ({{ number_format($profile->target_temp, 1) }}°C)</option>
                                                @endforeach
                                            </select>
                                            <x-input-error :messages="$errors->get('temperature_profile_id')"
                                                class="mt-2" />
                                        </div>
                                        <div>
                                            <x-input-label for="start_date" value="Start Date & Time" />
                                            <x-input type="datetime-local" id="start_date" name="start_date"
                                                value="{{ now()->format('Y-m-d\TH:i') }}" required />
                                            <x-input-error :messages="$errors->get('start_date')" class="mt-2" />
                                        </div>
                                        <p class="text-[10px] text-slate-400 italic font-bold">Assigning a new profile
                                            will automatically close the currently active profile.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="flex justify-end space-x-2 py-2 px-6">
                            <x-button variant="primary" size="sm" type="submit">Confirm</x-button>
                            <x-button variant="secondary" size="sm" type="button"
                                @click="open = false">Cancel</x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Assign Profile Modal -->
        <div x-data="{ open: false }" x-show="open" @open-cooling-modal.window="open = true" style="display: none;"
            class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">

            <div class="fixed inset-0 bg-slate-900/75 backdrop-blur-sm transition-opacity" @click="open = false"
                x-show="open" x-transition.opacity></div>

            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div x-show="open" x-transition.scale.origin.bottom
                    class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-slate-200">
                    <form action="{{ route('rooms.cooling-systems.assign', $room) }}" method="POST">
                        @csrf
                        <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div
                                    class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-indigo-100 sm:mx-0 sm:h-10 sm:w-10">
                                    <svg class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                                    <h3 class="text-base font-black leading-6 text-slate-900 uppercase tracking-widest"
                                        id="modal-title">Assign Thermal Profile</h3>
                                    <div class="mt-4 space-y-4">
                                        <div>
                                            <x-input-label for="temperature_profile_id" value="Select Profile" />
                                            <select id="temperature_profile_id" name="temperature_profile_id" required
                                                class="rounded-md border-gray-300">
                                                <option value="">Choose a baseline...</option>
                                                @foreach($profiles as $profile)
                                                    <option value="{{ $profile->id }}">{{ $profile->name }}
                                                        ({{ number_format($profile->target_temp, 1) }}°C)</option>
                                                @endforeach
                                            </select>
                                            <x-input-error :messages="$errors->get('temperature_profile_id')"
                                                class="mt-2" />
                                        </div>
                                        <div>
                                            <x-input-label for="start_date" value="Start Date & Time" />
                                            <x-input type="datetime-local" id="start_date" name="start_date"
                                                value="{{ now()->format('Y-m-d\TH:i') }}" required />
                                            <x-input-error :messages="$errors->get('start_date')" class="mt-2" />
                                        </div>
                                        <p class="text-[10px] text-slate-400 italic font-bold">Assigning a new profile
                                            will automatically close the currently active profile.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="flex justify-end space-x-2 py-2 px-6">
                            <x-button variant="primary" size="sm" type="submit">Confirm</x-button>
                            <x-button variant="secondary" size="sm" type="button"
                                @click="open = false">Cancel</x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Chart.js include -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>

        <!-- Alpine.js Application Logic -->
        <script>
            // Adding assets to the map

            document.addEventListener('alpine:init', () => {


                Alpine.data('roomDashboard', () => ({
                    temperature: -18.5,
                    status: 'Optimal',
                    lastInspection: 'Today, 08:30 AM',
                    hoveredAsset: null,
                    chartInitialized: false,

                    // Dummy Map Features setup
                    mapAssets: [
                        { id: 1, name: 'Evaporator A', x: 25, y: 35, status: 'operational', temp: -18.2 },
                        { id: 2, name: 'Evaporator B', x: 75, y: 35, status: 'warning', temp: -16.8 },
                        { id: 3, name: 'Sensor X', x: 50, y: 75, status: 'operational', temp: -18.5 }
                    ],

                    alerts: [
                        { id: 1, type: 'critical', message: 'Evaporator B temp rising above threshold', time: '10:45 AM' },
                        { id: 2, type: 'warning', message: 'Defrost cycle delayed by 15 mins', time: '09:12 AM' }
                    ],

                    systemTree: [
                        {
                            id: 'comp1', name: 'Rack System Alpha', status: 'operational', expanded: true,
                            children: [
                                { id: 'c1', name: 'Compressor 1', status: 'operational' },
                                { id: 'c2', name: 'Compressor 2', status: 'operational' }
                            ]
                        },
                        {
                            id: 'evap1', name: 'Evaporator Group', status: 'warning', expanded: true,
                            children: [
                                { id: 'f1', name: 'Unit A', status: 'operational' },
                                { id: 'f2', name: 'Unit B', status: 'warning' }
                            ]
                        }
                    ],

                    maintenanceTasks: [
                        { id: 1, type: 'Emergency', description: 'Inspect Evaporator B icing formation', assignee: 'John D.', assigneeInitials: 'JD', dueDate: 'Today, 14:00', status: 'In Progress' },
                        { id: 2, type: 'Scheduled', description: 'Monthly Sensor Calibration', assignee: 'Sarah M.', assigneeInitials: 'SM', dueDate: 'Tomorrow', status: 'Pending' }
                    ],

                    initDashboard() {
                        // Simulate Live Temperature Fluctuation
                        setInterval(() => {
                            this.temperature += (Math.random() * 0.4 - 0.2);
                        }, 5000);

                        // Setup Chart intersection observer for lazy-loading
                        this.$nextTick(() => {
                            if (!this.$refs.tempChartContainer) return;

                            const observer = new IntersectionObserver((entries) => {
                                if (entries[0].isIntersecting && !this.chartInitialized) {
                                    this.initChart();
                                    this.chartInitialized = true;
                                    observer.disconnect();
                                }
                            }, { threshold: 0.1 });

                            observer.observe(this.$refs.tempChartContainer);
                        });
                    },

                    statusColorClass(status, isBg = false) {
                        if (status === 'operational' || status === 'Optimal') return isBg ? 'bg-emerald-500' : 'text-emerald-500';
                        if (status === 'warning') return isBg ? 'bg-amber-500' : 'text-amber-500';
                        if (status === 'critical') return isBg ? 'bg-red-500' : 'text-red-500';
                        return isBg ? 'bg-slate-400' : 'text-slate-400';
                    },

                    initChart() {
                        if (!this.$refs.tempChart) return;

                        const ctx = this.$refs.tempChart.getContext('2d');

                        // Generate pseudo-random data for chart
                        const labels = Array.from({ length: 24 }, (_, i) => `${i}:00`);
                        const dataPoints = [];
                        let currentTemp = -18.5;
                        for (let i = 0; i < 24; i++) {
                            currentTemp += (Math.random() * 1.5 - 0.75);
                            dataPoints.push(currentTemp);
                        }

                        // Eye-catching gradient
                        const gradient = ctx.createLinearGradient(0, 0, 0, 300);
                        gradient.addColorStop(0, 'rgba(79, 70, 229, 0.2)'); // indigo-600
                        gradient.addColorStop(1, 'rgba(79, 70, 229, 0)');

                        new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: labels,
                                datasets: [{
                                    label: 'Temperature (°C)',
                                    data: dataPoints,
                                    borderColor: '#4f46e5',
                                    backgroundColor: gradient,
                                    borderWidth: 2,
                                    pointBackgroundColor: '#ffffff',
                                    pointBorderColor: '#4f46e5',
                                    pointBorderWidth: 2,
                                    pointRadius: 3,
                                    pointHoverRadius: 5,
                                    fill: true,
                                    tension: 0.4 // Smooth curve
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                interaction: {
                                    intersect: false,
                                    mode: 'index',
                                },
                                plugins: {
                                    legend: { display: false },
                                    tooltip: {
                                        backgroundColor: '#1e293b',
                                        titleFont: { size: 10, family: 'sans-serif', weight: 'bold' },
                                        bodyFont: { size: 12, family: 'monospace', weight: 'bold' },
                                        padding: 10,
                                        margin: 0,
                                        displayColors: false,
                                        callbacks: {
                                            label: function (context) { return `${context.parsed.y.toFixed(1)} °C`; }
                                        }
                                    }
                                },
                                scales: {
                                    y: {
                                        grid: { color: '#f8fafc', drawBorder: false },
                                        ticks: {
                                            font: { family: 'monospace', size: 10 },
                                            color: '#64748b',
                                            callback: function (value) { return value + '°'; }
                                        }
                                    },
                                    x: {
                                        grid: { display: false, drawBorder: false },
                                        ticks: {
                                            font: { family: 'sans-serif', size: 9, weight: 'bold' },
                                            color: '#94a3b8',
                                            maxTicksLimit: 8
                                        }
                                    }
                                }
                            }
                        });
                    }
                }));
            });
        </script>
</x-app-layout>