<x-app-layout>
    <form action="{{ route('monitoring.temperature.store') }}" method="POST">
        @csrf
        <x-page-header title="Temperature Monitoring"
            description="Record and monitor temperatures for all refrigeration and freezing rooms (bulk save).">
            <div class="flex items-center gap-4">
                @if(\App\Models\SystemSetting::get('temp_allow_user_time', false))
                    <div class="flex flex-col">
                        <label class="text-[10px] font-bold text-gray-400 uppercase mb-1">Reading Time</label>
                        <input type="datetime-local" name="common_recorded_at" value="{{ now()->format('Y-m-d\TH:i') }}"
                            class="text-xs border-gray-200 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                @endif
                <x-button type="submit" variant="primary" size="sm">
                    <i class="fa fa-save mr-2"></i> Save
                </x-button>
            </div>
        </x-page-header>
        <div class="py-6">
            <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100">
                    <div class="p-0 text-gray-900">
                        <table class="w-full text-right border-collapse">
                            <thead>
                                <tr class="bg-gray-50 border-b border-gray-100">
                                    <th class="px-6 py-4 text-xs font-black uppercase tracking-widest text-gray-400">
                                        Room
                                        Name</th>
                                    <th
                                        class="px-6 py-4 text-xs font-black uppercase tracking-widest text-gray-400 text-center">
                                        Allowed Range</th>
                                    <th
                                        class="px-6 py-4 text-xs font-black uppercase tracking-widest text-gray-400 text-center">
                                        Last Reading</th>
                                    <th
                                        class="px-6 py-4 text-xs font-black uppercase tracking-widest text-gray-400 text-center">
                                        New Reading</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($rooms as $room)
                                    @if ($room->activeProfileAssignment)

                                        @php
                                            $latestReading = $room->sensors->first();
                                            $profile = $room->activeProfileAssignment?->profile;
                                        @endphp
                                        <tr class="hover:bg-gray-50/50 transition">
                                            <td class="px-4 py-2">
                                                <div class="font-bold text-gray-900">{{ $room->name }}</div>
                                                <div class="text-[10px] text-gray-400 mt-0.5">ID: #{{ $room->id }}</div>
                                            </td>
                                            <td class="px-4 py-2 text-center">
                                                @if($profile)
                                                    <div class="flex flex-col items-center gap-1">
                                                        <span
                                                            class="text-xs font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-full">
                                                            {{ $profile->target_temp }}°C
                                                        </span>
                                                        <span class="text-[10px] text-gray-400">
                                                            ({{ $profile->min_temp }}° / {{ $profile->max_temp }}°)
                                                        </span>
                                                    </div>
                                                @else
                                                    <span class="text-xs text-gray-400 italic">لا يوجد ملف تعريف</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-2 text-center">
                                                @if($latestReading)
                                                    <div class="flex flex-col items-center">
                                                        <span
                                                            class="text-sm font-black {{ $profile && ($latestReading->temperature < $profile->min_temp || $latestReading->temperature > $profile->max_temp) ? 'text-red-600' : 'text-green-600' }}">
                                                            {{ number_format($latestReading->temperature, 1) }}°C
                                                        </span>
                                                        <span class="text-[10px] text-gray-400 mt-1">
                                                            {{ $latestReading->recorded_at instanceof \Illuminate\Support\Carbon ? $latestReading->recorded_at->diffForHumans() : $latestReading->recorded_at }}
                                                        </span>
                                                    </div>
                                                @else
                                                    <span class="text-xs text-gray-300">لا توجد سجلات</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-2">
                                                <div class="flex justify-center">
                                                    <div class="relative w-20">
                                                        <input type="number" step="0.1" name="readings[{{ $room->id }}]"
                                                            class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm font-bold focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all text-center"
                                                            placeholder="0.0">
                                                        <span
                                                            class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xl font-bold">
                                                            °C</span>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-4 py-2 text-center">
                                            <div class="flex flex-col items-center">
                                                <div
                                                    class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                                    <i class="fa fa-door-closed text-2xl text-gray-200"></i>
                                                </div>
                                                <p class="text-gray-400 font-medium italic">No Cooling/Freezing Rooms
                                                    Registered</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="mt-3 flex justify-end">
                    <x-button type="submit" variant="primary" size="sm" class="py-1">
                        <i class="fa fa-save mr-3 text-md"></i> Save
                    </x-button>
                </div>
            </div>
        </div>
    </form>
</x-app-layout>