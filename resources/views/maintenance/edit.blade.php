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
                    <form method="POST" action="{{ route('maintenance.update', $task) }}">
                        @csrf
                        @method('PATCH')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Room -->
                            <div>
                                <x-input-label for="room_id" :value="__('Room')" />
                                <select id="room_id" name="room_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    @foreach($rooms as $room)
                                        <option value="{{ $room->id }}" {{ old('room_id', $task->room_id) == $room->id ? 'selected' : '' }}>{{ $room->name }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('room_id')" class="mt-2" />
                            </div>

                            <!-- Status -->
                            <div>
                                <x-input-label for="status" :value="__('Status')" />
                                <select id="status" name="status" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    @foreach(['open', 'diagnosed', 'assigned', 'in_progress', 'completed', 'approved', 'closed'] as $status)
                                        <option value="{{ $status }}" {{ old('status', $task->status) == $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('status')" class="mt-2" />
                            </div>

                            <!-- Description -->
                            <div class="md:col-span-2">
                                <x-input-label for="issue_description" :value="__('Issue Description')" />
                                <textarea id="issue_description" name="issue_description" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="3" required>{{ old('issue_description', $task->issue_description) }}</textarea>
                                <x-input-error :messages="$errors->get('issue_description')" class="mt-2" />
                            </div>

                            <!-- Technician -->
                            <div>
                                <x-input-label for="technician_id" :value="__('Technician')" />
                                <select id="technician_id" name="technician_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="">{{ __('Unassigned') }}</option>
                                    @foreach($technicians as $tech)
                                        <option value="{{ $tech->id }}" {{ old('technician_id', $task->technician_id) == $tech->id ? 'selected' : '' }}>{{ $tech->name }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('technician_id')" class="mt-2" />
                            </div>

                            <!-- Cost -->
                            <div>
                                <x-input-label for="cost" :value="__('Cost')" />
                                <x-text-input id="cost" class="block mt-1 w-full" type="number" step="0.01" name="cost" :value="old('cost', $task->cost)" />
                                <x-input-error :messages="$errors->get('cost')" class="mt-2" />
                            </div>

                            <!-- Timestamps -->
                            <div>
                                <x-input-label for="started_at" :value="__('Started At')" />
                                <x-text-input id="started_at" class="block mt-1 w-full" type="datetime-local" name="started_at" :value="old('started_at', $task->started_at ? $task->started_at->format('Y-m-d\TH:i') : '')" />
                            </div>

                            <div>
                                <x-input-label for="completed_at" :value="__('Completed At')" />
                                <x-text-input id="completed_at" class="block mt-1 w-full" type="datetime-local" name="completed_at" :value="old('completed_at', $task->completed_at ? $task->completed_at->format('Y-m-d\TH:i') : '')" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ms-4">
                                {{ __('Update Task') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
