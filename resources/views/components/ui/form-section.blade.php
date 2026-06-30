@props([
    'title' => null,
    'description' => null,
])

<div class="rounded-[16px] border border-[#e2e8f0] bg-white p-6">
    @if($title)
        <div class="mb-4 pb-4 border-b border-[#e2e8f0]">
            <h2 class="text-[16px] font-semibold text-[#0f172a]">{{ $title }}</h2>
            @if($description)
                <p class="mt-1 text-[13px] text-[#64748b]">{{ $description }}</p>
            @endif
        </div>
    @endif
    <div class="{{ $title ? 'pt-1' : '' }}">
        {{ $slot }}
    </div>
</div>
