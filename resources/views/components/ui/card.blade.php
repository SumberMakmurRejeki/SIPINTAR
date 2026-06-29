@props(['padding' => 'p-6', 'bordered' => true, 'shadow' => false])
<div {{ $attributes->merge(['class' => ($bordered ? 'border border-[#D8D8D8] ' : '') . ($shadow ? 'shadow-sm ' : '') . 'rounded-lg bg-white ' . $padding]) }}>
    {{ $slot }}
</div>
