@props([
    'id' => 'modal',
    'title' => 'Konfirmasi',
    'description' => '',
    'variant' => 'default',
    'confirmText' => 'Konfirmasi',
    'cancelText' => 'Batal',
    'icon' => null,
])

@php
    $confirmClass = match($variant) {
        'danger' => 'bg-red-600 text-white hover:bg-red-700',
        default => 'bg-blue-600 text-white hover:bg-blue-700',
    };
@endphp

<div
    x-data="{ open: false }"
    @keydown.escape.window="open = false"
    @{{ $id }}-open.window="open = true"
    @{{ $id }}-close.window="open = false"
>
    <div
        x-show="open"
        x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center p-4"
        role="dialog"
        aria-modal="true"
    >
        {{-- Overlay --}}
        <div
            x-show="open"
            x-transition:enter="transition-opacity ease-linear duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-linear duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click="open = false"
            class="fixed inset-0 bg-black/50"
        ></div>

        {{-- Modal panel --}}
        <div
            x-show="open"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="relative w-full max-w-md rounded-[14px] border border-[#e2e8f0] bg-white p-6 shadow-xl"
        >
            {{-- Header --}}
            <div class="flex items-start gap-4">
                @if($icon)
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full {{ $variant === 'danger' ? 'bg-red-50' : 'bg-blue-50' }}">
                        {{ $icon }}
                    </div>
                @endif
                <div class="flex-1">
                    <h3 class="text-[15px] font-semibold text-[#0f172a]">{{ $title }}</h3>
                    @if($description)
                        <p class="mt-1 text-[13px] text-[#64748b]">{{ $description }}</p>
                    @endif
                </div>
                <button type="button" @click="open = false" class="text-[#94a3b8] hover:text-[#0f172a] transition-colors">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            {{-- Content slot --}}
            @if($slot->isNotEmpty())
                <div class="mt-4">
                    {{ $slot }}
                </div>
            @endif

            {{-- Footer --}}
            <div class="mt-6 flex items-center justify-end gap-3">
                <button
                    type="button"
                    @click="open = false"
                    {{ $attributes->merge(['@cancel']) }}
                    class="rounded-[10px] border border-[#e2e8f0] bg-white px-4 py-2 text-[13px] font-medium text-[#0f172a] hover:bg-gray-50 transition-colors"
                >
                    {{ $cancelText }}
                </button>
                <button
                    type="button"
                    {{ $attributes->merge(['@confirm', 'class' => "rounded-[10px] px-4 py-2 text-[13px] font-medium transition-colors $confirmClass"]) }}
                >
                    {{ $confirmText }}
                </button>
            </div>
        </div>
    </div>
</div>
