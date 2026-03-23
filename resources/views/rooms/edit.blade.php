<x-app-layout>
    <div class="">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-page-header 
                title="{{ __('Edit Room: ') . $room->name }}" 
                description="Update room parameters and temperature thresholds."
                :backRoute="route('rooms.index')"
            />

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100 p-8">
                <!-- Main Configuration form -->
                <form method="POST" action="{{ route('rooms.update', $room) }}">
                    @csrf
                    @method('PATCH')

                    <div x-data="{ expanded: true }" class="mb-4 bg-white border border-gray-200 shadow-sm rounded-xl overflow-hidden">
                        <!-- Toggle Header -->
                        <button type="button" @click="expanded = !expanded" class="w-full px-5 py-4 flex justify-between items-center bg-gray-50 hover:bg-gray-100 transition border-b border-gray-200 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                <h3 class="text-[10px] font-black uppercase tracking-widest text-gray-600">Room Configuration</h3>
                            </div>
                            <svg class="w-4 h-4 text-gray-400 transition-transform duration-300" :class="expanded ? '' : 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 15l7-7 7 7"></path></svg>
                        </button>

                        <!-- Collapsible Content -->
                        <div x-show="expanded" x-transition class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Name -->
                                <div>
                                    <x-input-label for="name" :value="__('Room Name')" class="text-xs font-bold uppercase tracking-wider text-gray-500 mb-1" />
                                    <x-text-input id="name" class="block w-full" type="text" name="name"
                                        :value="old('name', $room->name)" required autofocus />
                                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                </div>
                                <!-- warehouse_id -->
                                <div>
                                    <x-input-label for="warehouse_id" :value="__('Warehouse')" class="text-xs font-bold uppercase tracking-wider text-gray-500 mb-1" />
                                    <select id="warehouse_id" class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm font-medium text-gray-700" name="warehouse_id" required>
                                        <option value="">Select Warehouse</option>
                                        @foreach($warehouses as $warehouse)
                                            <option {{ old('warehouse_id', $room->warehouse_id) == $warehouse->id ? 'selected' : '' }} value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('warehouse_id')" class="mt-2" />
                                </div>
                                <!-- room_layout_id -->
                                <div>
                                    <x-input-label for="layout_id" :value="__('Room Layout')" class="text-xs font-bold uppercase tracking-wider text-gray-500 mb-1" />
                                    <select id="layout_id" class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm font-medium text-gray-700" name="room_layout_id" required>
                                        <option value="">Select Layout</option>
                                        @foreach($layouts as $layout)
                                            <option {{ old('room_layout_id', $room->room_layout_id) == $layout->id ? 'selected' : '' }} value="{{ $layout->id }}">{{ $layout->name }}</option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('layout_id')" class="mt-2" />
                                </div>
                                <!-- status -->
                                <div>
                                    <x-input-label for="status" :value="__('Status')" class="text-xs font-bold uppercase tracking-wider text-gray-500 mb-1" />
                                    <select id="status" class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm font-medium text-gray-700" name="status" required>
                                        <option value="">Select Status</option>
                                        <option {{ old('status', $room->status) == 'running' ? 'selected' : '' }} value="running">Running</option>
                                        <option {{ old('status', $room->status) == 'stopped' ? 'selected' : '' }} value="stopped">Stopped</option>
                                        <option {{ old('status', $room->status) == 'maintenance' ? 'selected' : '' }} value="maintenance">Maintenance</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('status')" class="mt-2" />
                                </div>
                                <!-- is_active -->
                                <div>
                                    <x-input-label for="is_active" :value="__('Active Protocol')" class="text-xs font-bold uppercase tracking-wider text-gray-500 mb-1" />
                                    <select id="is_active" class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm font-medium text-gray-700" name="is_active" required>
                                        <option {{ old('is_active', $room->is_active) == 1 ? 'selected' : '' }} value="1">Enabled</option>
                                        <option {{ old('is_active', $room->is_active) == 0 ? 'selected' : '' }} value="0">Disabled</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('is_active')" class="mt-2" />
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ms-4">
                                {{ __('Update Room') }}
                            </x-primary-button>
                        </div>
                    </div>
                </form>
             
                <!-- Temperature Configuration form -->
                <form method="POST" action="{{ route('rooms.update', $room) }}">
                    @csrf
                    @method('PATCH')

                    <div x-data="{ expanded: true }" class="mb-4 bg-white border border-gray-200 shadow-sm rounded-xl overflow-hidden">
                        <!-- Toggle Header -->
                        <button type="button" @click="expanded = !expanded" class="w-full px-5 py-4 flex justify-between items-center bg-gray-50 hover:bg-gray-100 transition border-b border-gray-200 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                <h3 class="text-[10px] font-black uppercase tracking-widest text-gray-600">Room Configuration</h3>
                            </div>
                            <svg class="w-4 h-4 text-gray-400 transition-transform duration-300" :class="expanded ? '' : 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 15l7-7 7 7"></path></svg>
                        </button>

                        <!-- Collapsible Content -->
                        <div x-show="expanded" x-transition class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Name -->
                                <div>
                                    <x-input-label for="name" :value="__('Room Name')" class="text-xs font-bold uppercase tracking-wider text-gray-500 mb-1" />
                                    <x-text-input id="name" class="block w-full" type="text" name="name"
                                        :value="old('name', $room->name)" required autofocus />
                                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                </div>
                                <!-- warehouse_id -->
                                <div>
                                    <x-input-label for="warehouse_id" :value="__('Warehouse')" class="text-xs font-bold uppercase tracking-wider text-gray-500 mb-1" />
                                    <select id="warehouse_id" class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm font-medium text-gray-700" name="warehouse_id" required>
                                        <option value="">Select Warehouse</option>
                                        @foreach($warehouses as $warehouse)
                                            <option {{ old('warehouse_id', $room->warehouse_id) == $warehouse->id ? 'selected' : '' }} value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('warehouse_id')" class="mt-2" />
                                </div>
                                <!-- room_layout_id -->
                                <div>
                                    <x-input-label for="layout_id" :value="__('Room Layout')" class="text-xs font-bold uppercase tracking-wider text-gray-500 mb-1" />
                                    <select id="layout_id" class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm font-medium text-gray-700" name="room_layout_id" required>
                                        <option value="">Select Layout</option>
                                        @foreach($layouts as $layout)
                                            <option {{ old('room_layout_id', $room->room_layout_id) == $layout->id ? 'selected' : '' }} value="{{ $layout->id }}">{{ $layout->name }}</option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('layout_id')" class="mt-2" />
                                </div>
                                <!-- status -->
                                <div>
                                    <x-input-label for="status" :value="__('Status')" class="text-xs font-bold uppercase tracking-wider text-gray-500 mb-1" />
                                    <select id="status" class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm font-medium text-gray-700" name="status" required>
                                        <option value="">Select Status</option>
                                        <option {{ old('status', $room->status) == 'running' ? 'selected' : '' }} value="running">Running</option>
                                        <option {{ old('status', $room->status) == 'stopped' ? 'selected' : '' }} value="stopped">Stopped</option>
                                        <option {{ old('status', $room->status) == 'maintenance' ? 'selected' : '' }} value="maintenance">Maintenance</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('status')" class="mt-2" />
                                </div>
                                <!-- is_active -->
                                <div>
                                    <x-input-label for="is_active" :value="__('Active Protocol')" class="text-xs font-bold uppercase tracking-wider text-gray-500 mb-1" />
                                    <select id="is_active" class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm font-medium text-gray-700" name="is_active" required>
                                        <option {{ old('is_active', $room->is_active) == 1 ? 'selected' : '' }} value="1">Enabled</option>
                                        <option {{ old('is_active', $room->is_active) == 0 ? 'selected' : '' }} value="0">Disabled</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('is_active')" class="mt-2" />
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ms-4">
                                {{ __('Update Room') }}
                            </x-primary-button>
                        </div>
                    </div>
                </form>
                
            </div>
        </div>
    </div>
</x-app-layout>
