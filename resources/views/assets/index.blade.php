<x-app-layout>
    <x-page-header 
        title="Asset Hierarchy" 
        description="Manage refrigeration systems and components in a hierarchical tree."
        :actionUrl="route('assets.create')"
        actionLabel="Add New Asset"
    />

    <div class="space-y-6">
    @foreach($rooms as $room)
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-200" x-data="{ open: true }">
            <div class="p-4 bg-gray-50 border-b border-gray-100 flex justify-between items-center cursor-pointer" @click="open = !open">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-gray-400 transition-transform" :class="open ? 'rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    <span class="text-xs font-black uppercase tracking-widest text-gray-400">Room:</span>
                    <h2 class="text-lg font-black text-gray-900">{{ $room->name }}</h2>
                </div>
                <span class="text-[10px] font-bold text-gray-400 uppercase italic">{{ $room->location }}</span>
            </div>
            
            <div x-show="open" x-collapse class="p-4 space-y-4">
                @forelse($room->refrigerationSystems as $system)
                    <div class="pl-4 border-l-2 border-indigo-100 space-y-3" x-data="{ openSystem: true }">
                        <div class="flex items-center justify-between group">
                            <div class="flex items-center gap-2 cursor-pointer" @click="openSystem = !openSystem">
                                <svg class="w-4 h-4 text-indigo-400 transition-transform" :class="openSystem ? 'rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                <span class="text-[10px] font-black uppercase tracking-widest text-indigo-400">System:</span>
                                <h3 class="font-bold text-gray-800 underline decoration-indigo-200 decoration-2 underline-offset-4 group-hover:decoration-indigo-500 transition">{{ $system->name }}</h3>
                            </div>
                            <div class="opacity-0 group-hover:opacity-100 transition">
                                <a href="{{ route('assets.create', ['system_id' => $system->id]) }}" class="text-[10px] font-black uppercase tracking-tighter text-indigo-600 hover:text-indigo-800">+ Add Asset</a>
                            </div>
                        </div>

                        <div x-show="openSystem" x-collapse class="pl-6 space-y-2">
                            @php
                                $topLevelAssets = $system->assets->whereNull('parent_id');
                            @endphp

                            @forelse($topLevelAssets as $asset)
                                <x-asset-node :asset="$asset" :allAssets="$system->assets" />
                            @empty
                                <p class="text-xs text-gray-400 italic">No assets assigned to this system.</p>
                            @endforelse
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-500 italic py-4">No refrigeration systems in this room.</p>
                @endforelse
            </div>
        </div>
    @endforeach
</div>
</x-app-layout>
