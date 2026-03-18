@props(['disabled' => false, 'accept' => null])

<div
    x-data="{ fileName: '' }"
    class="flex items-center border border-gray-300 rounded-md shadow-sm focus-within:border-indigo-500 focus-within:ring-1 focus-within:ring-indigo-500 overflow-hidden"
>
    <button
        type="button"
        @click="$el.closest('div').querySelector('input[type=file]').click()"
        class="shrink-0 px-3 py-2 text-xs font-semibold text-indigo-700 bg-indigo-50 border-r border-gray-300 cursor-pointer hover:bg-indigo-100 transition select-none"
    >
        Browse
    </button>
    <span
        x-text="fileName || 'No file chosen'"
        class="flex-1 px-3 py-2 text-sm text-gray-500 truncate"
    ></span>
    <input
        type="file"
        @disabled($disabled)
        {{ $attributes->except(['class']) }}
        @if($accept) accept="{{ $accept }}" @endif
        @change="fileName = $event.target.files[0]?.name || ''"
        class="sr-only"
    >
</div>
