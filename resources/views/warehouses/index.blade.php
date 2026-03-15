<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Warehouses') }}
            </h2>
            <button @click="$dispatch('open-modal', 'create-warehouse')" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg shadow transition">
                + New Warehouse
            </button>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100">
                <div class="p-6 text-gray-900">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 border-b">
                                <th class="p-4 font-semibold text-gray-600">Name</th>
                                <th class="p-4 font-semibold text-gray-600">Location</th>
                                <th class="p-4 font-semibold text-gray-600 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($warehouses as $warehouse)
                                <tr class="border-b transition hover:bg-gray-50">
                                    <td class="p-4 font-medium text-gray-800">{{ $warehouse->name }}</td>
                                    <td class="p-4 text-gray-500">{{ $warehouse->location ?? 'N/A' }}</td>
                                    <td class="p-4 text-right space-x-2 text-sm">
                                        <button @click="$dispatch('open-modal', 'edit-warehouse-{{ $warehouse->id }}')" class="text-indigo-600 hover:underline">Edit</button>
                                        <form action="{{ route('warehouses.destroy', $warehouse) }}" method="POST" class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:underline" onclick="return confirm('Delete warehouse?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>

                                <!-- Edit Modal -->
                                <x-modal name="edit-warehouse-{{ $warehouse->id }}" focusable>
                                    <form method="post" action="{{ route('warehouses.update', $warehouse) }}" class="p-6">
                                        @csrf @method('PATCH')
                                        <h2 class="text-lg font-medium text-gray-900">Edit Warehouse</h2>
                                        <div class="mt-4">
                                            <x-input-label for="name" value="Warehouse Name" />
                                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="$warehouse->name" required />
                                        </div>
                                        <div class="mt-4">
                                            <x-input-label for="location" value="Location" />
                                            <x-text-input id="location" name="location" type="text" class="mt-1 block w-full" :value="$warehouse->location" />
                                        </div>
                                        <div class="mt-6 flex justify-end">
                                            <x-secondary-button x-on:click="$dispatch('close')">Cancel</x-secondary-button>
                                            <x-danger-button class="ms-3 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800">Save Changes</x-danger-button>
                                        </div>
                                    </form>
                                </x-modal>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-4">
                        {{ $warehouses->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Modal -->
    <x-modal name="create-warehouse" focusable>
        <form method="post" action="{{ route('warehouses.store') }}" class="p-6">
            @csrf
            <h2 class="text-lg font-medium text-gray-900">Create New Warehouse</h2>
            <div class="mt-4">
                <x-input-label for="name" value="Warehouse Name" />
                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" placeholder="e.g. Main Store, Floor-1 Spare Rack" required />
            </div>
            <div class="mt-4">
                <x-input-label for="location" value="Location" />
                <x-text-input id="location" name="location" type="text" class="mt-1 block w-full" placeholder="Internal address or coordinates" />
            </div>
            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">Cancel</x-secondary-button>
                <x-danger-button class="ms-3 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800">Create</x-danger-button>
            </div>
        </form>
    </x-modal>
</x-app-layout>
