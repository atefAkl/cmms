@extends('layouts.app')

@section('content')
<div class="mb-6 flex items-center gap-4 px-4 sm:px-0">
    <a href="{{ route('assets.index') }}" class="p-2 bg-white border border-gray-200 rounded-lg text-gray-400 hover:text-gray-600 transition">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
    </a>
    <div>
        <h1 class="text-2xl font-black text-gray-900">Create New Asset</h1>
        <p class="text-gray-500 text-sm">Define components and their relationships.</p>
    </div>
</div>

<div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-200 p-6">
    <form action="{{ route('assets.store') }}" method="POST" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Basic Info -->
            <div class="space-y-4">
                <div>
                    <x-label for="name" value="Asset Name" />
                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" value="{{ old('name') }}" required autofocus />
                    <x-input-error for="name" class="mt-2" />
                </div>

                <div>
                    <x-label for="type" value="Asset Type" />
                    <select id="type" name="type" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm font-bold text-gray-700">
                        <option value="compressor">Compressor</option>
                        <option value="evaporator">Evaporator</option>
                        <option value="motor">Motor</option>
                        <option value="sensor">Sensor</option>
                        <option value="fan">Fan</option>
                        <option value="heater">Heater</option>
                        <option value="control_panel">Control Panel</option>
                        <option value="power_panel">Power Panel</option>
                    </select>
                    <x-input-error for="type" class="mt-2" />
                </div>

                <div>
                    <x-label for="status" value="Status" />
                    <select id="status" name="status" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm font-bold text-gray-700">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                        <option value="maintenance">Under Maintenance</option>
                        <option value="faulty">Faulty</option>
                    </select>
                </div>
            </div>

            <!-- Hierarchy & System -->
            <div class="space-y-4 p-4 bg-gray-50 rounded-xl border border-gray-100">
                <div>
                    <x-label for="refrigeration_system_id" value="Refrigeration System" />
                    <select id="refrigeration_system_id" name="refrigeration_system_id" class="mt-1 block w-full border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm font-bold text-gray-700">
                        @foreach($systems as $system)
                            <option value="{{ $system->id }}" {{ ($selectedSystemId == $system->id) ? 'selected' : '' }}>
                                {{ $system->room->name }} - {{ $system->name }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error for="refrigeration_system_id" class="mt-2" />
                </div>

                <div>
                    <x-label for="parent_id" value="Parent Asset (Optional)" />
                    <select id="parent_id" name="parent_id" class="mt-1 block w-full border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm font-bold text-gray-700">
                        <option value="">-- No Parent (Top Level) --</option>
                        @foreach($parentAssets as $pAsset)
                            <option value="{{ $pAsset->id }}" {{ ($selectedParentId == $pAsset->id) ? 'selected' : '' }}>
                                [{{ $pAsset->type }}] {{ $pAsset->name }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error for="parent_id" class="mt-2" />
                    <p class="text-[10px] text-gray-400 mt-1 italic">Selecting a parent makes this asset a component of the parent device.</p>
                </div>
            </div>

            <!-- Specs -->
            <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-3 gap-6 pt-4 border-t border-gray-100">
                <div>
                    <x-label for="manufacturer" value="Manufacturer" />
                    <x-text-input id="manufacturer" name="manufacturer" type="text" class="mt-1 block w-full" value="{{ old('manufacturer') }}" />
                </div>
                <div>
                    <x-label for="model" value="Model Number" />
                    <x-text-input id="model" name="model" type="text" class="mt-1 block w-full" value="{{ old('model') }}" />
                </div>
                <div>
                    <x-label for="serial_number" value="Serial Number" />
                    <x-text-input id="serial_number" name="serial_number" type="text" class="mt-1 block w-full" value="{{ old('serial_number') }}" />
                </div>
                <div>
                    <x-label for="install_date" value="Installation Date" />
                    <x-text-input id="install_date" name="install_date" type="date" class="mt-1 block w-full" value="{{ old('install_date') }}" />
                </div>
                <div class="md:col-span-2">
                    <x-label for="notes" value="Technical Notes" />
                    <textarea id="notes" name="notes" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm font-medium text-gray-600"></textarea>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-100">
            <a href="{{ route('assets.index') }}" class="text-sm font-bold text-gray-400 hover:text-gray-600 transition uppercase tracking-widest">Cancel</a>
            <button type="submit" class="inline-flex items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-xl font-black text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition shadow-lg shadow-indigo-600/20">
                Save Asset
            </button>
        </div>
    </form>
</div>
@endsection
