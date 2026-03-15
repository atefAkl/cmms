@php
    $modules = [
        'Equipment' => [
            ['label' => 'Rooms', 'route' => 'rooms.index', 'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'],
            ['label' => 'Ref. Systems', 'route' => 'refrigeration-systems.index', 'icon' => 'M13 10V3L4 14h7v7l9-11h-7z'],
        ],
        'Maintenance' => [
            ['label' => 'Tasks', 'route' => 'maintenance.index', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4'],
            ['label' => 'PM Schedules', 'route' => 'pm-schedules.index', 'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
        ],
        'Stock' => [
            ['label' => 'Inventory', 'route' => 'inventory-items.index', 'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4'],
            ['label' => 'Purchases', 'route' => 'procurement.index', 'icon' => 'M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z'],
        ],
        'Monitoring' => [
            ['label' => 'Alerts', 'route' => 'alerts.index', 'icon' => 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9'],
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
    <nav class="flex-1 overflow-y-auto py-6 px-4 space-y-8 custom-scrollbar">
        @foreach($modules as $group => $items)
            <div>
                <h3 class="px-4 text-xs font-semibold text-gray-500 uppercase tracking-widest mb-3">{{ $group }}</h3>
                <div class="space-y-1">
                    @foreach($items as $item)
                        @php $isActive = Route::is($item['route']); @endphp
                        <a href="{{ route($item['route']) }}"
                            class="flex items-center px-4 py-2.5 rounded-lg text-sm font-medium transition-all group {{ $isActive ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/20' : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                            <svg class="w-5 h-5 mr-3 {{ $isActive ? 'text-white' : 'text-gray-500 group-hover:text-gray-300' }}"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}">
                                </path>
                            </svg>
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
            <a href="#" class="p-1.5 rounded-md hover:bg-gray-800 text-gray-500 hover:text-white transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                    </path>
                </svg>
            </a>
        </div>
    </div>
</aside>