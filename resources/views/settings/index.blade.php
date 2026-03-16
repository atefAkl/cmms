<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('System Settings') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Device Types Category -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 p-6 hover:shadow-md transition group">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-indigo-50 rounded-xl text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
                        </div>
                        <span class="text-[10px] font-black uppercase tracking-widest text-gray-400">Hardware</span>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-1">Device Catalog</h3>
                    <p class="text-sm text-gray-500 mb-6">Manage global device types and hardware templates.</p>
                    <a href="{{ route('devices.index') }}" class="inline-flex items-center text-sm font-bold text-indigo-600 hover:text-indigo-800 transition">
                        Manage Types
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>

                <!-- Units of Measurement -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 p-6 hover:shadow-md transition group">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-blue-50 rounded-xl text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        </div>
                        <span class="text-[10px] font-black uppercase tracking-widest text-gray-400">Metrics</span>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-1">Units & Measures</h3>
                    <p class="text-sm text-gray-500 mb-6">Define temperature, pressure, and weight units.</p>
                    <a href="#" class="inline-flex items-center text-sm font-bold text-indigo-600 hover:text-indigo-800 transition">
                        Configure Units
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>

                <!-- Asset Categories -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 p-6 hover:shadow-md transition group">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-purple-50 rounded-xl text-purple-600 group-hover:bg-purple-600 group-hover:text-white transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                        </div>
                        <span class="text-[10px] font-black uppercase tracking-widest text-gray-400">Inventory</span>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-1">Asset Categories</h3>
                    <p class="text-sm text-gray-500 mb-6">Classify spare parts and consumables.</p>
                    <a href="{{ route('item-categories.index') }}" class="inline-flex items-center text-sm font-bold text-indigo-600 hover:text-indigo-800 transition">
                        Manage Categories
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
