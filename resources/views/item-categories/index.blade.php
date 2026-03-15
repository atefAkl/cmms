<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Item Categories') }}
            </h2>
            <button @click="$dispatch('open-modal', 'create-category')" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow transition">
                + New Category
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
                                <th class="p-4 font-semibold text-gray-600">Slug</th>
                                <th class="p-4 font-semibold text-gray-600">Items Count</th>
                                <th class="p-4 font-semibold text-gray-600 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($categories as $category)
                                <tr class="border-b transition hover:bg-gray-50">
                                    <td class="p-4 font-medium text-gray-800">{{ $category->name }}</td>
                                    <td class="p-4 text-gray-500">{{ $category->slug }}</td>
                                    <td class="p-4">
                                        <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm">
                                            {{ $category->items_count }} items
                                        </span>
                                    </td>
                                    <td class="p-4 text-right space-x-2 text-sm">
                                        <button @click="$dispatch('open-modal', 'edit-category-{{ $category->id }}')" class="text-blue-600 hover:underline">Edit</button>
                                        <form action="{{ route('item-categories.destroy', $category) }}" method="POST" class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:underline" onclick="return confirm('Delete category?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>

                                <!-- Edit Modal -->
                                <x-modal name="edit-category-{{ $category->id }}" focusable>
                                    <form method="post" action="{{ route('item-categories.update', $category) }}" class="p-6">
                                        @csrf @method('PATCH')
                                        <h2 class="text-lg font-medium text-gray-900">Edit Category</h2>
                                        <div class="mt-4">
                                            <x-input-label for="name" value="Category Name" />
                                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="$category->name" required />
                                        </div>
                                        <div class="mt-4">
                                            <x-input-label for="description" value="Description" />
                                            <textarea name="description" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ $category->description }}</textarea>
                                        </div>
                                        <div class="mt-6 flex justify-end">
                                            <x-secondary-button x-on:click="$dispatch('close')">Cancel</x-secondary-button>
                                            <x-danger-button class="ms-3 bg-blue-600 hover:bg-blue-700 active:bg-blue-800">Save Changes</x-danger-button>
                                        </div>
                                    </form>
                                </x-modal>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-4">
                        {{ $categories->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Modal -->
    <x-modal name="create-category" focusable>
        <form method="post" action="{{ route('item-categories.store') }}" class="p-6">
            @csrf
            <h2 class="text-lg font-medium text-gray-900">Create New Category</h2>
            <div class="mt-4">
                <x-input-label for="name" value="Category Name" />
                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" placeholder="e.g. Spare Parts, Consumables" required />
            </div>
            <div class="mt-4">
                <x-input-label for="description" value="Description" />
                <textarea name="description" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
            </div>
            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">Cancel</x-secondary-button>
                <x-danger-button class="ms-3 bg-blue-600 hover:bg-blue-700 active:bg-blue-800">Create</x-danger-button>
            </div>
        </form>
    </x-modal>
</x-app-layout>
