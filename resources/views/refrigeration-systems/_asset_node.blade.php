<div class="asset-node" x-data="{ expanded: {{ $asset->parent_id ? 'false' : 'true' }} }">
    <div class="flex items-center justify-between p-3 bg-white border border-gray-100 rounded-xl mb-3 shadow-sm hover:shadow-md hover:border-indigo-100 transition group">
        
        <div class="flex items-center gap-3">
            <!-- Expand/Collapse Handle -->
            @if($asset->children->count() > 0)
                <button @click.prevent="expanded = !expanded" class="text-gray-400 hover:text-indigo-600 focus:outline-none w-5 h-5 flex items-center justify-center transition">
                    <i class="fa" :class="expanded ? 'fa-chevron-down' : 'fa-chevron-right'"></i>
                </button>
            @else
                <div class="w-5 h-5 flex items-center justify-center text-gray-200"><i class="fa fa-minus text-[8px]"></i></div>
            @endif

            <!-- Asset Icon -->
            <div class="p-2.5 rounded-lg bg-indigo-50 text-indigo-500 border border-indigo-100 shadow-sm flex items-center justify-center">
                <i class="fa fa-microchip text-xs"></i>
            </div>
            
            <div>
                <h4 class="text-sm font-bold text-gray-900 leading-none mb-1">{{ $asset->name }}</h4>
                <div class="flex items-center gap-2">
                    <span class="text-[9px] font-black uppercase tracking-widest text-indigo-700 bg-indigo-100 px-2.5 py-1 rounded">
                        {{ $asset->itemCategory->name ?? $asset->type }}
                    </span>
                    <span class="text-[9px] font-black uppercase tracking-widest {{ $asset->status === 'operational' || $asset->status === 'configuration' ? 'text-emerald-700 bg-emerald-100' : 'text-rose-700 bg-rose-100' }} px-2.5 py-1 rounded">
                        {{ $asset->status === 'configuration' ? 'تكوين' : ($asset->status === 'operational' ? 'يعمل' : $asset->status) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Hover Actions -->
        <div class="opacity-0 group-hover:opacity-100 transition-opacity flex items-center gap-1 pr-2">
            <button @click="$dispatch('open-asset-modal', { parent_id: {{ $asset->id }}, system_id: {{ $asset->refrigeration_system_id }} })" class="text-xs font-bold text-gray-500 hover:text-indigo-700 px-2 py-1 rounded hover:bg-indigo-50 transition" title="Add Child Asset">
                <i class="fa fa-plus"></i> <span class="hidden sm:inline ml-1">Add Component</span>
            </button>
            <div class="w-px h-4 bg-gray-200 mx-1"></div>
            <button @click="$dispatch('open-asset-modal', { 
                asset_id: {{ $asset->id }}, 
                name: '{{ addslashes($asset->name) }}', 
                type: '{{ $asset->type }}', 
                item_category_id: '{{ $asset->item_category_id }}',
                inventory_item_id: '{{ $asset->inventory_item_id }}',
                status: '{{ $asset->status }}', 
                install_date: '{{ $asset->install_date ? $asset->install_date->format('Y-m-d') : '' }}', 
                parent_id: {{ $asset->parent_id ?? 'null' }}, 
                system_id: {{ $asset->refrigeration_system_id }} 
            })" class="text-xs font-bold text-gray-400 hover:text-gray-900 px-2 py-1 rounded hover:bg-gray-100 transition" title="تعديل الخصائص">
                <i class="fa fa-edit"></i>
            </button>
            <form action="{{ route('assets.destroy', $asset) }}" method="POST" class="inline m-0 p-0" onsubmit="return confirm('WARNING: Are you sure you want to delete this asset AND all of its sub-components? This action cannot be undone.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-xs font-bold text-rose-400 hover:text-rose-700 px-2 py-1 rounded hover:bg-rose-50 transition" title="Delete Structure">
                    <i class="fa fa-trash"></i>
                </button>
            </form>
        </div>
    </div>

    <!-- Recursive Children Render Block -->
    @if($asset->children->count() > 0)
        <!-- Note: Need Alpine Collapse plugin for x-collapse, fallback to simple x-show if unavailable -->
        <div x-show="expanded" class="transition-all">
            <div class="pl-8 border-l-2 border-dashed border-indigo-100 ml-5 mb-2 relative">
                <!-- Visual Connection Line -->
                <div class="absolute w-4 h-px bg-indigo-100 top-6 -left-0.5"></div>
                @foreach($asset->children as $child)
                    @include('refrigeration-systems._asset_node', ['asset' => $child])
                @endforeach
            </div>
        </div>
    @endif
</div>
