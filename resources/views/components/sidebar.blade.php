@php
    $modules = [
        'Equipment' => [
            ['label' => 'Rooms', 'route' => 'rooms.index', 'icon' => 'fa fa-door-open'],
            ['label' => 'Ref. Systems', 'route' => 'refrigeration-systems.index', 'icon' => 'fa fa-snowflake'],
        ],
        'Assets' => [
            ['label' => 'Item Categories', 'route' => 'item-categories.index', 'icon' => 'fa fa-tags'],
            ['label' => 'Inventory Items', 'route' => 'inventory-items.index', 'icon' => 'fa fa-boxes'],
            ['label' => 'Hardware Assets', 'route' => 'assets.index', 'icon' => 'fa fa-microchip'],
        ],
        'Maintenance' => [
            ['label' => 'Tasks', 'route' => 'maintenance.index', 'icon' => 'fa fa-tasks'],
            ['label' => 'PM Schedules', 'route' => 'pm-schedules.index', 'icon' => 'fa fa-calendar-alt'],
        ],
        'Stock' => [
            ['label' => 'Purchases', 'route' => 'procurement.index', 'icon' => 'fa fa-shopping-cart'],
        ],
        'Monitoring' => [
            ['label' => 'Alerts', 'route' => 'alerts.index', 'icon' => 'fa fa-exclamation-triangle'],
            ['label' => 'Temperature', 'route' => 'monitoring.temperature', 'icon' => 'fa fa-thermometer-half'],
            ['label' => 'Humidity', 'route' => 'monitoring.humidity', 'icon' => 'fa fa-tint'],
        ]
    ];
@endphp

<!-- Sidebar Overlay for Mobile -->
<div x-show="sidebarOpen" class="fixed inset-0 z-40 bg-gray-900/50 backdrop-blur-sm lg:hidden"
    x-transition:enter="transition-opacity ease-out duration-300" x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-in duration-200"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="sidebarOpen = false"></div>

<!-- Sidebar -->
<aside
    class="w-64 h-screen bg-gray-900 text-white flex-shrink-0 flex flex-col z-50 
             fixed inset-y-0 left-0 transform transition-transform duration-300 lg:relative lg:translate-x-0 lg:static lg:inset-auto lg:transform-none"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">

    <!-- Sidebar Header -->
    <div class="h-16 flex items-center justify-between px-6 border-b border-white/5 bg-gray-950/20">
        <div class="flex items-center gap-2">
            <x-application-logo class="w-8 h-8 fill-current text-indigo-500" />
            <span class="text-lg font-bold tracking-tight">CMMS<span class="text-indigo-500">.</span>Core</span>
        </div>
        <button @click="sidebarOpen = false"
            class="lg:hidden p-2 rounded-md hover:bg-gray-800 text-gray-400 hover:text-white">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>

    <!-- Sidebar Body -->
    <nav class="flex-1 overflow-y-auto py-1 px-4 space-y-1 custom-scrollbar">
        @foreach($modules as $group => $items)
            <div>
                <h3 class="px-4 text-xs font-semibold text-gray-500 uppercase tracking-widest mb-1">{{ $group }}</h3>
                <div class="space-y-1">
                    @foreach($items as $item)
                        @php $isActive = Route::is($item['route']); @endphp
                        <a href="{{ route($item['route']) }}"
                            class="flex items-center px-4 py-1 rounded-lg text-sm font-medium transition-all group {{ $isActive ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/20' : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                            <i
                                class="{{ $item['icon'] }} w-5 h-5 flex items-center justify-center mr-3 text-lg {{ $isActive ? 'text-white' : 'text-gray-500 group-hover:text-gray-300' }}"></i>
                            {{ $item['label'] }}
                        </a>
                    @endforeach
                </div>
            </div>
        @endforeach
    </nav>

    <!-- Sidebar Footer -->
    <div class="p-4 border-t border-white/5 bg-gray-950/20">
        <div class="flex items-center justify-between px-2 py-2">
            <div class="flex items-center text-xs text-gray-500">
                <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                v1.2.4-stable
            </div>
            <a href="{{ route('settings.index') }}"
                class="p-1.5 rounded-md hover:bg-gray-800 {{ Route::is('settings.*') ? 'text-indigo-500 bg-gray-950/40' : 'text-gray-500' }} hover:text-white transition">
                <i class="fa fa-cog w-5 h-5 flex items-center justify-center text-lg"></i>
            </a>
        </div>
    </div>
</aside>