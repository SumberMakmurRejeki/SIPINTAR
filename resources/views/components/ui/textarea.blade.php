@props([
    'name',
    'label' => null,
    'placeholder' => null,
    'helper' => null,
    'error' => null,
    'required' => false,
    'disabled' => false,
    'rows' => 4,
])

@php
    $hasError = $error || $errors->has($name);
    $base = 'block w-full rounded-[10px] bg-white px-4 py-2.5 text-[14px] text-[#0f172a] placeholder:text-[#94a3b8] caret-[#0f172a] transition-colors focus:outline-none focus:ring-2 focus:ring-offset-1 disabled:opacity-60 disabled:cursor-not-allowed resize-none';
    $normal = 'border border-[#e2e8f0] focus:border-blue-500 focus:ring-blue-500/20';
    $error = 'border border-red-500 focus:border-red-500 focus:ring-red-500/20';
    $classes = $hasError ? "$base $error" : "$base $normal";
@endphp

<div>
    @if($label)
        <label for="{{ $name }}" class="block text-[13px] font-medium text-[#0f172a] mb-1.5">
            {{ $label }}
            @if($required) <span class="text-red-500" aria-hidden="true">*</span> @endif
        </label>
    @endif

    <textarea
        id="{{ $name }}"
        name="{{ $name }}"
        rows="{{ $rows }}"
        placeholder="{{ $placeholder }}"
        {{ $required ? 'required' : '' }}
        {{ $disabled ? 'disabled' : '' }}
        {{ $attributes->merge(['class' => $classes]) }}
    >{{ old($name, $slot) }}</textarea>

    @if($hasError)
        <p class="mt-1.5 text-[12px] text-red-500">{{ $error ?? $errors->first($name) }}</p>
    @elseif($helper)
        <p class="mt-1.5 text-[12px] text-[#94a3b8]">{{ $helper }}</p>
    @endif
</div>
