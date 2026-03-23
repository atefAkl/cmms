<x-app-layout>
    <x-page-header title="{{ __('System Details') }}: {{ $refrigerationSystem->name }}">
        <div class="flex space-x-3">
            <a href="{{ route('refrigeration-systems.edit', $refrigerationSystem) }}"
                class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 transition">
                {{ __('Edit System') }}
            </a>
            <a href="{{ route('refrigeration-systems.index') }}"
                class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 transition shadow-sm">
                {{ __('Back to List') }}
            </a>
        </div>
    </x-page-header>

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <div class="lg:col-span-2 space-y-8">
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl p-8 border border-gray-100">
                        <div class="flex items-center justify-between mb-8 border-b border-gray-50 pb-6">
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900">{{ $refrigerationSystem->name }}</h3>
                                <p class="text-sm text-gray-500 mt-1">Installed on
                                    {{ $refrigerationSystem->installed_at ? \Illuminate\Support\Carbon::parse($refrigerationSystem->installed_at)->format('F d, Y') : 'Unknown Date' }}
                                </p>
                            </div>
                            <span
                                class="px-4 py-1.5 rounded-full text-sm font-bold {{ $refrigerationSystem->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ strtoupper($refrigerationSystem->status) }}
                            </span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-4">
                                <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest">General
                                    Information</h4>
                                <div class="bg-gray-50 rounded-xl p-4 flex items-center">
                                    <div class="p-2 bg-white rounded-lg shadow-sm mr-4 text-indigo-500">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path
                                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"
                                                stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 mb-0.5">Room Location</p>
                                        <div class="flex items-center gap-2">
                                            <p
                                                class="text-sm font-semibold {{ $refrigerationSystem->room ? 'text-gray-900' : 'text-gray-400 italic' }}">
                                                {{ $refrigerationSystem->room->name ?? 'Unassigned' }}
                                            </p>
                                            @if(!$refrigerationSystem->room)
                                                <a href="{{ route('refrigeration-systems.edit', $refrigerationSystem) }}"
                                                    class="inline-flex items-center px-2 py-0.5 bg-indigo-50 border border-indigo-200 text-indigo-600 rounded text-[9px] font-black uppercase tracking-widest hover:bg-indigo-600 hover:text-white transition shadow-sm">
                                                    Assign Room
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{ $refrigerationSystem }}
                    <!-- Hierarchical Asset Tree -->
                    <div x-data="{ treeExpanded: true }"
                        class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100 relative min-h-[400px]">

                        <div
                            class="px-8 py-5 border-b border-gray-50 flex items-center justify-between bg-white z-10 sticky top-0 shadow-sm">
                            <h3
                                class="font-bold text-gray-900 border-l-4 border-indigo-500 pl-4 flex items-center gap-2">
                                <i class="fa fa-sitemap text-indigo-400"></i> Asset Architecture
                            </h3>

                            <div class="flex items-center gap-3">
                                @if($refrigerationSystem->assets->isEmpty())
                                    <span
                                        class="text-[10px] font-black uppercase tracking-widest text-emerald-600 bg-emerald-50 px-3 py-1 rounded-full"><i
                                            class="fa fa-info-circle mr-1"></i> Use initialization wizard</span>
                                @else
                                    <button
                                        @click="$dispatch('open-asset-modal', { system_id: {{ $refrigerationSystem->id }} })"
                                        class="inline-flex items-center px-4 py-2 bg-slate-900 text-white rounded-lg font-black text-[10px] uppercase tracking-widest hover:bg-black transition shadow-[0_4px_14px_0_rgba(15,23,42,0.39)]">
                                        <i class="fa fa-plus mr-1.5 text-indigo-400"></i> Add Root Asset
                                    </button>
                                @endif
                            </div>
                        </div>

                        <!-- Core Rendering Space -->
                        <div class="p-6 bg-slate-50/30">
                            @forelse($refrigerationSystem->topLevelAssets as $rootAsset)
                                @include('refrigeration-systems._asset_node', ['asset' => $rootAsset])
                            @empty
                                <!-- Empty UI Placeholder -->
                                <div class="flex flex-col items-center justify-center py-16 text-center">
                                    <div
                                        class="w-20 h-20 bg-indigo-50/50 text-indigo-300 rounded-full flex items-center justify-center mb-5 text-3xl shadow-inner border border-indigo-100">
                                        <i class="fa fa-cubes"></i>
                                    </div>
                                    <h4 class="text-base font-black text-slate-800 mb-2 uppercase tracking-wide">Blank
                                        Architecture Slate</h4>
                                    <p class="text-sm font-medium text-slate-500 max-w-sm mb-6 leading-relaxed">No hardware
                                        configured. Utilize the Initialization Baseline tool in the sidebar to auto-generate
                                        a generic profile, or manually formulate a root asset to get started.</p>
                                    <button
                                        @click="$dispatch('open-asset-modal', { system_id: {{ $refrigerationSystem->id }} })"
                                        class="px-5 py-2.5 outline outline-2 outline-indigo-200 bg-white text-indigo-600 font-black text-[10px] uppercase tracking-widest rounded-lg shadow-sm hover:bg-indigo-50 hover:outline-indigo-300 transition focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        <i class="fa fa-plus mr-2"></i> Construct Manual Asset
                                    </button>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="space-y-8">
                    @if($refrigerationSystem->assets->isEmpty())
                        <div
                            class="bg-indigo-50 border-l-4 border-indigo-500 overflow-hidden shadow-sm sm:rounded-xl p-6 relative">
                            <h4 class="text-xs font-black text-indigo-800 uppercase tracking-widest mb-2 flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"
                                        stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                                </svg>
                                Initialize Hardware Baseline
                            </h4>
                            <p class="text-xs font-bold text-indigo-600 mb-4 tracking-wide leading-relaxed">
                                This system currently has no hardware components attached. Scaffold the predefined
                                sub-system framework now.
                            </p>

                            <form action="{{ route('systems.initialize', $refrigerationSystem) }}" method="POST">
                                @csrf
                                <div class="space-y-3 mb-4">
                                    <div
                                        class="bg-white/50 p-2 rounded-lg border border-indigo-100 flex items-center justify-between">
                                        <label for="compressors_count"
                                            class="text-[10px] font-black uppercase tracking-widest text-indigo-900">Total
                                            Compressors</label>
                                        <input id="compressors_count" type="number" name="compressors_count"
                                            class="w-16 h-8 text-center text-sm font-bold border-indigo-200 rounded text-indigo-900 focus:ring-indigo-500"
                                            min="1" max="10" value="1" required />
                                    </div>
                                    <div
                                        class="bg-white/50 p-2 rounded-lg border border-indigo-100 flex items-center justify-between">
                                        <label for="evaporators_count"
                                            class="text-[10px] font-black uppercase tracking-widest text-indigo-900">Total
                                            Evaporators</label>
                                        <input id="evaporators_count" type="number" name="evaporators_count"
                                            class="w-16 h-8 text-center text-sm font-bold border-indigo-200 rounded text-indigo-900 focus:ring-indigo-500"
                                            min="1" max="10" value="1" required />
                                    </div>
                                </div>
                                <button
                                    class="w-full justify-center inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-black text-xs text-white uppercase tracking-widest hover:bg-indigo-500 focus:bg-indigo-500 active:bg-indigo-700 transition shadow-[0_4px_14px_0_rgba(79,70,229,0.39)]">
                                    Initialize Templates
                                </button>
                            </form>
                        </div>
                    @endif

                    <div
                        class="bg-white overflow-hidden shadow-xl sm:rounded-2xl p-6 border border-gray-100 overflow-hidden relative">
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Latest Temperature
                        </h4>
                        <div class="flex items-center">
                            <span
                                class="text-5xl font-black text-gray-900 tracking-tighter">{{ $refrigerationSystem->temperatureReadings->first()->temperature ?? 'N/A' }}</span>
                            @if($refrigerationSystem->temperatureReadings->first()) <span
                            class="text-2xl font-bold text-blue-500 ml-2">°C</span> @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Asset Add/Edit Modal (AlpineJS) -->
    <div x-data="{ 
            open: false, 
            isEdit: false,
            formAction: '',
            method: 'POST',
            allInventoryItems: {{ $inventoryItems->toJson() }},
            filteredItems: [],
            systemCreatedAt: '{{ $refrigerationSystem->created_at->format('Y-m-d') }}',
            formData: {
                asset_id: null,
                name: '',
                type: '',
                item_category_id: '',
                inventory_item_id: '',
                status: 'configuration',
                parent_id: '',
                refrigeration_system_id: '',
                install_date: ''
            },
            updateFilteredItems() {
                if (!this.formData.item_category_id) {
                    this.filteredItems = [];
                    return;
                }
                this.filteredItems = this.allInventoryItems.filter(item => item.category_id == this.formData.item_category_id);
            },
            onItemSelect() {
                const selectedItem = this.allInventoryItems.find(item => item.id == this.formData.inventory_item_id);
                if (selectedItem) {
                    this.formData.name = selectedItem.name;
                    // If we want to set the type name from category name:
                    const category = document.querySelector('#item_category_id option:checked');
                    if (category) {
                        this.formData.type = category.textContent.trim();
                    }
                }
            }
         }" x-show="open" @open-asset-modal.window="
            open = true; 
            isEdit = !!$event.detail.asset_id;
            formData.asset_id = $event.detail.asset_id || null;
            formData.name = $event.detail.name || '';
            formData.type = $event.detail.type || '';
            formData.item_category_id = $event.detail.item_category_id || '';
            formData.inventory_item_id = $event.detail.inventory_item_id || '';
            formData.status = $event.detail.status || (isEdit ? 'operational' : 'configuration');
            formData.parent_id = $event.detail.parent_id || '';
            formData.refrigeration_system_id = $event.detail.system_id || '';
            formData.install_date = $event.detail.install_date || (isEdit ? '' : systemCreatedAt);
            
            updateFilteredItems();
            formAction = isEdit ? '{{ url('assets') }}/' + formData.asset_id : '{{ route('assets.store') }}';
         " style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title"
        role="dialog" aria-modal="true">

        <div class="fixed inset-0 bg-slate-900/75 backdrop-blur-sm transition-opacity" @click="open = false"
            x-show="open" x-transition.opacity></div>

        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div x-show="open" x-transition.scale.origin.bottom
                class="relative transform overflow-visible rounded-xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-slate-200">
                <form :action="formAction" method="POST">
                    @csrf
                    <template x-if="isEdit"><input type="hidden" name="_method" value="PUT"></template>
                    <input type="hidden" name="refrigeration_system_id" :value="formData.refrigeration_system_id">
                    <input type="hidden" name="parent_id" :value="formData.parent_id">
                    <input type="hidden" name="type" :value="formData.type">

                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4 rounded-t-xl">
                        <div class="sm:flex sm:items-start">
                            <div
                                class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-indigo-100 sm:mx-0 sm:h-10 sm:w-10">
                                <i class="fa fa-microchip text-indigo-600 text-lg"></i>
                            </div>
                            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                                <h3 class="text-base font-black leading-6 text-slate-900 uppercase tracking-widest"
                                    id="modal-title" x-text="isEdit ? 'تعديل بيانات القطعة' : 'إضافة قطعة للنظام'"></h3>
                                <div class="mt-4 space-y-4 text-left">
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label for="item_category_id"
                                                class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">نوع
                                                المكون (الفئة)</label>
                                            <select id="item_category_id" name="item_category_id"
                                                x-model="formData.item_category_id" @change="updateFilteredItems()"
                                                required
                                                class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 font-bold">
                                                <option value="">-- اختر الفئة --</option>
                                                @foreach($categories as $category)
                                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label for="inventory_item_id"
                                                class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">الجهاز
                                                / قطعة الغيار</label>
                                            <select id="inventory_item_id" name="inventory_item_id"
                                                x-model="formData.inventory_item_id" @change="onItemSelect()" required
                                                class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 font-bold">
                                                <option value="">-- اختر المنتج --</option>
                                                <template x-for="item in filteredItems" :key="item.id">
                                                    <option :value="item.id" x-text="item.name"></option>
                                                </template>
                                            </select>
                                        </div>
                                    </div>

                                    <div>
                                        <label for="name"
                                            class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">التسمية
                                            الفنية (الاسم)</label>
                                        <input type="text" id="name" name="name" x-model="formData.name" required
                                            class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 font-bold"
                                            placeholder="مثال: ضاغط رقم 1">
                                    </div>

                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label for="status"
                                                class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">الحالة</label>
                                            <select id="status" name="status" x-model="formData.status" required
                                                class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 font-bold">
                                                <option value="configuration">تكوين (أصل)</option>
                                                <option value="operational">يعمل (Operational)</option>
                                                <option value="maintenance">تحت الصيانة</option>
                                                <option value="failed">عطل</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label for="install_date"
                                                class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">تاريخ
                                                التثبيت / الإضافة</label>
                                            <input type="date" id="install_date" name="install_date"
                                                x-model="formData.install_date" readonly
                                                class="block w-full rounded-md border-0 py-1.5 bg-gray-50 text-slate-500 shadow-sm ring-1 ring-inset ring-slate-300 sm:text-sm sm:leading-6 font-bold">
                                        </div>
                                    </div>

                                    <template x-if="formData.parent_id">
                                        <div
                                            class="bg-indigo-50 p-2 rounded text-[10px] font-bold text-indigo-700 flex items-center">
                                            <i class="fa fa-link mr-2"></i> يتم ربطها كعنصر تابع (مكون فرعي).
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div
                        class="bg-slate-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 rounded-b-xl border-t border-gray-100">
                        <button type="submit"
                            class="inline-flex w-full justify-center rounded-md bg-indigo-600 px-4 py-2 text-xs font-black uppercase tracking-widest text-white shadow-sm hover:bg-indigo-500 sm:ml-3 sm:w-auto transition">Integrate
                            Node</button>
                        <button type="button" @click="open = false"
                            class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-4 py-2 text-xs font-black uppercase tracking-widest text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 sm:mt-0 sm:w-auto transition">Abort</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>