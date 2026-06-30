@props(['padding' => 'p-5', 'bordered' => true, 'shadow' => false, 'rounded' => true])
<div {{ $attributes->merge(['class' => ($bordered ? 'border border-[#e2e8f0] ' : '') . ($shadow ? 'shadow-sm ' : '') . ($rounded ? 'rounded-[16px] ' : 'rounded-lg ') . 'bg-white ' . $padding]) }}>
    {{ $slot }}
</div>
