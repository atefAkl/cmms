@props([
    'type' => 'submit',
    'disabled' => false,
    'href' => null,
])

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => 'inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-black text-xs text-white uppercase tracking-widest hover:bg-indigo-500 focus:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-white disabled:opacity-50 transition ease-in-out duration-150 shadow-[0_4px_14px_0_rgba(79,70,229,0.39)]']) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $disabled ? 'disabled' : '' }} {{ $attributes->merge(['class' => 'inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-black text-xs text-white uppercase tracking-widest hover:bg-indigo-500 focus:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-white disabled:opacity-50 transition ease-in-out duration-150 shadow-[0_4px_14px_0_rgba(79,70,229,0.39)]']) }}>
        {{ $slot }}
    </button>
@endif
