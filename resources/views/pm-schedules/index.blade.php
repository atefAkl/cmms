<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Preventive Maintenance Schedules') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">All PM Schedules</h3>
                        <a href="{{ route('pm-schedules.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('Add Schedule') }}
                        </a>
                    </div>

                    @if (session('success'))
                        <div class="mb-4 font-medium text-sm text-green-600">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="border-b p-3">Description</th>
                                    <th class="border-b p-3">Equipment</th>
                                    <th class="border-b p-3">Priority</th>
                                    <th class="border-b p-3">Interval (Days)</th>
                                    <th class="border-b p-3">Next Due</th>
                                    <th class="border-b p-3 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($schedules as $schedule)
                                    <tr class="hover:bg-gray-50 border-b">
                                        <td class="p-3">{{ $schedule->description }}</td>
                                        <td class="p-3 text-sm text-gray-600">{{ class_basename($schedule->equipment_type) }} #{{ $schedule->equipment_id }}</td>
                                        <td class="p-3">
                                            <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $schedule->priority == 'critical' ? 'bg-red-100 text-red-800' : ($schedule->priority == 'high' ? 'bg-orange-100 text-orange-800' : 'bg-gray-100 text-gray-800') }}">
                                                {{ ucfirst($schedule->priority) }}
                                            </span>
                                        </td>
                                        <td class="p-3 text-sm">{{ $schedule->interval_days }}</td>
                                        <td class="p-3 text-sm {{ now()->greaterThanOrEqualTo($schedule->next_due) ? 'text-red-600 font-bold' : '' }}">
                                            {{ $schedule->next_due->format('Y-m-d') }}
                                        </td>
                                        <td class="p-3 text-right space-x-2 whitespace-nowrap">
                                            <a href="{{ route('pm-schedules.edit', $schedule) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">Edit</a>
                                            <form method="POST" action="{{ route('pm-schedules.destroy', $schedule) }}" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 text-sm font-medium" onclick="return confirm('Are you sure?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="p-4 text-center text-gray-500">No PM schedules found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $schedules->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
