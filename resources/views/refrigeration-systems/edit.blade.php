<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Refrigeration System') }}: {{ $refrigerationSystem->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <form action="{{ route('refrigeration-systems.update', $refrigerationSystem) }}" method="POST"
                    class="space-y-6">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-label for="room_id" value="{{ __('Room') }}" />
                            <select name="room_id" id="room_id"
                                class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                @foreach($rooms as $room)
                                    <option value="{{ $room->id }}" {{ $refrigerationSystem->room_id == $room->id ? 'selected' : '' }}>{{ $room->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <x-label for="name" value="{{ __('System Name') }}" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                                :value="old('name', $refrigerationSystem->name)" required />
                        </div>
                        <div>
                            <x-label for="status" value="{{ __('Status') }}" />
                            <select name="status" id="status"
                                class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="active" {{ $refrigerationSystem->status == 'active' ? 'selected' : '' }}>
                                    Active</option>
                                <option value="inactive" {{ $refrigerationSystem->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="maintenance" {{ $refrigerationSystem->status == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                            </select>
                        </div>
                        <div>
                            <x-label for="installed_at" value="{{ __('Installation Date') }}" />
                            <x-text-input id="installed_at" name="installed_at" type="date" class="mt-1 block w-full"
                                :value="old('installed_at', $refrigerationSystem->installed_at ? \Illuminate\Support\Carbon::parse($refrigerationSystem->installed_at)->format('Y-m-d') : '')" />
                        </div>
                    </div>
                    <div>
                        <x-label for="notes" value="{{ __('Notes') }}" />
                        <textarea id="notes" name="notes"
                            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                            rows="3">{{ old('notes', $refrigerationSystem->notes) }}</textarea>
                    </div>
                    <div class="flex items-center justify-end space-x-4">
                        <x-primary-button>
                            {{ __('Update System') }}
                        </x-primary-button>
                    </div>
                </form>

                <div class="mt-10 pt-10 border-t border-gray-100">
                    <h3 class="text-lg font-medium text-red-600">Danger Zone</h3>
                    <p class="text-sm text-gray-500 mb-4">Once deleted, the system and its associations cannot be
                        recovered.</p>
                    <form action="{{ route('refrigeration-systems.destroy', $refrigerationSystem) }}" method="POST"
                        onsubmit="return confirm('Are you sure you want to delete this system?');">
                        @csrf
                        @method('DELETE')
                        <x-danger-button type="submit">
                            {{ __('Delete System') }}
                        </x-danger-button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>