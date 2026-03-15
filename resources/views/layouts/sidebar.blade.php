<aside class="bg-gray-800 text-white w-64 min-h-screen flex flex-col" x-data="{ openEquipment: false, openMaintenance: false, openInventory: false, openMonitoring: false }">
    <div class="h-16 flex items-center justify-center border-b border-gray-700">
        <span class="text-xl font-bold tracking-wider">CMMS Core</span>
    </div>

    <nav class="flex-1 px-2 py-4 space-y-2">
        <!-- Dashboard Menu -->
        <a href="{{ route('dashboard') }}" class="block px-4 py-2 rounded hover:bg-gray-700 {{ request()->routeIs('dashboard') ? 'bg-gray-700' : '' }}">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                Dashboard
            </div>
        </a>

        <!-- Equipment -->
        <div>
            <button @click="openEquipment = !openEquipment" class="w-full text-left px-4 py-2 rounded hover:bg-gray-700 flex justify-between items-center bg-gray-800">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    Equipment
                </div>
                <svg :class="{'rotate-180': openEquipment}" class="w-4 h-4 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>
            <div x-show="openEquipment" x-transition class="pl-8 space-y-1 mt-1">
                <a href="{{ route('rooms.index') }}" class="block px-4 py-2 text-sm text-gray-300 rounded hover:bg-gray-700 hover:text-white {{ request()->routeIs('rooms.*') ? 'text-white font-bold' : '' }}">Rooms</a>
            </div>
        </div>

        <!-- Maintenance -->
        <div>
            <button @click="openMaintenance = !openMaintenance" class="w-full text-left px-4 py-2 rounded hover:bg-gray-700 flex justify-between items-center bg-gray-800">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    Maintenance
                </div>
                <svg :class="{'rotate-180': openMaintenance}" class="w-4 h-4 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>
            <div x-show="openMaintenance" x-transition class="pl-8 space-y-1 mt-1">
                <a href="{{ route('maintenance.index') }}" class="block px-4 py-2 text-sm text-gray-300 rounded hover:bg-gray-700 hover:text-white {{ request()->routeIs('maintenance.*') ? 'text-white font-bold' : '' }}">Work Orders</a>
                <a href="{{ route('pm-schedules.index') }}" class="block px-4 py-2 text-sm text-gray-300 rounded hover:bg-gray-700 hover:text-white {{ request()->routeIs('pm-schedules.*') ? 'text-white font-bold' : '' }}">PM Schedules</a>
            </div>
        </div>

        <!-- Inventory -->
        <div>
            <button @click="openInventory = !openInventory" class="w-full text-left px-4 py-2 rounded hover:bg-gray-700 flex justify-between items-center bg-gray-800">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    Inventory
                </div>
                <svg :class="{'rotate-180': openInventory}" class="w-4 h-4 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>
            <div x-show="openInventory" x-transition class="pl-8 space-y-1 mt-1">
                <a href="{{ route('inventory.index') }}" class="block px-4 py-2 text-sm text-gray-300 rounded hover:bg-gray-700 hover:text-white {{ request()->routeIs('inventory.*') ? 'text-white font-bold' : '' }}">Spare Parts</a>
            </div>
        </div>

        <!-- Monitoring -->
        <div>
            <button @click="openMonitoring = !openMonitoring" class="w-full text-left px-4 py-2 rounded hover:bg-gray-700 flex justify-between items-center bg-gray-800">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    Monitoring
                </div>
                <svg :class="{'rotate-180': openMonitoring}" class="w-4 h-4 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>
            <div x-show="openMonitoring" x-transition class="pl-8 space-y-1 mt-1">
                <a href="{{ route('alerts.index') }}" class="block px-4 py-2 text-sm text-gray-300 rounded hover:bg-gray-700 hover:text-white {{ request()->routeIs('alerts.*') ? 'text-white font-bold' : '' }}">Alerts</a>
            </div>
        </div>
    </nav>
</aside>
