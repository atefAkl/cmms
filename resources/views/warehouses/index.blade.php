<x-app-layout>
    <div x-data="{}" x-init="
        @if($errors->any())
            @if(old('warehouse_id'))
                $nextTick(() => $dispatch('open-modal', 'edit-warehouse-{{ old('warehouse_id') }}'));
            @else
                $nextTick(() => $dispatch('open-modal', 'create-warehouse'));
            @endif
        @endif
    ">
        <div class="mb-6">
            <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
                <x-page-header title="{{ __('Warehouses') }}"
                    description="Manage physical storage locations and inventory bins.">
                    <x-button variant="primary" size="sm" @click="$dispatch('open-modal', 'create-warehouse')">
                        <i class="fa fa-plus me-3"></i> {{__('New')}}
                    </x-button>
                </x-page-header>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100">
                    <div class="p-6 text-gray-900">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50 border-b border-gray-100">
                                    <th class="px-4 py-3 text-[10px] font-black uppercase tracking-widest text-gray-400">
                                        Name</th>
                                    <th class="px-4 py-3 text-[10px] font-black uppercase tracking-widest text-gray-400">
                                        Location</th>
                                    <th
                                        class="px-4 py-3 text-[10px] font-black uppercase tracking-widest text-gray-400 text-right">
                                        Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($warehouses as $warehouse)
                                    <tr class="border-b transition hover:bg-gray-50">
                                        <td class="p-4 font-medium text-gray-800">{{ $warehouse->name }}</td>
                                        <td class="p-4 text-gray-500">{{ $warehouse->location ?? 'N/A' }}</td>
                                        <td class="p-4 text-right space-x-2 text-sm">
                                            <button @click="$dispatch('open-modal', 'edit-warehouse-{{ $warehouse->id }}')"
                                                class="text-indigo-600 hover:underline font-bold uppercase tracking-widest text-[10px]">Edit</button>
                                            <form action="{{ route('warehouses.destroy', $warehouse) }}" method="POST"
                                                class="inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:underline font-bold uppercase tracking-widest text-[10px]"
                                                    onclick="return confirm('Delete warehouse?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>

                                    <!-- Edit Modal -->
                                    <x-modal name="edit-warehouse-{{ $warehouse->id }}" focusable>
                                        <h2 class="text-lg border-b border-gray-100 font-medium text-gray-900 py-3 px-5">Edit Warehouse</h2>
                                        <form method="post" action="{{ route('warehouses.update', $warehouse) }}" class="">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="warehouse_id" value="{{ $warehouse->id }}">
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-5">
                                                <div class="mt-4">
                                                    <x-input-label for="name" value="Warehouse Name" />
                                                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                                                        placeholder="e.g. Main Store" required :value="old('name', $warehouse->name)" />
                                                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                                </div>
                                                <div class="mt-4">
                                                    <x-input-label for="max_room_count" value="Warehouse Rooms" />
                                                    <x-text-input id="max_room_count" name="max_room_count" type="number" class="mt-1 block w-full"
                                                        placeholder="4.00" required :value="old('max_room_count', $warehouse->max_room_count)" />
                                                    <x-input-error :messages="$errors->get('max_room_count')" class="mt-2" />
                                                </div>
                                                <div class="mt-4">
                                                    <x-input-label for="max_path_count" value="Warehouse Paths" />
                                                    <x-text-input id="max_path_count" name="max_path_count" type="number" class="mt-1 block w-full"
                                                        placeholder="2.00" required :value="old('max_path_count', $warehouse->max_path_count)" />
                                                    <x-input-error :messages="$errors->get('max_path_count')" class="mt-2" />
                                                </div>
                                                <div class="mt-4">
                                                    <x-input-label for="diameter" value="Warehouse Diameter" />
                                                    <div class="grid grid-cols-2 gap-2">
                                                        <x-text-input id="wh_width" name="wh_width" type="number" class="mt-1" placeholder="Width"
                                                            required :value="old('wh_width', is_array($warehouse->diameter) ? ($warehouse->diameter['width'] ?? '') : '')" />
                                                        <x-text-input id="wh_length" name="wh_length" type="number" class="mt-1" placeholder="Depth"
                                                            required :value="old('wh_length', is_array($warehouse->diameter) ? ($warehouse->diameter['length'] ?? '') : '')" />
                                                    </div>
                                                    <x-input-error :messages="$errors->get('wh_width')" class="mt-2" />
                                                    <x-input-error :messages="$errors->get('wh_length')" class="mt-2" />
                                                </div>
                                                <div class="mt-4">
                                                    <x-input-label for="diameter_unit" value="Warehouse Diameter Unit" />
                                                    <select id="diameter_unit" name="diameter_unit" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                                        <option value="">Select Unit</option>
                                                        @foreach(['mm' => 'Millimeters', 'cm' => 'Centimeters', 'm' => 'Meters', 'in' => 'Inches', 'ft' => 'Feet'] as $value => $label)
                                                            <option value="{{ $value }}" {{ old('diameter_unit', $warehouse->diameter_unit) == $value ? 'selected' : '' }}>{{ $label }}</option>
                                                        @endforeach
                                                    </select>
                                                    <x-input-error :messages="$errors->get('diameter_unit')" class="mt-2" />
                                                </div>
                                                <div class="mt-4">
                                                    <x-input-label for="door_dimensions" value="Warehouse Door Dimensions" />
                                                    <div class="grid grid-cols-2 gap-2">
                                                        <x-text-input id="door_width" name="door_width" type="number" class="mt-1" placeholder="Width"
                                                            required :value="old('door_width', is_array($warehouse->door_dimensions) ? ($warehouse->door_dimensions['width'] ?? '') : '')" />
                                                        <x-text-input id="door_height" name="door_height" type="number" class="mt-1" placeholder="Height"
                                                            required :value="old('door_height', is_array($warehouse->door_dimensions) ? ($warehouse->door_dimensions['height'] ?? '') : '')" />
                                                    </div>
                                                    <x-input-error :messages="$errors->get('door_width')" class="mt-2" />
                                                    <x-input-error :messages="$errors->get('door_height')" class="mt-2" />
                                                </div>
                                            </div>
                                            <div class="border-t border-gray-100 pb-3 px-5 flex justify-end">
                                                <x-secondary-button x-on:click="$dispatch('close')">Cancel</x-secondary-button>
                                                <x-button variant="primary" class="ms-3">Save Changes</x-button>
                                            </div>
                                        </form>
                                    </x-modal>
                                @endforeach
                            </tbody>
                        </table>
                        @if($warehouses->hasPages())
                            <div class="mt-4">
                                {{ $warehouses->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Create Modal -->
        <x-modal name="create-warehouse" focusable>
            <form method="post" action="{{ route('warehouses.store') }}" class="p-6">
                @csrf
                <h2 class="text-lg font-medium text-gray-900">Create New Warehouse</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="mt-4">
                        <x-input-label for="name" value="Warehouse Name" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                            placeholder="e.g. Main Store" required :value="old('name')" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>
                    <div class="mt-4">
                        <x-input-label for="max_room_count" value="Warehouse Rooms" />
                        <x-text-input id="max_room_count" name="max_room_count" type="number" class="mt-1 block w-full"
                            placeholder="4.00" required :value="old('max_room_count')" />
                        <x-input-error :messages="$errors->get('max_room_count')" class="mt-2" />
                    </div>
                    <div class="mt-4">
                        <x-input-label for="max_path_count" value="Warehouse Paths" />
                        <x-text-input id="max_path_count" name="max_path_count" type="number" class="mt-1 block w-full"
                            placeholder="2.00" required :value="old('max_path_count')" />
                        <x-input-error :messages="$errors->get('max_path_count')" class="mt-2" />
                    </div>
                    <div class="mt-4">
                        <x-input-label for="diameter" value="Warehouse Diameter" />
                        <div class="grid grid-cols-2 gap-2">
                            <x-text-input id="wh_width" name="wh_width" type="number" class="mt-1" placeholder="Width"
                                required :value="old('wh_width')" />
                            <x-text-input id="wh_length" name="wh_length" type="number" class="mt-1" placeholder="Depth"
                                required :value="old('wh_length')" />
                        </div>
                        <x-input-error :messages="$errors->get('wh_width')" class="mt-2" />
                        <x-input-error :messages="$errors->get('wh_length')" class="mt-2" />
                    </div>
                    <div class="mt-4">
                        <x-input-label for="diameter_unit" value="Warehouse Diameter Unit" />
                        <select id="diameter_unit" name="diameter_unit" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            <option value="">Select Unit</option>
                            @foreach(['mm' => 'Millimeters', 'cm' => 'Centimeters', 'm' => 'Meters', 'in' => 'Inches', 'ft' => 'Feet'] as $value => $label)
                                <option value="{{ $value }}" {{ old('diameter_unit') == $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('diameter_unit')" class="mt-2" />
                    </div>
                    <div class="mt-4">
                        <x-input-label for="door_dimensions" value="Warehouse Door Dimensions" />
                        <div class="grid grid-cols-2 gap-2">
                            <x-text-input id="door_width" name="door_width" type="number" class="mt-1" placeholder="Width"
                                required :value="old('door_width')" />
                            <x-text-input id="door_height" name="door_height" type="number" class="mt-1" placeholder="Height"
                                required :value="old('door_height')" />
                        </div>
                        <x-input-error :messages="$errors->get('door_width')" class="mt-2" />
                        <x-input-error :messages="$errors->get('door_height')" class="mt-2" />
                    </div>
                </div>
                <div class="mt-6 flex justify-end">
                    <x-button variant="secondary" x-on:click="$dispatch('close')">Cancel</x-button>
                    <x-button variant="primary" class="ms-3">Create</x-button>
                </div>
            </form>
        </x-modal>
    </div>
</x-app-layout>