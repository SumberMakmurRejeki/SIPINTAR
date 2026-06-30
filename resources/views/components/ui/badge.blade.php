@props(['variant' => 'default', 'size' => 'sm'])

@php
    $variants = [
        'success' => 'bg-green-50 text-green-700 ring-1 ring-inset ring-green-600/20',
        'danger' => 'bg-red-50 text-red-700 ring-1 ring-inset ring-red-600/20',
        'warning' => 'bg-amber-50 text-amber-700 ring-1 ring-inset ring-amber-600/20',
        'info' => 'bg-blue-50 text-blue-700 ring-1 ring-inset ring-blue-600/20',
        'muted' => 'bg-gray-50 text-gray-600 ring-1 ring-inset ring-gray-500/10',
        'orange' => 'bg-orange-50 text-orange-700 ring-1 ring-inset ring-orange-600/20',
        'default' => 'bg-gray-50 text-gray-700 ring-1 ring-inset ring-gray-500/10',
    ];

    $sizes = [
        'sm' => 'text-[12px] px-2.5 py-1',
        'md' => 'text-[13px] px-3 py-1.5',
    ];

    $classes = trim("inline-flex items-center gap-1.5 font-medium rounded-full {$sizes[$size]} {$variants[$variant]}");
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</span>
