@props(['x' => 50, 'y' => 55, 's' => 1])

{{-- La carita que comparten todos los stickers. --}}
<g transform="translate({{ $x }} {{ $y }}) scale({{ $s }})" stroke="none">
    <circle cx="-8" cy="-2" r="2.8" fill="#2E2B28" />
    <circle cx="8" cy="-2" r="2.8" fill="#2E2B28" />
    <circle cx="-7" cy="-3" r="0.9" fill="#fff" />
    <circle cx="9" cy="-3" r="0.9" fill="#fff" />
    <path d="M-4 4 q4 4 8 0" stroke="#2E2B28" stroke-width="2.4" fill="none" stroke-linecap="round" />
    <ellipse cx="-13" cy="4" rx="3.5" ry="2.2" fill="#FF8C8C" opacity="0.7" />
    <ellipse cx="13" cy="4" rx="3.5" ry="2.2" fill="#FF8C8C" opacity="0.7" />
</g>
