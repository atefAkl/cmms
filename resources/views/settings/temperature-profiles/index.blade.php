<x-app-layout>
    <div class="pb-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex flex-col gap-2">
            
            <x-page-header title="Temperature Profiles" description="Manage baseline thermal configurations and environmental constraints." :backRoute="route('settings.index')">
                <x-primary-button href="{{ route('settings.temperature-profiles.create') }}">
                    <i class="fa fa-plus"></i>  
                </x-primary-button>
            </x-page-header>



            <!-- SCADA Data Grid container -->
            <div class="bg-slate-900 rounded-md shadow border border-slate-800 overflow-hidden flex flex-col">
                
                <!-- Toolbar -->
                <div class="p-4 bg-slate-800/50 border-b border-slate-800 flex justify-between items-center gap-4">
                    <form method="GET" action="{{ route('settings.temperature-profiles.index') }}" class="flex gap-4 w-full max-w-2xl">
                        <!-- Search -->
                        <div class="relative flex-1">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fa fa-search text-slate-500"></i>
                            </div>
                            <input type="text" name="search" value="{{ request('search') }}" class="block w-full pl-10 bg-slate-950 border-slate-700 text-slate-200 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-lg" placeholder="Search profiles...">
                        </div>
                        <!-- Filter -->
                        <div>
                            <select name="product_type" onchange="this.form.submit()" class="block w-full pl-3 pr-10 py-2 bg-slate-950 border-slate-700 text-slate-200 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-lg">
                                <option value="">All Types</option>
                                <option value="chilled" {{ request('product_type') === 'chilled' ? 'selected' : '' }}>Chilled</option>
                                <option value="frozen" {{ request('product_type') === 'frozen' ? 'selected' : '' }}>Frozen</option>
                                <option value="custom" {{ request('product_type') === 'custom' ? 'selected' : '' }}>Custom / Extreme</option>
                            </select>
                        </div>
                    </form>
                </div>

                <!-- Data Table -->
                <div class="overflow-x-auto w-full">
                    <table class="min-w-full divide-y divide-slate-800 w-full">
                        <thead class="bg-slate-950">
                            <tr>
                                <th scope="col" class="px-6 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest bg-slate-900 border-b border-slate-800">Profile Name</th>
                                <th scope="col" class="px-6 py-4 text-center text-[10px] font-black text-slate-400 uppercase tracking-widest bg-slate-900 border-b border-slate-800">Product Class</th>
                                <th scope="col" class="px-6 py-4 text-center text-[10px] font-black text-slate-400 uppercase tracking-widest bg-slate-800 border-b border-slate-800 shadow-inner">Target</th>
                                <th scope="col" class="px-6 py-4 text-center text-[10px] font-black text-slate-400 uppercase tracking-widest bg-slate-900 border-b border-slate-800">Range (Min/Max)</th>
                                <th scope="col" class="px-6 py-4 text-center text-[10px] font-black text-slate-400 uppercase tracking-widest bg-slate-900 border-b border-slate-800">Usage</th>
                                <th scope="col" class="relative px-6 py-4 bg-slate-900 border-b border-slate-800">
                                    <span class="sr-only">Actions</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/60 bg-slate-900">
                            @forelse($profiles as $profile)
                            <tr class="hover:bg-slate-800/40 transition">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-slate-100">{{ $profile->name }}</div>
                                    <div class="text-[10px] font-black uppercase text-slate-500 tracking-wider mt-1 border border-slate-700 inline-block px-1.5 py-0.5 rounded shadow-sm bg-slate-900">±{{ $profile->tolerance }}°C Tol.</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if($profile->product_type === 'frozen')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-blue-900/30 text-blue-400 border border-blue-500/20">
                                            <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></span> Frozen
                                        </span>
                                    @elseif($profile->product_type === 'chilled')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-emerald-900/30 text-emerald-400 border border-emerald-500/20">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Chilled
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-red-900/30 text-red-400 border border-red-500/20">
                                            <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Extreme
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center bg-slate-800/30 relative">
                                    <!-- LCD Style readout -->
                                    <div class="text-xl font-black font-mono tracking-tight" style="text-shadow: 0 0 10px rgba(0,255,255,0.2);" class="{{ $profile->target_temp < 0 ? 'text-cyan-400' : 'text-emerald-400' }}">
                                        {{ number_format($profile->target_temp, 1) }}<span class="text-sm text-slate-500 ml-0.5">°C</span>
                                    </div>
                                    <div class="absolute inset-y-0 left-0 w-px bg-slate-800"></div>
                                    <div class="absolute inset-y-0 right-0 w-px bg-slate-800"></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="flex items-center justify-center gap-3">
                                        <div class="text-right">
                                            <div class="text-[9px] font-black text-slate-500 uppercase tracking-widest">Min</div>
                                            <div class="text-sm font-mono font-bold text-blue-300">{{ number_format($profile->min_temp, 1) }}</div>
                                        </div>
                                        <div class="h-4 w-px bg-slate-700"></div>
                                        <div class="text-left">
                                            <div class="text-[9px] font-black text-slate-500 uppercase tracking-widest">Max</div>
                                            <div class="text-sm font-mono font-bold text-red-300">{{ number_format($profile->max_temp, 1) }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="text-sm font-black text-slate-300">{{ $profile->assignments_count }} <span class="text-xs text-slate-500 font-bold uppercase ml-1">Rooms</span></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end gap-3">
                                        <a href="{{ route('settings.temperature-profiles.edit', $profile) }}" class="text-indigo-400 hover:text-indigo-300 font-bold uppercase tracking-wider text-[10px]">Edit</a>
                                        
                                        @if($profile->assignments_count === 0)
                                            <form action="{{ route('settings.temperature-profiles.destroy', $profile) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this profile?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-400 hover:text-red-300 font-bold uppercase tracking-wider text-[10px]">Delete</button>
                                            </form>
                                        @else
                                            <span class="text-slate-600 font-bold uppercase tracking-wider text-[10px] cursor-not-allowed" title="In Use">Delete</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <svg class="mx-auto h-10 w-10 text-slate-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                    <p class="text-sm font-black uppercase tracking-widest text-slate-500">No Temperature Profiles Found</p>
                                    <p class="text-xs text-slate-600 mt-1">Get started by creating a new thermal baseline.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                @if($profiles->hasPages())
                <div class="px-6 py-4 bg-slate-950 border-t border-slate-800">
                    {{ $profiles->withQueryString()->links() }}
                </div>
                @endif
                
            </div>
        </div>
    </div>
</x-app-layout>
