<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-page-header 
                title="{{ __('Edit Room: ') . $room->name }}" 
                description="Update room parameters and temperature thresholds."
                :backRoute="route('rooms.index')"
            />

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100 p-8">
                    <form method="POST" action="{{ route('rooms.update', $room) }}">
                        @csrf
                        @method('PATCH')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Name -->
                            <div>
                                <x-input-label for="name" :value="__('Room Name')" />
                                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $room->name)" required autofocus />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <!-- Location -->
                            <div>
                                <x-input-label for="location" :value="__('Location')" />
                                <x-text-input id="location" class="block mt-1 w-full" type="text" name="location" :value="old('location', $room->location)" />
                                <x-input-error :messages="$errors->get('location')" class="mt-2" />
                            </div>

                            <!-- Target Temp -->
                            <div>
                                <x-input-label for="target_temperature" :value="__('Target Temperature (°C)')" />
                                <x-text-input id="target_temperature" class="block mt-1 w-full" type="number" step="0.1" name="target_temperature" :value="old('target_temperature', $room->target_temperature)" required />
                                <x-input-error :messages="$errors->get('target_temperature')" class="mt-2" />
                            </div>

                            <!-- Min Temp -->
                            <div>
                                <x-input-label for="min_temperature" :value="__('Min Temperature (°C)')" />
                                <x-text-input id="min_temperature" class="block mt-1 w-full" type="number" step="0.1" name="min_temperature" :value="old('min_temperature', $room->min_temperature)" required />
                                <x-input-error :messages="$errors->get('min_temperature')" class="mt-2" />
                            </div>

                            <!-- Max Temp -->
                            <div>
                                <x-input-label for="max_temperature" :value="__('Max Temperature (°C)')" />
                                <x-text-input id="max_temperature" class="block mt-1 w-full" type="number" step="0.1" name="max_temperature" :value="old('max_temperature', $room->max_temperature)" required />
                                <x-input-error :messages="$errors->get('max_temperature')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ms-4">
                                {{ __('Update Room') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
