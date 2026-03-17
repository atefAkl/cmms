<x-app-layout>
    <div x-data="{}" x-init="
        @if(session('opened'))
            $nextTick(() => $dispatch('open-modal', '{{ session('opened') }}'));
        @elseif($errors->any())
            @if(old('category_id'))
                $nextTick(() => $dispatch('open-modal', 'edit-category-{{ old('category_id') }}'));
            @else
                $nextTick(() => $dispatch('open-modal', 'create-category'));
            @endif
        @endif
    ">






        <div class="py-2">
            <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
    <x-page-header 
        title="{{ __('Item Categories') }}" 
        description="Organize your items into parent-child relationships."
    >
        <x-button variant="primary" size="sm" @click="$dispatch('open-modal', 'create-category')">
            + {{__('New')}}
        </x-button>
    </x-page-header>
                <div class="bg-white overflow-hidden shadow sm:rounded-lg border border-gray-100">

                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-100">
                                <th class="px-4 py-3 text-[10px] font-black uppercase tracking-widest text-gray-400">Name</th>
                                <th class="px-4 py-3 text-[10px] font-black uppercase tracking-widest text-gray-400">Slug</th>
                                <th class="px-4 py-3 text-[10px] font-black uppercase tracking-widest text-gray-400">Items Count</th>
                                <th class="px-4 py-3 text-[10px] font-black uppercase tracking-widest text-gray-400">Parent</th>
                                <th class="px-4 py-3 text-[10px] font-black uppercase tracking-widest text-gray-400 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($allCategories as $category)
                                <tr class="border-b transition bg-gray-50 hover:bg-gray-100">
                                    <td class="px-4 py-1 font-medium text-gray-800">{{ $category->name }}</td>
                                    <td class="px-4 py-1 text-gray-500">{{ $category->slug }}</td>
                                    <td class="px-4 py-1">
                                        <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm">
                                            {{ $category->items_count }} items
                                        </span>
                                    </td>
                                    <td class="px-4 py-1">
                                        <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm">
                                            {{ $category->parent->name ?? 'Root' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-1 text-right space-x-2 text-sm">
                                        <button @click="$dispatch('open-modal', 'edit-category-{{ $category->id }}')"
                                            class="text-blue-600 hover:underline">Edit</button>
                                        <form action="{{ route('item-categories.destroy', $category) }}" method="POST"
                                            class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:underline"
                                                onclick="return confirm('Delete category?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>

                                <!-- Edit Modal -->
                                <x-modal name="edit-category-{{ $category->id }}" focusable>
                                    <form method="post" action="{{ route('item-categories.update', $category) }}">
                                        @csrf @method('PUT')
                                        <div class="p-6">
                                            <div class="mt-4">
                                                <x-input-label for="name" value="Category Name" />
                                                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                                                    placeholder="e.g. Spare Parts, Consumables" required
                                                    :value="old('name', $category->name)" />
                                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                            </div>
                                            <div class="mt-4">
                                                <x-input-label for="description" value="Description" />
                                                <textarea name="description"
                                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('description', $category->description) }}</textarea>
                                                <x-input-error :messages="$errors->get('description')" class="mt-2" />
                                            </div>
                                            <div class="mt-4">
                                                <select name="parent_id"
                                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                                    <option value="">Select Parent Category</option>
                                                    @foreach($categories as $cat)
                                                        @if ($cat->parent)
                                                            <option value="{{ $cat->id }}" {{ old('parent_id', $category->parent_id) == $cat->id ? 'selected' : '' }}>
                                                                {{ $cat->parent->name }} - {{ $cat->name }}
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                                <x-input-error :messages="$errors->get('parent_id')" class="mt-2" />
                                            </div>

                                            <div class="mt-6 flex justify-end">
                                                <x-button variant="secondary"
                                                    x-on:click="$dispatch('close')">Cancel</x-button>
                                                <x-button variant="primary"
                                                    class="ms-3 bg-blue-600 hover:bg-blue-700 active:bg-blue-800">Save
                                                    Changes</x-button>
                                            </div>
                                        </div>
                                    </form>
                                </x-modal>
                            @endforeach
                        </tbody>
                    </table>
                    @if($allCategories->hasPages())
                        <div class="mt-4">
                            {{ $allCategories->links() }}
                        </div>
                    @endif

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
                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                        placeholder="e.g. Spare Parts, Consumables" required :value="old('name')" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>
                <div class="mt-4">
                    <x-input-label for="description" value="Description" />
                    <textarea name="description"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('description') }}</textarea>
                    <x-input-error :messages="$errors->get('description')" class="mt-2" />
                </div>
                <div class="mt-4">
                    <select name="parent_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        <option value="">Select Parent Category</option>
                        @foreach($categories as $category)
                            @if ($category->parent)
                                <option value="{{ $category->id }}" {{ old('parent_id', $category->parent_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->parent->name }} - {{ $category->name }}
                                </option>
                            @endif
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('parent_id')" class="mt-2" />
                </div>
                <div class="mt-6 flex justify-end">
                    <x-button variant="secondary" x-on:click="$dispatch('close')">Cancel</x-button>
                    <x-button variant="primary"
                        class="ms-3 bg-blue-600 hover:bg-blue-700 active:bg-blue-800">Create</x-button>
                </div>
            </form>
        </x-modal>
</x-app-layout>