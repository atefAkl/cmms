@extends('layouts.app')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-black text-gray-900">{{ $asset->name }}</h1>
            <p class="text-gray-500 text-sm">Asset Details & Hierarchy</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('assets.edit', $asset) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg font-bold text-xs uppercase transition hover:bg-indigo-700">
                Edit Asset
            </a>
            <a href="{{ route('assets.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-200 text-gray-700 rounded-lg font-bold text-xs uppercase transition hover:bg-gray-50">
                Back to Hierarchy
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-8">
            <!-- Asset Info -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
                <div class="flex items-center justify-between mb-8 border-b border-gray-50 pb-6">
                    <div>
                        <span class="text-[10px] font-black uppercase tracking-widest text-indigo-400">Asset Type</span>
                        <h3 class="text-xl font-bold text-gray-900">{{ ucfirst($asset->type) }}</h3>
                    </div>
                    <span class="px-4 py-1.5 rounded-full text-sm font-bold {{ $asset->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                        {{ strtoupper($asset->status) }}
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-4">
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest">Technical Specifications</h4>
                        <div class="space-y-3">
                            <div>
                                <p class="text-[10px] text-gray-400 uppercase font-black">Manufacturer</p>
                                <p class="text-sm font-bold text-gray-900">{{ $asset->manufacturer ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] text-gray-400 uppercase font-black">Model Number</p>
                                <p class="text-sm font-bold text-gray-900">{{ $asset->model ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] text-gray-400 uppercase font-black">Serial Number</p>
                                <p class="text-sm font-bold text-gray-900 text-indigo-600 font-mono">{{ $asset->serial_number ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="space-y-4">
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest">Operational Data</h4>
                        <div class="space-y-3">
                            <div>
                                <p class="text-[10px] text-gray-400 uppercase font-black">Installation Date</p>
                                <p class="text-sm font-bold text-gray-900">{{ $asset->install_date ? \Illuminate\Support\Carbon::parse($asset->install_date)->format('M d, Y') : 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] text-gray-400 uppercase font-black">Associated System</p>
                                <p class="text-sm font-bold text-gray-900">{{ $asset->refrigerationSystem->name }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] text-gray-400 uppercase font-black">Location (Room)</p>
                                <p class="text-sm font-bold text-gray-900">{{ $asset->refrigerationSystem->room->name }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                @if($asset->notes)
                    <div class="mt-8 pt-8 border-t border-gray-50">
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Notes</h4>
                        <p class="text-sm text-gray-600 italic">{{ $asset->notes }}</p>
                    </div>
                @endif
            </div>

            <!-- Maintenance History -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-6 border-b border-gray-50 flex items-center justify-between">
                    <h3 class="font-bold text-gray-900">Maintenance History</h3>
                    <a href="{{ route('maintenance.create', ['asset_id' => $asset->id]) }}" class="text-xs font-black uppercase text-indigo-600 hover:text-indigo-800">Add Entry</a>
                </div>
                <div class="p-0">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50 text-[10px] uppercase font-black tracking-widest text-gray-400">
                            <tr>
                                <th class="px-6 py-4">Date</th>
                                <th class="px-6 py-4">Description</th>
                                <th class="px-6 py-4">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($asset->maintenanceTasks as $task)
                                <tr class="text-sm">
                                    <td class="px-6 py-4 text-gray-500">{{ $task->created_at->format('M d, Y') }}</td>
                                    <td class="px-6 py-4 font-bold text-gray-900">{{ $task->issue_description }}</td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $task->status === 'completed' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                            {{ $task->status }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-12 text-center text-gray-400 italic">No maintenance records found for this asset.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Sidebar: Hierarchy Context -->
        <div class="space-y-8">
            <div class="bg-indigo-900 rounded-2xl p-6 text-white shadow-xl shadow-indigo-900/20">
                <h4 class="text-[10px] font-black uppercase tracking-widest text-indigo-300 mb-4">Parent Asset</h4>
                @if($asset->parent)
                    <a href="{{ route('assets.show', $asset->parent) }}" class="group">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-indigo-800 rounded-lg group-hover:bg-indigo-700 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 15l7-7 7 7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                            </div>
                            <div>
                                <p class="text-sm font-bold group-hover:text-indigo-200 transition">{{ $asset->parent->name }}</p>
                                <p class="text-[10px] uppercase text-indigo-400">{{ $asset->parent->type }}</p>
                            </div>
                        </div>
                    </a>
                @else
                    <p class="text-sm text-indigo-400 italic">This is a top-level asset.</p>
                @endif
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <h4 class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-4">Component Breakdown (Children)</h4>
                <div class="space-y-4">
                    @forelse($asset->children as $child)
                        <div class="flex items-center justify-between group">
                            <div class="flex items-center gap-3">
                                <div class="w-2 h-2 rounded-full bg-indigo-400"></div>
                                <a href="{{ route('assets.show', $child) }}" class="text-sm font-bold text-gray-700 hover:text-indigo-600 transition">{{ $child->name }}</a>
                            </div>
                            <span class="text-[10px] uppercase font-black text-gray-300">{{ $child->type }}</span>
                        </div>
                    @empty
                        <p class="text-xs text-gray-400 italic text-center py-4">No child components attached.</p>
                    @endforelse
                    
                    <a href="{{ route('assets.create', ['parent_id' => $asset->id, 'system_id' => $asset->refrigeration_system_id]) }}" class="mt-4 flex items-center justify-center gap-2 p-3 bg-gray-50 rounded-xl text-xs font-bold text-indigo-600 hover:bg-indigo-50 transition border border-dashed border-indigo-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                        Add Sub-component
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
