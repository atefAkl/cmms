<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('System Details') }}: {{ $refrigerationSystem->name }}
            </h2>
            <div class="flex space-x-3">
                <a href="{{ route('refrigeration-systems.edit', $refrigerationSystem) }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 transition">
                    {{ __('Edit System') }}
                </a>
                <a href="{{ route('refrigeration-systems.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 transition shadow-sm">
                    {{ __('Back to List') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <div class="lg:col-span-2 space-y-8">
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl p-8 border border-gray-100">
                        <div class="flex items-center justify-between mb-8 border-b border-gray-50 pb-6">
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900">{{ $refrigerationSystem->name }}</h3>
                                <p class="text-sm text-gray-500 mt-1">Installed on {{ $refrigerationSystem->installed_at ? \Illuminate\Support\Carbon::parse($refrigerationSystem->installed_at)->format('F d, Y') : 'Unknown Date' }}</p>
                            </div>
                            <span class="px-4 py-1.5 rounded-full text-sm font-bold {{ $refrigerationSystem->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ strtoupper($refrigerationSystem->status) }}
                            </span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-4">
                                <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest">General Information</h4>
                                <div class="bg-gray-50 rounded-xl p-4 flex items-center">
                                    <div class="p-2 bg-white rounded-lg shadow-sm mr-4 text-indigo-500">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">Room Location</p>
                                        <p class="text-sm font-semibold text-gray-900">{{ $refrigerationSystem->room->name }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100">
                        <div class="px-8 py-6 border-b border-gray-50 flex items-center justify-between">
                            <h3 class="font-bold text-gray-900 border-l-4 border-indigo-500 pl-4">System Devices</h3>
                            <a href="{{ route('system-devices.create', ['system_id' => $refrigerationSystem->id]) }}" class="inline-flex items-center px-3 py-1.5 bg-indigo-50 text-indigo-600 rounded-lg font-bold text-[10px] uppercase tracking-widest hover:bg-indigo-600 hover:text-white transition shadow-sm">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                                Attach New Device
                            </a>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead class="bg-gray-50 text-gray-500 text-[10px] uppercase font-black tracking-widest">
                                    <tr>
                                        <th class="px-8 py-4">Device Name</th>
                                        <th class="px-8 py-4">Category</th>
                                        <th class="px-8 py-4">Installation Date</th>
                                        <th class="px-8 py-4 text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50">
                                    @forelse($refrigerationSystem->systemDevices as $sDevice)
                                        <tr class="hover:bg-gray-50 transition text-sm">
                                            <td class="px-8 py-4 font-bold text-gray-900">
                                                {{ $sDevice->name }}
                                            </td>
                                            <td class="px-8 py-4">
                                                <span class="text-xs px-2 py-0.5 rounded font-bold uppercase bg-blue-100 text-blue-700">
                                                    {{ $sDevice->device->name }}
                                                </span>
                                            </td>
                                            <td class="px-8 py-4 text-gray-500 font-medium">
                                                {{ $sDevice->installed ? $sDevice->installed->format('M d, Y') : 'N/A' }}
                                            </td>
                                            <td class="px-8 py-4 text-right">
                                                <div class="flex items-center justify-end space-x-2">
                                                    <a href="{{ route('system-devices.edit', $sDevice) }}" class="p-2 text-indigo-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition" title="Edit Device Details">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-8 py-12 text-center">
                                                <div class="flex flex-col items-center">
                                                    <div class="p-4 bg-gray-50 rounded-full mb-4">
                                                        <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                                                    </div>
                                                    <span class="text-gray-400 italic">No devices attached to this system.</span>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="space-y-8">
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl p-6 border border-gray-100 overflow-hidden relative">
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Latest Temperature</h4>
                        <div class="flex items-center">
                            <span class="text-5xl font-black text-gray-900 tracking-tighter">{{ $refrigerationSystem->temperatureReadings->first()->temperature ?? 'N/A' }}</span>
                            @if($refrigerationSystem->temperatureReadings->first()) <span class="text-2xl font-bold text-blue-500 ml-2">°C</span> @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
