@extends('layouts.app')

@section('content')
    <div class="mb-6 flex justify-between items-center px-4 sm:px-0">
        <div>
            <h1 class="text-2xl font-black text-gray-900">Refrigeration Systems</h1>
            <p class="text-gray-500 text-sm">Monitor and manage all specialized cooling equipment.</p>
        </div>
        <a href="{{ route('refrigeration-systems.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-bold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 transition shadow-lg shadow-indigo-600/20">
            Add New System
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($systems as $system)
            <div class="bg-white rounded-xl border border-gray-200 p-6 hover:shadow-xl transition-all duration-300 relative overflow-hidden group">
                <div class="absolute top-0 right-0 mt-4 mr-4">
                    <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest {{ $system->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                        {{ ucfirst($system->status) }}
                    </span>
                </div>

                <div class="flex items-center mb-6">
                    <div class="p-3 bg-indigo-50 rounded-xl text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-black text-gray-900 leading-tight truncate max-w-[150px]">{{ $system->name }}</h3>
                        <p class="text-xs text-gray-500 font-bold uppercase tracking-tighter">{{ $system->room->name ?? 'Unassigned' }}</p>
                    </div>
                </div>

                <div class="space-y-3 mb-6">
                    <div class="flex items-center text-xs font-bold text-gray-400 uppercase tracking-widest">
                        <svg class="w-4 h-4 mr-2 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Installed: <span class="text-gray-700 ml-1">{{ $system->installed_at ? \Illuminate\Support\Carbon::parse($system->installed_at)->format('M d, Y') : 'N/A' }}</span>
                    </div>
                </div>

                <div class="flex justify-between items-center pt-5 border-t border-gray-100">
                    <div class="flex gap-4">
                        <a href="{{ route('refrigeration-systems.show', $system) }}" class="text-xs font-black uppercase tracking-widest text-indigo-600 hover:text-indigo-800 transition">View Details</a>
                        <a href="{{ route('refrigeration-systems.edit', $system) }}" class="text-xs font-black uppercase tracking-widest text-gray-400 hover:text-gray-600 transition">Edit</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection

