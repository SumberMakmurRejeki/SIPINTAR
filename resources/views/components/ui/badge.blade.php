@props(['variant' => 'default', 'size' => 'sm'])

@php
    $variants = [
        'success' => 'bg-green-50 text-green-700 ring-1 ring-inset ring-green-600/20',
        'danger' => 'bg-red-50 text-red-700 ring-1 ring-inset ring-red-600/20',
        'warning' => 'bg-yellow-50 text-yellow-800 ring-1 ring-inset ring-yellow-600/20',
        'info' => 'bg-blue-50 text-blue-700 ring-1 ring-inset ring-blue-600/20',
        'muted' => 'bg-gray-50 text-gray-600 ring-1 ring-inset ring-gray-500/10',
        'orange' => 'bg-orange-50 text-orange-700 ring-1 ring-inset ring-orange-600/20',
        'default' => 'bg-gray-50 text-gray-700 ring-1 ring-inset ring-gray-500/10',
    ];

    $sizes = [
        'sm' => 'text-xs px-2 py-0.5',
        'md' => 'text-sm px-2.5 py-1',
    ];

    $classes = trim("inline-flex items-center font-medium rounded-full {$sizes[$size]} {$variants[$variant]}");
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</span>
