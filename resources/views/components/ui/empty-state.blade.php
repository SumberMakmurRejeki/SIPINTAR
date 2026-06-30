@props([
    'title' => 'Belum ada data',
    'description' => null,
    'actionText' => null,
    'actionUrl' => null,
    'icon' => null,
])

<div class="p-12 text-center">
    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-gray-50 mb-4">
        @if($icon)
            {{ $icon }}
        @else
            <svg class="h-7 w-7 text-[#94a3b8]" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
            </svg>
        @endif
    </div>
    @if($title)
        <h3 class="text-[15px] font-semibold text-[#0f172a]">{{ $title }}</h3>
    @endif
    @if($description)
        <p class="mt-1 text-[13px] text-[#64748b]">{{ $description }}</p>
    @endif
    @if($actionText && $actionUrl)
        <div class="mt-5">
            <a href="{{ $actionUrl }}" class="inline-flex items-center gap-2 rounded-[10px] bg-blue-600 px-5 py-2.5 text-[14px] font-medium text-white transition-all hover:bg-blue-700">
                {{ $actionText }}
            </a>
        </div>
    @endif
</div>
