<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-page-header 
                title="{{ __('Create Room') }}" 
                description="Add a new cold storage facility to the system."
                :backRoute="route('rooms.index')"
            />
     
            

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100 p-8">
                <form method="POST" action="{{ route('rooms.store') }}">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Name -->
                        <div>
                            <x-input-label for="name" :value="__('Room Name')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name"
                                :value="old('name')" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>
                        <!-- warehouse_id -->
                        <div>
                            <x-input-label for="warehouse_id" :value="__('Warehouse')" />
                            <select id="warehouse_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" name="warehouse_id" required autofocus>
                                <option value="">Select Warehouse</option>
                                @foreach($warehouses as $warehouse)
                                    <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('warehouse_id')" class="mt-2" />
                        </div>
                        <!-- room_layout_id -->
                        <div>
                            <x-input-label for="layout_id" :value="__('Room Layout')" />
                            <select id="layout_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" name="room_layout_id" required autofocus>
                                <option value="">Select Layout</option>
                                @foreach($layouts as $layout)
                                    <option value="{{ $layout->id }}">{{ $layout->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('layout_id')" class="mt-2" />
                        </div>
                        
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <x-primary-button class="ms-4">
                            {{ __('Create Room') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>