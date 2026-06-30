@props([
    'title' => null,
    'subtitle' => null,
    'actions' => null,
])

<div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div>
        @if($title)
            <h1 class="text-[28px] font-bold text-[#0f172a] tracking-tight">{{ $title }}</h1>
        @endif
        @if($subtitle)
            <p class="mt-1 text-[14px] text-[#64748b]">{{ $subtitle }}</p>
        @endif
    </div>
    @if($actions)
        <div class="flex items-center gap-2">
            {{ $actions }}
        </div>
    @endif
</div>
