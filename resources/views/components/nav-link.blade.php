@props(['active'])

@php
$classes = ($active ?? false)
            ? 'hover:text-[#f53003] dark:hover:text-[#FF4433] transition-colors text-[#f53003] dark:text-[#FF4433] font-semibold'
            : 'hover:text-[#f53003] dark:hover:text-[#FF4433] transition-colors text-gray-600 dark:text-gray-400';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
