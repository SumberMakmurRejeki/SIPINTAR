@props([
    'name',
    'label' => null,
    'placeholder' => null,
    'helper' => null,
    'error' => null,
    'required' => false,
    'disabled' => false,
])

@php
    $hasError = $error || $errors->has($name);
    $base = 'block w-full rounded-md px-3 py-2 text-sm transition-colors focus:outline-none focus:ring-2 focus:ring-offset-1 disabled:opacity-60 disabled:cursor-not-allowed bg-white';
    $normal = 'border border-[#D8D8D8] focus:border-[#080808] focus:ring-[#080808]';
    $error = 'border border-[#EE1D36] focus:border-[#EE1D36] focus:ring-[#EE1D36]';
    $classes = $hasError ? "$base $error" : "$base $normal";
@endphp

<div>
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-[#080808] mb-1">
            {{ $label }}
            @if($required) <span class="text-[#EE1D36]" aria-hidden="true">*</span> @endif
        </label>
    @endif

    <select
        id="{{ $name }}"
        name="{{ $name }}"
        {{ $required ? 'required' : '' }}
        {{ $disabled ? 'disabled' : '' }}
        {{ $attributes->merge(['class' => $classes]) }}
    >
        @if($placeholder)
            <option value="">{{ $placeholder }}</option>
        @endif
        {{ $slot }}
    </select>

    @if($hasError)
        <p class="mt-1 text-xs text-[#EE1D36]">{{ $error ?? $errors->first($name) }}</p>
    @elseif($helper)
        <p class="mt-1 text-xs text-[#898989]">{{ $helper }}</p>
    @endif
</div>
