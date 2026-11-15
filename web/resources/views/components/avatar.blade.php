@props(['size' => 40])

{{-- Círculo con la inicial de quien inició sesión. --}}
<span
    {{ $attributes->class('grid shrink-0 place-items-center rounded-full bg-gradient-to-br from-[#FFC23D] to-tomato-500 font-display font-black text-white ring-2 ring-white') }}
    style="width: {{ $size }}px; height: {{ $size }}px; font-size: {{ $size * 0.4 }}px"
>
    {{ auth()->user()->initial }}
</span>
