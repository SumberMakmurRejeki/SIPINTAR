@props([
    'title' => null,
    'description' => null,
])

<div class="rounded-lg border border-[#D8D8D8] bg-white p-6">
    @if($title)
        <div class="mb-4 pb-4 border-b border-[#D8D8D8]">
            <h2 class="text-lg font-semibold text-[#080808]">{{ $title }}</h2>
            @if($description)
                <p class="mt-1 text-sm text-[#5A5A5A]">{{ $description }}</p>
            @endif
        </div>
    @endif
    <div class="{{ $title ? 'pt-1' : '' }}">
        {{ $slot }}
    </div>
</div>
