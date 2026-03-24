<div class="asset-node" x-data="{ expanded: {{ $component->parent_id ? 'false' : 'true' }} }">
    <div class="flex items-center justify-between p-3 bg-white border border-gray-100 rounded-xl mb-3 shadow-sm hover:shadow-md hover:border-indigo-100 transition group" :id="'component-node-' + {{ $component->id }}">
        
        <div class="flex items-center gap-3">
            <!-- Expand/Collapse Handle -->
            @if($component->children->count() > 0)
                <button @click.prevent="expanded = !expanded" class="text-gray-400 hover:text-indigo-600 focus:outline-none w-5 h-5 flex items-center justify-center transition">
                    <i class="fa" :class="expanded ? 'fa-chevron-down' : 'fa-chevron-right'"></i>
                </button>
            @else
                <div class="w-5 h-5 flex items-center justify-center text-gray-200"><i class="fa fa-minus text-[8px]"></i></div>
            @endif

            <!-- Asset Icon -->
            <div class="p-2.5 rounded-lg bg-emerald-50 text-emerald-500 border border-emerald-100 shadow-sm flex items-center justify-center">
                <i class="fa fa-cogs text-xs"></i>
            </div>
            
            <div>
                <h4 class="text-sm font-bold text-gray-900 leading-none mb-1">{{ $component->name }}</h4>
                <div class="flex items-center gap-2">
                    <span class="text-[9px] font-black uppercase tracking-widest text-emerald-700 bg-emerald-100 px-2.5 py-1 rounded">
                        {{ $component->component_type ?? 'Component' }}
                    </span>
                    <span class="text-[9px] font-black uppercase tracking-widest {{ $component->status === 'working' ? 'text-green-700 bg-green-100' : ($component->status === 'stopped' ? 'text-rose-700 bg-rose-100' : 'text-gray-500 bg-gray-100') }} px-2.5 py-1 rounded">
                        {{ strtoupper($component->status ?? 'UNKNOWN') }}
                    </span>
                    @if($component->product)
                        <span class="text-[9px] font-bold text-gray-500"><i class="fa fa-box mr-1"></i>{{ $component->product->name }}</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Hover Actions -->
        <div class="opacity-0 group-hover:opacity-100 transition-opacity flex items-center gap-1 pr-2">
            @if($component->level < 3)
            <button @click="$dispatch('open-component-modal', { parent_id: {{ $component->id }}, system_id: {{ $component->refrigeration_system_id }} })" class="text-xs font-bold text-gray-500 hover:text-emerald-700 px-2 py-1 rounded hover:bg-emerald-50 transition" title="Add Sub-Component">
                <i class="fa fa-plus"></i> <span class="hidden sm:inline ml-1">Add Sub</span>
            </button>
            <div class="w-px h-4 bg-gray-200 mx-1"></div>
            @endif
            <button @click="$dispatch('open-component-modal', { 
                component_id: {{ $component->id }}, 
                name: '{{ addslashes($component->name) }}', 
                component_type: '{{ $component->component_type }}', 
                product_id: '{{ $component->product_id }}',
                status: '{{ $component->status }}', 
                install_type: '{{ $component->install_type }}', 
                install_date: '{{ $component->installed ? \Carbon\Carbon::parse($component->installed)->format('Y-m-d') : '' }}', 
                parent_id: {{ $component->parent_id ?? 'null' }}, 
                system_id: {{ $component->refrigeration_system_id }},
                serial: '{{ $component->metadata['serial'] ?? '' }}',
                position: '{{ $component->metadata['position'] ?? '' }}'
            })" class="text-xs font-bold text-gray-400 hover:text-gray-900 px-2 py-1 rounded hover:bg-gray-100 transition" title="Edit Component">
                <i class="fa fa-edit"></i>
            </button>
            <button @click="deleteComponent({{ $component->id }})" class="text-xs font-bold text-rose-400 hover:text-rose-700 px-2 py-1 rounded hover:bg-rose-50 transition" title="Delete Component">
                <i class="fa fa-trash"></i>
            </button>
        </div>
    </div>

    <!-- Recursive Children Render Block -->
    @if($component->children->count() > 0)
        <!-- Note: Need Alpine Collapse plugin for x-collapse, fallback to simple x-show if unavailable -->
        <div x-show="expanded" class="transition-all">
            <div class="pl-8 border-l-2 border-dashed border-emerald-100 ml-5 mb-2 relative">
                <!-- Visual Connection Line -->
                <div class="absolute w-4 h-px bg-emerald-100 top-6 -left-0.5"></div>
                @foreach($component->children as $child)
                    @include('refrigeration-systems._system_device_node', ['component' => $child])
                @endforeach
            </div>
        </div>
    @endif
</div>
