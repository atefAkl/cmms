<x-app-layout>
    <!--  -->

    <div x-data="{ 
        open: false, 
        editMode: false, 
        action: '{{ route('devices.store') }}',
        deviceName: '',
        deviceDescription: '',
        deviceId: '',
        openModal(edit = false, device = null) {
            this.editMode = edit;
            if (edit && device) {
                this.action = '/devices/' + device.id;
                this.deviceName = device.name;
                this.deviceDescription = device.description;
                this.deviceId = device.id;
            } else {
                this.action = '{{ route('devices.store') }}';
                this.deviceName = '';
                this.deviceDescription = '';
                this.deviceId = '';
            }
            this.open = true;
        }
    }" class="py-2 px-6 ">
        <!-- Standard Header as per Image 1 -->
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center gap-4">
                <a href="{{ route('settings.index') }}"
                    class="p-2 bg-white border border-gray-200 rounded-lg text-gray-400 hover:text-gray-600 transition shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                        </path>
                    </svg>
                </a>
                <h2 class="font-bold text-xl text-gray-800 leading-tight">
                    {{ __('Device Catalog') }}
                </h2>
            </div>

            <x-button @click="openModal()" variant="primary" size="sm">
                + {{ __('New') }}
            </x-button>
        </div>

        <!-- Alpine.js Modal -->
        <div x-show="open" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" @click="open = false"></div>

                <div
                    class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form :action="action" method="POST">
                        @csrf
                        <template x-if="editMode">
                            <input type="hidden" name="_method" value="PUT">
                        </template>

                        <div class="bg-white px-8 pt-8 pb-4">
                            <h3 class="text-xl font-black text-gray-900 uppercase tracking-tighter"
                                x-text="editMode ? 'Edit Device Type' : 'Add Device Type'"></h3>
                            <div class="mt-6 space-y-4">
                                <div>
                                    <x-input-label for="name" :value="__('Name')" />
                                    <x-text-input id="name" name="name" type="text"
                                        class="mt-1 block w-full bg-gray-50 border-gray-200" x-model="deviceName"
                                        required />
                                </div>
                                <div>
                                    <x-input-label for="description" :value="__('Description')" />
                                    <textarea id="description" name="description"
                                        class="mt-1 block w-full border-gray-200 bg-gray-50 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm"
                                        rows="3" x-model="deviceDescription" required></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-8 py-6 flex flex-row-reverse gap-3">
                            <x-button variant="primary" type="submit">
                                <span x-text="editMode ? 'Update' : 'Save'"></span>
                            </x-button>
                            <x-button variant="secondary" type="button" @click="open = false">
                                Cancel
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Standard Table as per Image 1 -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-100">
            <x-auth-session-status class="p-4" :status="session('success')" />
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100">
                            <th
                                class="px-8 py-3 text-left text-[11px] font-bold text-gray-400 uppercase tracking-widest">
                                Name</th>
                            <th
                                class="px-8 py-3 text-left text-[11px] font-bold text-gray-400 uppercase tracking-widest">
                                Description</th>
                            <th
                                class="px-8 py-3 text-right text-[11px] font-bold text-gray-400 uppercase tracking-widest">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($devices as $device)
                            <tr>
                                <td class="px-8 py-2 whitespace-nowrap font-bold text-gray-800">{{ $device->name }}</td>
                                <td class="px-8 py-2 text-gray-500">{{ $device->description }}</td>
                                <td class="px-8 py-2 text-right font-bold text-sm">
                                    <x-button @click="openModal(true, {{ json_encode($device) }})" variant="success"
                                        size="xs" class="me-2">{{ __('Edit') }}</x-button>
                                    <form action="{{ route('devices.destroy', $device) }}" method="POST" class="inline"
                                        onsubmit="return confirm('Delete this device type?')">
                                        @csrf
                                        @method('DELETE')
                                        <x-button variant="danger" size="xs">{{ __('Delete') }}</x-button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-8 py-20 text-center text-gray-400 italic">
                                    No device types found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</x-app-layout>