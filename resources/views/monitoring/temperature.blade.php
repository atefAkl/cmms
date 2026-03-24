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
                                        Temp</th>
                                    <th
                                        class="px-6 py-4 text-xs font-black uppercase tracking-widest text-gray-400 text-center">
                                        Humidity</th>
                                    <th
                                        class="px-6 py-4 text-xs font-black uppercase tracking-widest text-gray-400 text-center">
                                        Snapshot</th>
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
                                                            class="absolute left-1 top-1/2 -translate-y-1/2 text-gray-400 text-[10px] font-bold">
                                                            °C</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-4 py-2">
                                                <div class="flex justify-center">
                                                    <div class="relative w-20">
                                                        <input type="number" step="0.1" name="humidities[{{ $room->id }}]"
                                                            class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm font-bold focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all text-center"
                                                            placeholder="0.0">
                                                        <span
                                                            class="absolute left-1 top-1/2 -translate-y-1/2 text-gray-400 text-[10px] font-bold">
                                                            %</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-4 py-2 text-center">
                                                <div class="flex items-center justify-center">
                                                    <input id="save_snapshots_{{ $room->id }}" type="checkbox" name="save_snapshots[{{ $room->id }}]" value="1" class="w-5 h-5 text-indigo-600 bg-gray-100 border-gray-300 rounded focus:ring-indigo-500 cursor-pointer transition duration-150 ease-in-out">
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

    <div class="py-6 pt-0">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-900 overflow-hidden shadow-sm sm:rounded-xl border border-gray-800 p-6 text-white" x-data="componentRegistry()">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-bold">Manual Component Status Registry</h3>
                        <p class="text-xs text-gray-400">Select a system to manually update its components' status.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="col-span-1">
                        <label class="block text-xs font-bold uppercase text-gray-500 mb-2">Select System</label>
                        <select x-model="selectedSystem" @change="fetchComponents" class="w-full bg-gray-800 border-gray-700 rounded-xl text-sm focus:ring-indigo-500 text-white">
                            <option value="">-- Select System --</option>
                            @foreach(\App\Models\RefrigerationSystem::all() as $system)
                                <option value="{{ $system->id }}">{{ $system->name }}</option>
                            @endforeach
                        </select>

                        <div class="mt-4">
                            <label class="block text-xs font-bold uppercase text-gray-500 mb-2">Shift</label>
                            <select x-model="shift" class="w-full bg-gray-800 border-gray-700 rounded-xl text-sm focus:ring-indigo-500 text-white">
                                <option value="mss">MSS (06:00 - 14:00)</option>
                                <option value="mes">MES (14:00 - 22:00)</option>
                                <option value="ess">ESS (22:00 - 02:00)</option>
                                <option value="ees">EES (02:00 - 06:00)</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-span-2 bg-gray-800/50 rounded-xl p-4 min-h-[200px]">
                        <template x-if="loading">
                            <div class="flex items-center justify-center h-full">
                                <i class="fa fa-spinner fa-spin text-indigo-500 text-2xl"></i>
                            </div>
                        </template>

                        <template x-if="!loading && components.length > 0">
                            <div class="space-y-4">
                                <template x-for="component in components" :key="component.id">
                                    <div class="flex items-center justify-between bg-gray-800 p-3 rounded-lg border border-gray-700">
                                        <div>
                                            <div class="text-sm font-bold" x-text="component.name"></div>
                                            <div class="text-[10px] text-gray-500" x-text="(component.parent_asset_name || '') + ' | ' + component.type"></div>
                                        </div>
                                        <div class="flex gap-2">
                                            <button type="button" @click="component.status = 'working'" :class="component.status === 'working' ? 'bg-green-600 border-green-400' : 'bg-gray-700 border-gray-600'" class="px-3 py-1 rounded text-[10px] font-bold border transition">WORKING</button>
                                            <button type="button" @click="component.status = 'stopped'" :class="component.status === 'stopped' ? 'bg-red-600 border-red-400' : 'bg-gray-700 border-gray-600'" class="px-3 py-1 rounded text-[10px] font-bold border transition">STOPPED</button>
                                        </div>
                                    </div>
                                </template>
                                
                                <div class="mt-6 flex justify-end">
                                    <button type="button" @click="saveStatus" class="bg-indigo-600 hover:bg-indigo-700 px-6 py-2 rounded-xl text-sm font-bold transition flex items-center gap-2">
                                        <i class="fa fa-check-circle"></i> Update Status
                                    </button>
                                </div>
                            </div>
                        </template>

                        <template x-if="!loading && components.length === 0">
                            <div class="flex items-center justify-center h-full text-gray-500 italic text-sm">
                                Select a system to load components
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function fetchComponents() {
            if (!this.selectedSystem) {
                this.components = [];
                return;
            }
            this.loading = true;
            fetch(`/monitoring/systems/${this.selectedSystem}/components`)
                .then(res => res.json())
                .then(data => {
                    console.log(data);
                    this.components = data.components.map(c => ({
                        id: c.id,
                        name: c.name,
                        type: c.type,
                        parent_asset_name: c.parent_asset_name,
                        status: c.last_status || 'working'
                    }));
                    this.loading = false;
                });
        }
        function componentRegistry() {
            return {
                selectedSystem: '',
                shift: 'mss',
                loading: false,
                components: [],
                // fetchComponents() {
                //     if (!this.selectedSystem) {
                //         this.components = [];
                //         return;
                //     }
                //     this.loading = true;
                //     fetch(`/monitoring/systems/${this.selectedSystem}/components`)
                //         .then(res => res.json())
                //         .then(data => {
                //             this.components = data.components.map(c => ({
                //                 id: c.id,
                //                 name: c.name,
                //                 type: c.type,
                //                 parent_asset_name: c.parent_asset_name,
                //                 status: c.last_status || 'working'
                //             }));
                //             this.loading = false;
                //         });
                // },
                saveStatus() {
                    if (this.components.length === 0) return;
                    
                    const payload = {
                        system_id: this.selectedSystem,
                        shift: this.shift,
                        register_type: 'manually',
                        components: this.components.map(c => ({
                            component_id: c.id,
                            status: c.status
                        }))
                    };

                    fetch('{{ route('monitoring.item-work-registries.store') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify(payload)
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            alert('Status updated successfully');
                        } else {
                            alert('Error: ' + data.message);
                        }
                    });
                }
            }
        }
    </script>
</x-app-layout>