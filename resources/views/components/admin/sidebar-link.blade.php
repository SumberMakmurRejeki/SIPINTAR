@props(['active' => false, 'href' => '#'])
<a href="{{ $href }}" {{ $attributes->merge(['class' => 'flex items-center gap-2 rounded-md px-2 py-1.5 text-sm font-medium transition-colors ' . ($active ? 'bg-gray-100 text-[#080808]' : 'text-[#5A5A5A] hover:bg-gray-50 hover:text-[#080808]')]) }}>
    {{ $slot }}
</a>
