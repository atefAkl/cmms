@extends('layouts.app')

@section('content')
    <div class="mb-4">
        <h1 class="text-2xl font-black text-gray-900">Maintenance Dashboard</h1>
        <p class="text-gray-500 text-sm">Welcome back, {{ Auth::user()->name }}</p>
    </div>

    <div class="space-y-6">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-200">
            <div class="p-8 text-gray-900">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center text-green-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <h2 class="font-bold text-lg">System Status: Online</h2>
                        <p class="text-gray-500 text-sm">All refrigeration systems are operating within normal parameters.</p>
                    </div>
                </div>
                {{ __("You're logged in!") }}
            </div>
        </div>
    </div>
@endsection

