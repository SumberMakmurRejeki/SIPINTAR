@props([
    'title' => null,
    'subtitle' => null,
    'actions' => null,
])

<div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div>
        @if($title)
            <h1 class="text-2xl font-semibold text-[#080808]">{{ $title }}</h1>
        @endif
        @if($subtitle)
            <p class="mt-1 text-sm text-[#5A5A5A]">{{ $subtitle }}</p>
        @endif
    </div>
    @if($actions)
        <div class="flex items-center gap-2">
            {{ $actions }}
        </div>
    @endif
</div>
