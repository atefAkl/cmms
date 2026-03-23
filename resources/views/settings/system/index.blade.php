<x-app-layout>
    <div x-data="{ 
        showModal: false,
        newSetting: {
            key: '',
            display_name: '',
            group: 'general',
            type: 'string',
            value: '',
            description: '',
            options: [],
            range: { min: null, max: null }
        },
        addOption() {
            this.newSetting.options.push({ key: '', label: '' });
        },
        removeOption(index) {
            this.newSetting.options.splice(index, 1);
        },
        async saveNewSetting() {
            try {
                // Convert options array to object for backend
                const optionsObj = {};
                this.newSetting.options.forEach(opt => {
                    if (opt.key) optionsObj[opt.key] = opt.label;
                });

                const res = await fetch('{{ route('settings.system-settings.store') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        ...this.newSetting,
                        options: Object.keys(optionsObj).length > 0 ? optionsObj : null,
                        range: (this.newSetting.type === 'integer' || this.newSetting.type === 'float') ? this.newSetting.range : null
                    })
                });
                const data = await res.json();
                if (data.success) {
                    window.location.reload();
                } else {
                    alert('Error creating setting');
                }
            } catch (e) {
                console.error(e);
            }
        }
    }">
        <x-page-header title="System Settings" description="Configure application defaults and monitoring behaviors.">
            <div class="flex items-center gap-2">
                <x-button variant="primary" size="sm" @click="showModal = true">
                    <i class="fa fa-plus mr-2"></i> Add New Setting
                </x-button>
                <x-button variant="secondary" size="sm" onclick="window.history.back()">
                    <i class="fa fa-angle-left mr-2"></i> Back
                </x-button>
            </div>
        </x-page-header>

        <div class="py-6">
            <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
                <div class="space-y-4">
                    @foreach($settings as $group => $groupSettings)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100">
                            <div class="px-6 py-4 bg-gray-50 border-b border-gray-100">
                                <h4 class="text-xs font-black uppercase tracking-widest text-gray-400">
                                    {{ ucfirst($group) }} Settings
                                </h4>
                            </div>
                            <div class="divide-y divide-gray-100">
                                @foreach($groupSettings as $setting)
                                    <div class="px-6 py-6 flex items-center justify-between gap-6 hover:bg-gray-50/30 transition">
                                        <div class="flex-1">
                                            <div class="font-bold text-gray-900 mb-1">
                                                {{ $setting->display_name }}
                                                <span class="text-[10px] text-gray-400 font-mono ml-2 uppercase">#{{ $setting->key }}</span>
                                            </div>
                                            <p class="text-xs text-gray-400 italic max-w-xl">{{ $setting->description }}</p>
                                        </div>
                                        <div class="flex items-center gap-4" x-data="{ 
                                            value: '{{ $setting->value }}',
                                            isSaving: false,
                                            isError: false,
                                            isSuccess: false,
                                            async updateSetting(newValue) {
                                                this.isSaving = true;
                                                this.isError = false;
                                                this.isSuccess = false;
                                                try {
                                                    const res = await fetch('{{ route('settings.system-settings.update', $setting) }}', {
                                                        method: 'PATCH',
                                                        headers: {
                                                            'Content-Type': 'application/json',
                                                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                                        },
                                                        body: JSON.stringify({ value: newValue })
                                                    });
                                                    const data = await res.json();
                                                    if (data.success) {
                                                        this.isSuccess = true;
                                                        this.value = data.new_value;
                                                        setTimeout(() => this.isSuccess = false, 2000);
                                                    } else {
                                                        this.isError = true;
                                                    }
                                                } catch (e) {
                                                    this.isError = true;
                                                } finally {
                                                    this.isSaving = false;
                                                }
                                            }
                                        }">
                                            <div class="flex flex-col items-end gap-1">
                                                @if($setting->type === 'boolean')
                                                    <div class="flex items-center">
                                                        <input type="checkbox" :checked="value == '1'" 
                                                            @change="updateSetting($event.target.checked ? '1' : '0')"
                                                            class="w-5 h-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 cursor-pointer">
                                                    </div>
                                                @elseif($setting->type === 'select' && $setting->options)
                                                    <select :value="value" @change="updateSetting($event.target.value)"
                                                        class="px-3 py-1.5 bg-gray-50 border border-gray-200 rounded-lg text-sm font-bold focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all min-w-[150px]">
                                                        @foreach($setting->options as $val => $label)
                                                            <option value="{{ $val }}">{{ $label }}</option>
                                                        @endforeach
                                                    </select>
                                                @elseif($setting->type === 'integer' || $setting->type === 'float')
                                                    <input type="{{ $setting->type === 'integer' ? 'number' : 'text' }}" 
                                                        :value="value" @change="updateSetting($event.target.value)"
                                                        @if($setting->range)
                                                            @if(isset($setting->range['min'])) min="{{ $setting->range['min'] }}" @endif
                                                            @if(isset($setting->range['max'])) max="{{ $setting->range['max'] }}" @endif
                                                        @endif
                                                        class="w-24 px-3 py-1.5 bg-gray-50 border border-gray-200 rounded-lg text-sm font-bold focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-center">
                                                @elseif($setting->type === 'color')
                                                    <input type="color" :value="value" @change="updateSetting($event.target.value)"
                                                        class="w-12 h-8 p-0 border-none bg-transparent cursor-pointer">
                                                @else
                                                    <input type="text" :value="value" @change="updateSetting($event.target.value)"
                                                        class="px-3 py-1.5 bg-gray-50 border border-gray-200 rounded-lg text-sm font-bold focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                                @endif

                                                <div class="h-3 flex items-center mt-1">
                                                    <template x-if="isSaving">
                                                        <span class="text-[8px] text-gray-400 flex items-center uppercase font-black tracking-widest">
                                                            <i class="fa fa-spinner fa-spin mr-1"></i> Saving
                                                        </span>
                                                    </template>
                                                    <template x-if="isSuccess">
                                                        <span class="text-[8px] text-green-500 flex items-center uppercase font-black tracking-widest">
                                                            <i class="fa fa-check mr-1"></i> Saved
                                                        </span>
                                                    </template>
                                                    <template x-if="isError">
                                                        <span class="text-[8px] text-red-500 flex items-center uppercase font-black tracking-widest">
                                                            <i class="fa fa-times mr-1"></i> Error
                                                        </span>
                                                    </template>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Add Setting Modal -->
        <div x-show="showModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;" x-cloak>
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity" aria-hidden="true" @click="showModal = false">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>

                <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full">
                    <div class="bg-white px-6 py-6 border-b border-gray-100 flex items-center justify-between">
                        <h3 class="text-lg font-bold text-gray-900">Add New System Setting</h3>
                        <button @click="showModal = false" class="text-gray-400 hover:text-gray-500">
                            <i class="fa fa-times text-xl"></i>
                        </button>
                    </div>

                    <div class="bg-white px-6 py-6 space-y-4 max-h-[70vh] overflow-y-auto">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-black uppercase text-gray-400 mb-1">Display Name</label>
                                <input type="text" x-model="newSetting.display_name" class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all font-bold text-sm" placeholder="e.g. Max Daily Limit">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black uppercase text-gray-400 mb-1">Key (Internal Name)</label>
                                <input type="text" x-model="newSetting.key" class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all font-mono text-xs" placeholder="e.g. max_daily_limit">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-black uppercase text-gray-400 mb-1">Type</label>
                                <select x-model="newSetting.type" class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all font-bold text-sm">
                                    <option value="string">String (Text)</option>
                                    <option value="integer">Integer (Number)</option>
                                    <option value="float">Float (Decimal)</option>
                                    <option value="boolean">Boolean (Toggle)</option>
                                    <option value="select">Select (Dropdown)</option>
                                    <option value="color">Color Picker</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black uppercase text-gray-400 mb-1">Group</label>
                                <input type="text" x-model="newSetting.group" class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all font-bold text-sm" placeholder="e.g. general, monitoring">
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-black uppercase text-gray-400 mb-1">Description (Arabic/UI Help)</label>
                            <textarea x-model="newSetting.description" rows="2" class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all text-sm" placeholder="وصف موجز لهذا الإعداد..."></textarea>
                        </div>

                        <div>
                            <label class="block text-[10px] font-black uppercase text-gray-400 mb-1">Initial Value</label>
                            <template x-if="newSetting.type !== 'boolean'">
                                <input :type="newSetting.type === 'color' ? 'color' : (newSetting.type === 'integer' ? 'number' : 'text')" 
                                    x-model="newSetting.value" 
                                    class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all font-bold text-sm">
                            </template>
                            <template x-if="newSetting.type === 'boolean'">
                                <select x-model="newSetting.value" class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all font-bold text-sm">
                                    <option value="0">Off (False)</option>
                                    <option value="1">On (True)</option>
                                </select>
                            </template>
                        </div>

                        <!-- Special: Select Options -->
                        <template x-if="newSetting.type === 'select'">
                            <div class="space-y-2 p-4 bg-indigo-50 rounded-2xl border border-indigo-100">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-[10px] font-black uppercase text-indigo-400">Dropdown Options</span>
                                    <button @click="addOption()" class="text-xs font-bold text-indigo-600 hover:text-indigo-800">
                                        <i class="fa fa-plus mr-1"></i> Add Option
                                    </button>
                                </div>
                                <div class="space-y-2">
                                    <template x-for="(opt, index) in newSetting.options" :key="index">
                                        <div class="flex gap-2">
                                            <input type="text" x-model="opt.key" placeholder="Key (e.g. red)" class="flex-1 px-3 py-1.5 bg-white border border-indigo-200 rounded-lg text-xs">
                                            <input type="text" x-model="opt.label" placeholder="Label (e.g. Red Color)" class="flex-1 px-3 py-1.5 bg-white border border-indigo-200 rounded-lg text-xs">
                                            <button @click="removeOption(index)" class="text-red-400 hover:text-red-600 px-2">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </template>

                        <!-- Special: Range -->
                        <template x-if="newSetting.type === 'integer' || newSetting.type === 'float'">
                            <div class="grid grid-cols-2 gap-4 p-4 bg-indigo-50 rounded-2xl border border-indigo-100">
                                <div>
                                    <label class="block text-[10px] font-black uppercase text-indigo-400 mb-1">Min Value</label>
                                    <input type="number" x-model="newSetting.range.min" class="w-full px-3 py-1.5 bg-white border border-indigo-200 rounded-lg text-xs">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black uppercase text-indigo-400 mb-1">Max Value</label>
                                    <input type="number" x-model="newSetting.range.max" class="w-full px-3 py-1.5 bg-white border border-indigo-200 rounded-lg text-xs">
                                </div>
                            </div>
                        </template>
                    </div>

                    <div class="bg-gray-50 px-6 py-4 flex flex-row-reverse gap-3">
                        <button type="button" @click="saveNewSetting()" class="inline-flex justify-center py-2 px-6 border border-transparent shadow-sm text-sm font-bold rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all">
                            Create Setting
                        </button>
                        <button type="button" @click="showModal = false" class="inline-flex justify-center py-2 px-6 border border-gray-300 shadow-sm text-sm font-bold rounded-xl text-gray-700 bg-white hover:bg-gray-50 focus:outline-none transition-all">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
