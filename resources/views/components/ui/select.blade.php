@props([
    'name' => null,
    'label' => null,
    'placeholder' => null,
    'helper' => null,
    'error' => null,
    'required' => false,
    'disabled' => false,
])

@php
    $hasError = filled($name) && ($error || $errors->has($name));
    $base = 'block w-full rounded-[10px] bg-white px-4 py-2.5 text-[14px] text-[#0f172a] transition-colors focus:outline-none focus:ring-2 focus:ring-offset-1 disabled:opacity-60 disabled:cursor-not-allowed appearance-none';
    $normal = 'border border-[#e2e8f0] focus:border-blue-500 focus:ring-blue-500/20';
    $errorClasses = 'border border-red-500 focus:border-red-500 focus:ring-red-500/20';
    $classes = $hasError ? "$base $errorClasses" : "$base $normal";
@endphp

<div>
    @if($label)
        <label @if(filled($name)) for="{{ $name }}" @endif class="block text-[13px] font-medium text-[#0f172a] mb-1.5">
            {{ $label }}
            @if($required) <span class="text-red-500" aria-hidden="true">*</span> @endif
        </label>
    @endif

    <div class="relative">
        <select
            @if(filled($name)) id="{{ $name }}" name="{{ $name }}" @endif
            {{ $required ? 'required' : '' }}
            {{ $disabled ? 'disabled' : '' }}
            {{ $attributes->merge(['class' => $classes]) }}
        >
            @if($placeholder)
                <option value="">{{ $placeholder }}</option>
            @endif
            {{ $slot }}
        </select>
        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
            <svg class="h-4 w-4 text-[#94a3b8]" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" /></svg>
        </div>
    </div>

    @if($hasError)
        <p class="mt-1.5 text-[12px] text-red-500">{{ $error ?? $errors->first($name) }}</p>
    @elseif($helper)
        <p class="mt-1.5 text-[12px] text-[#94a3b8]">{{ $helper }}</p>
    @endif
</div>
