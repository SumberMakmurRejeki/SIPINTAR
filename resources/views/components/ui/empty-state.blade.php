@props([
    'title' => 'Belum ada data',
    'description' => null,
    'actionText' => null,
    'actionUrl' => null,
    'icon' => null,
])

<div class="rounded-lg border border-[#D8D8D8] bg-white p-12 text-center">
    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-gray-50 mb-4">
        @if($icon)
            {{ $icon }}
        @else
            <svg class="h-6 w-6 text-[#898989]" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
            </svg>
        @endif
    </div>
    @if($title)
        <h3 class="text-sm font-semibold text-[#080808]">{{ $title }}</h3>
    @endif
    @if($description)
        <p class="mt-1 text-sm text-[#5A5A5A]">{{ $description }}</p>
    @endif
    @if($actionText && $actionUrl)
        <div class="mt-6">
            <a href="{{ $actionUrl }}" class="inline-flex items-center rounded-md bg-[#080808] px-4 py-2 text-sm font-medium text-white hover:bg-[#080808]/90 transition-colors">
                {{ $actionText }}
            </a>
        </div>
    @endif
</div>
