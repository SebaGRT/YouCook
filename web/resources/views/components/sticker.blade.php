@props(['kind', 'size' => 88, 'locked' => false, 'rotate' => 0])

@php
    // Cada dibujo es un archivo en components/stickers/. Para agregar un
    // sticker nuevo basta con crear su archivo: no hay que tocar este.
    $exists = view()->exists('components.stickers.'.$kind);
    $filter = 'cut-'.Str::random(8);
@endphp

@if ($locked || ! $exists)
    {{-- Bloqueado: círculo punteado con un signo de pregunta. --}}
    <svg width="{{ $size }}" height="{{ $size }}" viewBox="0 0 100 100" aria-hidden="true">
        <circle cx="50" cy="50" r="38" fill="#F3E7D4" stroke="#D9C5A6" stroke-width="3" stroke-dasharray="6 6" />
        <text x="50" y="62" text-anchor="middle" font-family="Nunito" font-weight="900" font-size="34" fill="#C9B394">?</text>
    </svg>
@else
    {{-- El filtro dibuja el borde blanco de «sticker recortado». --}}
    <svg width="{{ $size }}" height="{{ $size }}" viewBox="0 0 100 100" style="transform: rotate({{ $rotate }}deg); filter: drop-shadow(0 4px 6px rgb(46 43 40 / .18))" aria-hidden="true">
        <defs>
            <filter id="{{ $filter }}" x="-20%" y="-20%" width="140%" height="140%">
                <feMorphology in="SourceAlpha" operator="dilate" radius="5" result="d" />
                <feFlood flood-color="#fff" />
                <feComposite in2="d" operator="in" result="o" />
                <feMerge><feMergeNode in="o" /><feMergeNode in="SourceGraphic" /></feMerge>
            </filter>
        </defs>
        <g filter="url(#{{ $filter }})">
            <x-dynamic-component :component="'stickers.'.$kind" />
        </g>
    </svg>
@endif
