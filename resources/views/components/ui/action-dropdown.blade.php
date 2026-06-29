@props(['items' => []])

<div class="relative" x-data="{ open: false }">
    <button
        type="button"
        @click="open = !open"
        class="rounded-md p-1.5 text-[#5A5A5A] hover:bg-gray-100 hover:text-[#080808] transition-colors"
    >
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 12.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 18.75a.75.75 0 110-1.5.75.75 0 010 1.5z" />
        </svg>
    </button>

    <div
        x-show="open"
        @click.outside="open = false"
        x-transition
        class="absolute right-0 z-10 mt-1 w-40 rounded-md border border-[#D8D8D8] bg-white py-1 shadow-lg"
    >
        @foreach($items as $item)
            @if(isset($item['divider']))
                <div class="my-1 border-t border-[#D8D8D8]"></div>
            @else
                <a
                    href="{{ $item['url'] ?? '#' }}"
                    @if(isset($item['danger'])) class="block px-4 py-2 text-sm text-[#EE1D36] hover:bg-red-50"
                    @else class="block px-4 py-2 text-sm text-[#080808] hover:bg-gray-50"
                    @endif
                >
                    {{ $item['label'] }}
                </a>
            @endif
        @endforeach
    </div>
</div>
