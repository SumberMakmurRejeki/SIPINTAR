@props(['items' => []])

@if(count($items) > 0)
    <nav class="flex items-center gap-1 text-sm text-[#5A5A5A]">
        @foreach($items as $label => $url)
            @if(!$loop->last)
                <a href="{{ is_string($url) ? $url : '#' }}" class="hover:text-[#080808] transition-colors">{{ $label }}</a>
                <svg class="h-3 w-3 text-[#898989]" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                </svg>
            @else
                <span class="text-[#080808] font-medium">{{ $label }}</span>
            @endif
        @endforeach
    </nav>
@endif
