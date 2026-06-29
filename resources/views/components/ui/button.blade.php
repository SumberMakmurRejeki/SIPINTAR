@props([
    'variant' => 'primary',
    'size' => 'md',
    'type' => 'button',
    'href' => null,
    'loading' => false,
    'disabled' => false,
])

@php
    $base = 'inline-flex items-center justify-center font-medium transition-colors rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2';

    $sizes = [
        'sm' => 'text-xs px-3 py-1.5',
        'md' => 'text-sm px-4 py-2',
        'lg' => 'text-base px-6 py-2.5',
    ];

    $variants = [
        'primary' => 'bg-[#080808] text-white hover:bg-[#080808]/90 focus:ring-[#080808]',
        'secondary' => 'bg-white border border-[#D8D8D8] text-[#080808] hover:bg-gray-50 focus:ring-[#D8D8D8]',
        'danger' => 'bg-[#EE1D36] text-white hover:bg-[#EE1D36]/90 focus:ring-[#EE1D36]',
        'ghost' => 'text-[#080808] hover:bg-gray-100 focus:ring-[#D8D8D8]',
        'outline' => 'bg-transparent border border-[#D8D8D8] text-[#080808] hover:bg-gray-50 focus:ring-[#D8D8D8]',
    ];

    $classes = trim("$base {$sizes[$size]} {$variants[$variant]}");

    if ($disabled || $loading) {
        $classes .= ' opacity-60 cursor-not-allowed pointer-events-none';
    }
@endphp

@if($href && !$disabled)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes, 'disabled' => $disabled || $loading]) }}>
        @if($loading)
            <svg class="animate-spin -ml-1 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        @endif
        {{ $slot }}
    </button>
@endif
