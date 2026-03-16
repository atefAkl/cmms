@props([
    'variant' => 'primary',
    'size' => 'md',
    'href' => null,
])
@php
    $baseClasses = 'inline-flex items-center justify-center font-black uppercase tracking-widest transition ease-in-out duration-150 focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-25';

    $variants = [
        'primary' => 'bg-indigo-600 border border-transparent text-white hover:bg-[#4d47c4] focus:ring-indigo-500 shadow-sm rounded-lg',
        'secondary' => 'bg-white border border-gray-200 text-gray-400 hover:text-gray-600 hover:bg-gray-50 focus:ring-indigo-500 rounded-lg',
        'danger' => 'bg-red-600 border border-transparent text-white hover:bg-red-700 focus:ring-red-500 shadow-sm rounded-lg',
        'gray' => 'bg-gray-800 border-2 border-gray-300 text-white hover:bg-black hover:border-black rounded-xl', // Matches original primary style
        'outline' => 'bg-gray-100 border-2 border-gray-300 text-black hover:bg-white hover:border-gray-400 rounded-xl', // Matches original secondary style
        'link' => 'bg-transparent border border-transparent p-0 text-[#5D57E0] hover:underline focus:ring-0 shadow-none normal-case tracking-normal font-bold',
        'link-danger' => 'bg-transparent border border-transparent p-0 text-[#E05757] hover:underline focus:ring-0 shadow-none normal-case tracking-normal font-bold',
    ];

    $sizes = [
        'xs' => 'px-3 py-1.5 text-[10px]',
        'sm' => 'px-4 py-2 text-xs',
        'md' => 'px-6 py-3 text-xs',
        'lg' => 'px-8 py-4 text-sm',
        'none' => '',
    ];

    $classes = $baseClasses . ' ' . ($variants[$variant] ?? $variants['primary']) . ' ' . ($sizes[$size] ?? $sizes['md']);
@endphp
@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button {{ $attributes->merge(['type' => 'submit', 'class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif
