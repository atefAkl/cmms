<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Task #') . $task->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Technician Quick Actions (Mobile Oriented) -->
                    @if(!auth()->user()->hasRole('Manager'))
                        <div class="mb-8 p-4 bg-indigo-50 rounded-xl border border-indigo-100">
                            <h3 class="text-sm font-black text-indigo-900 uppercase tracking-widest mb-4">Quick Status Update</h3>
                            <div class="grid grid-cols-2 gap-3">
                                <form method="POST" action="{{ route('maintenance.update', $task) }}" class="contents">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="in_progress">
                                    <input type="hidden" name="started_at" value="{{ now()->format('Y-m-d\TH:i') }}">
                                    <button type="submit" class="flex flex-col items-center justify-center p-4 bg-white border-2 border-blue-200 rounded-xl hover:bg-blue-50 transition-all {{ $task->status === 'in_progress' ? 'border-blue-500 bg-blue-50 ring-2 ring-blue-200' : '' }}">
                                        <svg class="w-8 h-8 text-blue-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/><path d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                                        <span class="text-xs font-bold text-blue-900">Start Work</span>
                                    </button>
                                </form>

                                <form method="POST" action="{{ route('maintenance.update', $task) }}" class="contents">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="completed">
                                    <input type="hidden" name="completed_at" value="{{ now()->format('Y-m-d\TH:i') }}">
                                    <button type="submit" class="flex flex-col items-center justify-center p-4 bg-white border-2 border-green-200 rounded-xl hover:bg-green-50 transition-all {{ $task->status === 'completed' ? 'border-green-500 bg-green-50 ring-2 ring-green-200' : '' }}">
                                        <svg class="w-8 h-8 text-green-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                                        <span class="text-xs font-bold text-green-900">Complete</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('maintenance.update', $task) }}" class="space-y-6">
                        @csrf
                        @method('PATCH')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Basic Info Section -->
                            <div class="space-y-4">
                                    @if(auth()->user()->hasRole('Manager'))
                                        <div>
                                            <x-input-label for="room_id" :value="__('Room')" />
                                            <select id="room_id" name="room_id" class="block mt-1 w-full border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm h-12">
                                                @foreach($rooms as $room)
                                                    <option value="{{ $room->id }}" {{ old('room_id', $task->room_id) == $room->id ? 'selected' : '' }}>{{ $room->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div>
                                            <x-input-label for="asset_id" :value="__('Associated Asset')" />
                                            <select id="asset_id" name="asset_id" class="block mt-1 w-full border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm h-12">
                                                <option value="">{{ __('No specific asset') }}</option>
                                                @foreach($assets as $asset)
                                                    <option value="{{ $asset->id }}" {{ old('asset_id', $task->asset_id) == $asset->id ? 'selected' : '' }}>
                                                        {{ $asset->name }} ({{ $asset->refrigerationSystem->name }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @else
                                        <div class="space-y-4">
                                            <div class="bg-gray-50 p-4 rounded-xl">
                                                <p class="text-xs font-black text-gray-400 uppercase tracking-widest">Location</p>
                                                <p class="font-bold text-gray-900 text-lg">{{ $task->room->name ?? 'General' }}</p>
                                            </div>
                                            @if($task->asset)
                                                <div class="bg-indigo-50 p-4 rounded-xl">
                                                    <p class="text-xs font-black text-indigo-400 uppercase tracking-widest">Asset</p>
                                                    <p class="font-bold text-indigo-900 text-lg">{{ $task->asset->name }}</p>
                                                </div>
                                            @endif
                                        </div>
                                    @endif

                                <div>
                                    <x-input-label for="issue_description" :value="__('Details / Findings')" />
                                    <textarea id="issue_description" name="issue_description" class="block mt-1 w-full border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm" rows="4" required>{{ old('issue_description', $task->issue_description) }}</textarea>
                                </div>
                            </div>

                            <!-- Status & Cost Section -->
                            <div class="space-y-4">
                                @if(auth()->user()->hasRole('Manager'))
                                    <div>
                                        <x-input-label for="status" :value="__('Overall Status')" />
                                        <select id="status" name="status" class="block mt-1 w-full border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm h-12">
                                            @foreach(['open', 'diagnosed', 'assigned', 'in_progress', 'completed', 'approved', 'closed'] as $status)
                                                <option value="{{ $status }}" {{ old('status', $task->status) == $status ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <x-input-label for="technician_id" :value="__('Assign Technician')" />
                                        <select id="technician_id" name="technician_id" class="block mt-1 w-full border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm h-12">
                                            <option value="">{{ __('Unassigned') }}</option>
                                            @foreach($technicians as $tech)
                                                <option value="{{ $tech->id }}" {{ old('technician_id', $task->technician_id) == $tech->id ? 'selected' : '' }}>{{ $tech->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <x-input-label for="cost" :value="__('Material Cost (SAR)')" />
                                        <x-text-input id="cost" class="block mt-1 w-full h-12 rounded-xl" type="number" step="0.01" name="cost" :value="old('cost', $task->cost)" />
                                    </div>
                                    <div class="flex items-end">
                                        <button type="submit" class="w-full h-12 bg-indigo-600 text-white rounded-xl font-black uppercase tracking-widest text-xs shadow-lg hover:bg-indigo-700 transition-all">
                                            Save All Changes
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
