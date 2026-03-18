<x-app-layout>
    <div x-data="{}" x-init="
        @if($errors->any())
            @if(old('layout_id'))
                $nextTick(() => $dispatch('open-modal', 'edit-layout-{{ old('layout_id') }}'));
            @else
                $nextTick(() => $dispatch('open-modal', 'create-layout'));
            @endif
        @endif
    ">
        <div class="mb-6">
            <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
                <x-page-header title="{{ __('Room Layouts') }}"
                    description="Define and manage physical layout templates for storage rooms.">
                    <x-button variant="primary" size="sm" @click="$dispatch('open-modal', 'create-layout')">
                        <i class="fa fa-plus me-3"></i> {{__('New Layout')}}
                    </x-button>
                </x-page-header>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100">
                    <div class="p-6 text-gray-900">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50 border-b border-gray-100">
                                    <th
                                        class="px-4 py-3 text-[10px] font-black uppercase tracking-widest text-gray-400">
                                        Layout Name</th>
                                    <th
                                        class="px-4 py-3 text-[10px] font-black uppercase tracking-widest text-gray-400">
                                        Dimensions</th>
                                    <th
                                        class="px-4 py-3 text-[10px] font-black uppercase tracking-widest text-gray-400">
                                        Door Position</th>
                                    <th
                                        class="px-4 py-3 text-[10px] font-black uppercase tracking-widest text-gray-400">
                                        Status</th>
                                    <th
                                        class="px-4 py-3 text-[10px] font-black uppercase tracking-widest text-gray-400 text-right">
                                        Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($layouts as $layout)
                                    <tr class="border-b transition hover:bg-gray-50">
                                        <td class="px-4 py-2 font-medium text-gray-800">{{ $layout->name }}</td>
                                        <td class="px-4 py-2 text-gray-500 text-sm">
                                            @if(is_array($layout->layout_dimensions))
                                                {{ $layout->layout_dimensions['length'] ?? '' }}L x {{ $layout->layout_dimensions['width'] ?? '' }}W x {{ $layout->layout_dimensions['height'] ?? '' }}H
                                            @else
                                                {{ $layout->layout_dimensions }}
                                            @endif
                                        </td>
                                        <td class="px-4 py-2 text-gray-500 text-sm capitalize">{{ $layout->door_position }}
                                        </td>
                                        <td class="px-4 py-2">
                                            @if($layout->is_active)
                                                <span
                                                    class="px-2 py-0.5 bg-green-100 text-green-700 rounded-full text-[10px] font-bold uppercase">Active</span>
                                            @else
                                                <span
                                                    class="px-2 py-0.5 bg-gray-100 text-gray-700 rounded-full text-[10px] font-bold uppercase">Inactive</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-2 text-right space-x-2">
                                            <button @click="$dispatch('open-modal', 'edit-layout-{{ $layout->id }}')"
                                                class="text-indigo-600 hover:underline font-bold uppercase tracking-widest text-[10px]">Edit</button>
                                            <form action="{{ route('room-layouts.destroy', $layout) }}" method="POST"
                                                class="inline">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                    class="text-red-600 hover:underline font-bold uppercase tracking-widest text-[10px]"
                                                    onclick="return confirm('Delete layout?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>

                                    <!-- Edit Modal -->
                                    <x-modal name="edit-layout-{{ $layout->id }}" focusable>
                                        <h2 class="text-lg font-medium text-gray-900 border-b py-3 px-5 mb-4">Edit Room
                                            Layout: {{ $layout->name }}</h2>
                                        <form method="post" action="{{ route('room-layouts.update', $layout) }}"
                                            enctype="multipart/form-data" class="">
                                            @csrf @method('PUT')
                                            <input type="hidden" name="layout_id" value="{{ $layout->id }}">

                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2 px-4">
                                                <div class="mt-4">
                                                    <x-input-label for="name" value="Layout Name" />
                                                    <x-text-input id="name" name="name" type="text"
                                                        class="mt-1 block w-full" placeholder="e.g. Standard Cold Room"
                                                        :value="old('name', $layout)" required />
                                                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                                </div>

                                                <div class="mt-4">
                                                    <x-input-label for="slug" value="Slug" />
                                                    <x-text-input id="slug" name="slug" type="text"
                                                        class="mt-1 block w-full" placeholder="e.g. SCR-001"
                                                        :value="old('slug', $layout)" required />
                                                    <x-input-error :messages="$errors->get('slug')" class="mt-2" />
                                                </div>

                                                <div class="mt-4">
                                                    <x-input-label for="layout_dimensions" value="Dimensions (L x W x H)" />
                                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-1">
                                                        <x-text-input id="r-d-width" name="r-d-width" type="text"
                                                            class="mt-1 block w-full" placeholder="5.00"
                                                            :value="old('r-d-width', is_array($layout->layout_dimensions) ? ($layout->layout_dimensions['width'] ?? '') : '')" required />
                                                        <x-text-input id="r-d-length" name="r-d-length" type="text"
                                                            class="mt-1 block w-full" placeholder="10.00"
                                                            :value="old('r-d-length', is_array($layout->layout_dimensions) ? ($layout->layout_dimensions['length'] ?? '') : '')" required />
                                                        <x-text-input id="r-d-height" name="r-d-height" type="text"
                                                            class="mt-1 block w-full" placeholder="6.80"
                                                            :value="old('r-d-height', is_array($layout->layout_dimensions) ? ($layout->layout_dimensions['height'] ?? '') : '')" required />
                                                    </div>

                                                    <x-input-error :messages="$errors->get('layout_dimensions')"
                                                        class="mt-2" />
                                                </div>

                                                <div class="mt-4">
                                                    <x-input-label for="door_dimensions" value="Door Dimensions (W x H)" />
                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-1">
                                                        <x-text-input id="d-d-width" name="d-d-width" type="text"
                                                            class="mt-1 block w-full" placeholder="2.00"
                                                            :value="old('d-d-width', is_array($layout->door_dimensions) ? ($layout->door_dimensions['width'] ?? '') : '')" required />
                                                        <x-text-input id="d-d-height" name="d-d-height" type="text"
                                                            class="mt-1 block w-full" placeholder="2.50"
                                                            :value="old('d-d-height', is_array($layout->door_dimensions) ? ($layout->door_dimensions['height'] ?? '') : '')" required />
                                                    </div>
                                                    <x-input-error :messages="$errors->get('door_dimensions')"
                                                        class="mt-2" />
                                                </div>

                                                <div class="mt-4">
                                                    <x-input-label for="door_position" value="Door Position" />
                                                    <select name="door_position"
                                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                        <option {{ old('door_position', $layout->door_position) == 'left' ? 'selected' : '' }}
                                                            value="left">Left</option>
                                                        <option {{ old('door_position', $layout->door_position) == 'center' ? 'selected' : '' }}
                                                            value="center">Center</option>
                                                        <option {{ old('door_position', $layout->door_position) == 'right' ? 'selected' : '' }}
                                                            value="right">Right</option>
                                                    </select>
                                                    <x-input-error :messages="$errors->get('door_position')" class="mt-2" />
                                                </div>

                                                <div class="mt-4">
                                                    <x-input-label for="wall_thickness" value="Wall Thickness (cm)" />
                                                    <x-text-input id="wall_thickness" name="wall_thickness" type="number"
                                                        step="0.01" class="mt-1 block w-full" placeholder="10.00"
                                                        :value="old('wall_thickness', $layout->wall_thickness)" required />
                                                    <x-input-error :messages="$errors->get('wall_thickness')"
                                                        class="mt-2" />
                                                </div>

                                                <div class="mt-4">
                                                    <x-input-label for="image" value="Layout Image" />
                                                    <x-file-input :value="old('image', $layout->image)"
                                                        @click="$el.closest('div').querySelector('input[type=file]').click()"
                                                        id="image" name="image" class="mt-1" accept="png,jpg,jpeg,webp" />
                                                    <x-input-error :messages="$errors->get('image')" class="mt-2" />
                                                </div>

                                                <!-- Display the layout image here -->
                                                 <div class="mt-4">
                                                    <img src="{{ asset('storage/layouts/' . $layout->image) }}" alt="{{ $layout->name }} Image" class="mt-2" />
                                                </div>
                                            </div>
                                            <div class="px-5">
                                                {{-- Active toggle --}}
                                                <div class="mt-4">
                                                    <label class="inline-flex items-center">
                                                        <input type="checkbox" name="is_active"
                                                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                                            {{ old('is_active', $layout->is_active) ? 'checked' : '' }}>
                                                        <span class="ms-2 text-sm text-gray-600">Active Template</span>
                                                    </label>
                                                </div>

                                            </div>
                                            <div class="mt-2 flex justify-end py-3 px-5">
                                                <x-button variant="secondary" type="button" size="sm"
                                                    x-on:click="$dispatch('close')">Cancel</x-button>
                                                <x-button type="submit" variant="primary" size="sm" class="ms-3">Save
                                                    Changes</x-button>
                                            </div>
                                        </form>
                                    </x-modal>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                                            <i class="fa fa-th-large text-4xl mb-3 block opacity-20"></i>
                                            No room layouts found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        @if($layouts->hasPages())
                            <div class="mt-4">
                                {{ $layouts->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Create Modal -->
        <x-modal name="create-layout" focusable>
            <form method="post" action="{{ route('room-layouts.store') }}" enctype="multipart/form-data" class="p-6">
                @csrf
                <h2 class="text-lg font-medium text-gray-900 border-b pb-3 mb-4">Create New Room Layout</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="mt-4">
                        <x-input-label for="name" value="Layout Name" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                            placeholder="e.g. Standard Cold Room" :value="old('name')" required />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="slug" value="Slug" />
                        <x-text-input id="slug" name="slug" type="text" class="mt-1 block w-full"
                            placeholder="e.g. SCR-001" :value="old('slug')" required />
                        <x-input-error :messages="$errors->get('slug')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="layout_dimensions" value="Dimensions (L x W x H)" />
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-1">
                            <x-text-input id="r-d-width" name="r-d-width" type="text" class="mt-1 block w-full"
                                placeholder="5.00" :value="old('r-d-width')" required />
                            <x-text-input id="r-d-length" name="r-d-length" type="text" class="mt-1 block w-full"
                                placeholder="10.00" :value="old('r-d-length')" required />
                            <x-text-input id="r-d-height" name="r-d-height" type="text" class="mt-1 block w-full"
                                placeholder="6.80" :value="old('r-d-height')" required />
                        </div>

                        <x-input-error :messages="$errors->get('layout_dimensions')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="door_dimensions" value="Door Dimensions (W x H)" />
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-1">
                            <x-text-input id="d-d-width" name="d-d-width" type="text" class="mt-1 block w-full"
                                placeholder="2.00" :value="old('d-d-width')" required />
                            <x-text-input id="d-d-height" name="d-d-height" type="text" class="mt-1 block w-full"
                                placeholder="2.50" :value="old('d-d-height')" required />
                        </div>
                        <x-input-error :messages="$errors->get('door_dimensions')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="door_position" value="Door Position" />
                        <select name="door_position"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option {{ old('door_position') == 'left' ? 'selected' : '' }} value="left">Left</option>
                            <option {{ old('door_position') == 'center' ? 'selected' : '' }} value="center">Center
                            </option>
                            <option {{ old('door_position') == 'right' ? 'selected' : '' }} value="right">Right</option>
                        </select>
                        <x-input-error :messages="$errors->get('door_position')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="wall_thickness" value="Wall Thickness (cm)" />
                        <x-text-input id="wall_thickness" name="wall_thickness" type="number" step="0.01"
                            class="mt-1 block w-full" placeholder="10.00" :value="old('wall_thickness')" required />
                        <x-input-error :messages="$errors->get('wall_thickness')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="image" value="Layout Image" />
                        <x-file-input @click="$el.closest('div').querySelector('input[type=file]').click()" id="image"
                            name="image" class="mt-1" accept="png,jpg,jpeg,webp" />
                        <x-input-error :messages="$errors->get('image')" class="mt-2" />
                    </div>
                </div>

                <div class="mt-4">
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="is_active"
                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" checked>
                        <span class="ms-2 text-sm text-gray-600">Active Template</span>
                    </label>
                </div>

                <div class="mt-6 flex justify-end">
                    <x-secondary-button x-on:click="$dispatch('close')">Cancel</x-secondary-button>
                    <x-button variant="primary" class="ms-3">Create Layout</x-button>
                </div>
            </form>
        </x-modal>
    </div>
</x-app-layout>