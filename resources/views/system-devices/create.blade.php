<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('refrigeration-systems.show', $system) }}" class="p-2 bg-white border border-gray-200 rounded-lg text-gray-400 hover:text-gray-600 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Attach Device to') }} {{ $system->name }}
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl p-8 border border-gray-100">
                <form action="{{ route('system-devices.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <input type="hidden" name="refrigeration_system_id" value="{{ $system->id }}">

                    <div>
                        <x-input-label for="name" :value="__('Display Name')" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" placeholder="e.g. Master Compressor Unit" required autofocus />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="device_id" :value="__('Device Type (Catalog)')" />
                        <select id="device_id" name="device_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm font-bold text-gray-700" required>
                            <option value="">-- Select Type --</option>
                            @foreach($devices as $device)
                                <option value="{{ $device->id }}" {{ old('device_id') == $device->id ? 'selected' : '' }}>
                                    {{ $device->name }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('device_id')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="installed" :value="__('Installation Date')" />
                        <x-text-input id="installed" name="installed" type="date" class="mt-1 block w-full" :value="old('installed', date('Y-m-d'))" />
                        <x-input-error :messages="$errors->get('installed')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-100">
                        <a href="{{ route('refrigeration-systems.show', $system) }}" class="text-sm font-bold text-gray-400 hover:text-gray-600 transition uppercase tracking-widest">{{ __('Cancel') }}</a>
                        <x-primary-button>
                            {{ __('Attach Device') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
