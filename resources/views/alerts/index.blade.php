<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('System Alerts') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium mb-4">Temperature & System Alerts</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="border-b p-3">Date</th>
                                    <th class="border-b p-3">Location</th>
                                    <th class="border-b p-3">Description</th>
                                    <th class="border-b p-3">Severity</th>
                                    <th class="border-b p-3">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($alerts as $alert)
                                    <tr class="hover:bg-gray-50 border-b">
                                        <td class="p-3">{{ $alert->created_at->format('Y-m-d H:i') }}</td>
                                        <td class="p-3">{{ $alert->room ? $alert->room->name : 'System' }}</td>
                                        <td class="p-3">Temperature {{ $alert->temperature }}°C exceeded threshold {{ $alert->threshold }}°C</td>
                                        <td class="p-3">
                                            <span class="px-2 py-1 rounded-full text-xs font-bold {{ $alert->severity == 'critical' ? 'bg-red-200 text-red-900' : 'bg-yellow-200 text-yellow-900' }}">
                                                {{ strtoupper($alert->severity) }}
                                            </span>
                                        </td>
                                        <td class="p-3">
                                            <span class="px-2 py-1 rounded text-xs font-semibold {{ $alert->status == 'resolved' ? 'bg-green-100 text-green-800' : 'bg-gray-200 text-gray-800' }}">
                                                {{ ucfirst($alert->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="p-4 text-center text-gray-500">No alerts found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $alerts->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
