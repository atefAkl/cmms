@props(['asset', 'allAssets'])

<div x-data="{ openNode: false }" class="relative">
    <div class="flex items-center justify-between group py-1.5 hover:bg-indigo-50/50 rounded-lg px-2 transition">
        <div class="flex items-center gap-2">
            @php
                $children = $allAssets->where('parent_id', $asset->id);
                $hasChildren = $children->isNotEmpty();
            @endphp
            
            @if($hasChildren)
                <button @click="openNode = !openNode" class="focus:outline-none">
                    <svg class="w-3 h-3 text-gray-400 transition-transform" :class="openNode ? 'rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </button>
            @else
                <div class="w-3"></div>
            @endif

            <span class="px-1.5 py-0.5 rounded text-[9px] font-black uppercase tracking-tighter 
                {{ $asset->type == 'compressor' ? 'bg-orange-100 text-orange-700' : 
                   ($asset->type == 'evaporator' ? 'bg-blue-100 text-blue-700' : 
                   ($asset->type == 'motor' ? 'bg-purple-100 text-purple-700' : 'bg-gray-100 text-gray-700')) }}">
                {{ $asset->type }}
            </span>
            
            <a href="{{ route('assets.show', $asset) }}" class="text-sm font-bold text-gray-700 hover:text-indigo-600 transition">{{ $asset->name }}</a>
            
            @if($asset->status != 'active')
                <span class="w-1.5 h-1.5 rounded-full bg-red-400 animate-pulse" title="{{ $asset->status }}"></span>
            @endif
        </div>

        <div class="opacity-0 group-hover:opacity-100 flex items-center gap-3 transition">
            <a href="{{ route('assets.create', ['system_id' => $asset->refrigeration_system_id, 'parent_id' => $asset->id]) }}" class="text-[9px] font-black uppercase tracking-tighter text-indigo-600 hover:text-indigo-800" title="Add Child">+ Add Child</a>
            <a href="{{ route('assets.edit', $asset) }}" class="text-[9px] font-black uppercase tracking-tighter text-gray-400 hover:text-gray-600">Edit</a>
        </div>
    </div>

    @if($hasChildren)
        <div x-show="openNode" x-collapse class="pl-6 border-l border-gray-100 ml-1.5 mt-1 space-y-1">
            @foreach($children as $child)
                <x-asset-node :asset="$child" :allAssets="$allAssets" />
            @endforeach
        </div>
    @endif
</div>
