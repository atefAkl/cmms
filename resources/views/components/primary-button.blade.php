@props([
    'type' => 'submit',
    'disabled' => false,
])

<button type="{{ $type }}" {{ $disabled ? 'disabled' : '' }} {{ $attributes->merge(['class' => 'inline-flex items-center px-5 py-2.5 bg-gray-800 border-2 border-gray-300 rounded-xl font-black text-xs text-white uppercase tracking-widest shadow-sm hover:bg-black hover:border-black focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
