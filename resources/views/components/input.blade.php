@props([
    'disabled' => false,
    'type' => 'text',
    'value' => '',
    'required' => false,
    'placeholder' => '',
])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm']) }} type="{{ $type }}" value="{{ $value }}" required="{{ $required }}" placeholder="{{ $placeholder }}">
