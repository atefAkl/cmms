<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create PM Schedule') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('pm-schedules.store') }}">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Equipment Type -->
                            <div>
                                <x-input-label for="equipment_type" :value="__('Equipment Type')" />
                                <select id="equipment_type" name="equipment_type" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="App\Models\Room">Room</option>
                                    <option value="App\Models\Compressor">Compressor</option>
                                    <option value="App\Models\Evaporator">Evaporator</option>
                                </select>
                                <x-input-error :messages="$errors->get('equipment_type')" class="mt-2" />
                            </div>

                            <!-- Equipment ID -->
                            <div>
                                <x-input-label for="equipment_id" :value="__('Equipment ID')" />
                                <x-text-input id="equipment_id" class="block mt-1 w-full" type="number" name="equipment_id" :value="old('equipment_id')" required />
                                <p class="text-xs text-gray-500 mt-1">Enter the numeric ID of the selected equipment type.</p>
                                <x-input-error :messages="$errors->get('equipment_id')" class="mt-2" />
                            </div>

                            <!-- Description -->
                            <div class="md:col-span-2">
                                <x-input-label for="description" :value="__('Task Description')" />
                                <x-text-input id="description" class="block mt-1 w-full" type="text" name="description" :value="old('description')" required />
                                <x-input-error :messages="$errors->get('description')" class="mt-2" />
                            </div>

                            <!-- Interval -->
                            <div>
                                <x-input-label for="interval_days" :value="__('Interval (Days)')" />
                                <x-text-input id="interval_days" class="block mt-1 w-full" type="number" name="interval_days" :value="old('interval_days', 30)" required />
                                <x-input-error :messages="$errors->get('interval_days')" class="mt-2" />
                            </div>

                            <!-- Duration -->
                            <div>
                                <x-input-label for="estimated_duration" :value="__('Est. Duration (Minutes)')" />
                                <x-text-input id="estimated_duration" class="block mt-1 w-full" type="number" name="estimated_duration" :value="old('estimated_duration', 60)" required />
                                <x-input-error :messages="$errors->get('estimated_duration')" class="mt-2" />
                            </div>

                            <!-- Priority -->
                            <div>
                                <x-input-label for="priority" :value="__('Priority')" />
                                <select id="priority" name="priority" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="low">Low</option>
                                    <option value="medium" selected>Medium</option>
                                    <option value="high">High</option>
                                    <option value="critical">Critical</option>
                                </select>
                                <x-input-error :messages="$errors->get('priority')" class="mt-2" />
                            </div>

                            <!-- Next Due -->
                            <div>
                                <x-input-label for="next_due" :value="__('Next Due Date')" />
                                <x-text-input id="next_due" class="block mt-1 w-full" type="date" name="next_due" :value="old('next_due')" required />
                                <x-input-error :messages="$errors->get('next_due')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ms-4">
                                {{ __('Create Schedule') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
