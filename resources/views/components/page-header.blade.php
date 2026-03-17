@props([
    'title',
    'description' => null,
    'backRoute' => route('dashboard'),
    'actionUrl' => null,
    'actionLabel' => null,
    'actionIcon' => 'fa fa-plus'
])

<div class="flex items-center justify-between mb-6">
    <div class="flex items-center gap-4">
        @if($backRoute)
            <a href="{{ $backRoute }}"
                class="p-2 bg-white border border-gray-200 rounded-lg text-gray-400 hover:text-gray-600 transition shadow-sm">
                <i class="fa fa-angle-left px-2"></i>
            </a>
        @endif
        <div>
            <h1 class="text-2xl font-black text-gray-900 leading-tight">{{ $title }}</h1>
            @if($description)
                <p class="text-gray-500 text-sm">{{ $description }}</p>
            @endif
        </div>
    </div>

    @if($actionUrl)
        <a href="{{ $actionUrl }}"
            class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-bold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-lg shadow-indigo-600/20">
            @if($actionIcon)
                <i class="{{ $actionIcon }} me-2"></i>
            @endif
            {{ $actionLabel ?? __('New') }}
        </a>
    @elseif(isset($slot) && $slot->isNotEmpty())
        {{ $slot }}
    @endif
</div>
