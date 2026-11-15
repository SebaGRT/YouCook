@props(['name', 'size' => 20, 'filled' => false, 'stroke' => 2])

@php
    // Los mismos íconos del mockup (components/Icons.tsx), por nombre.
    $icons = [
        'search' => '<circle cx="11" cy="11" r="7" /><path d="m20 20-3.5-3.5" />',
        'bookmark' => '<path d="M6 4.5A1.5 1.5 0 0 1 7.5 3h9A1.5 1.5 0 0 1 18 4.5V21l-6-4-6 4z" />',
        'clock' => '<circle cx="12" cy="12" r="9" /><path d="M12 7v5l3 2" />',
        'flame' => '<path d="M12 3s5 4.5 5 10a5 5 0 0 1-10 0c0-2.5 1.5-4 1.5-4s.5 2 2 2c0-3 1.5-6 1.5-8z" />',
        'users' => '<circle cx="9" cy="8" r="3.5" /><path d="M2.5 20a6.5 6.5 0 0 1 13 0M16 4.5a3.5 3.5 0 0 1 0 7M21.5 20a6.5 6.5 0 0 0-4-6" />',
        'wallet' => '<rect x="3" y="6" width="18" height="14" rx="3" /><path d="M16 13h2M3 10h18M6 6l9-3 1.5 3" />',
        'check' => '<path d="m5 12.5 4.5 4.5L19 7.5" />',
        'sparkle' => '<path d="M12 3c.6 4.2 2.8 6.4 7 7-4.2.6-6.4 2.8-7 7-.6-4.2-2.8-6.4-7-7 4.2-.6 6.4-2.8 7-7zM19 16c.3 1.6 1 2.4 2.5 2.5-1.6.3-2.3 1-2.5 2.5-.3-1.6-1-2.3-2.5-2.5 1.6-.2 2.3-1 2.5-2.5z" />',
        'send' => '<path d="M4 12 20 4l-6 16-3-7z" /><path d="m11 13 9-9" />',
        'alert' => '<path d="M12 3.5 22 20H2z" /><path d="M12 10v4M12 17h.01" />',
        'info' => '<circle cx="12" cy="12" r="9" /><path d="M12 11v5M12 8h.01" />',
        'arrow-left' => '<path d="M19 12H5M11 6l-6 6 6 6" />',
        'arrow-right' => '<path d="M5 12h14M13 6l6 6-6 6" />',
        'x' => '<path d="M6 6l12 12M18 6 6 18" />',
        'refresh' => '<path d="M20 11a8 8 0 0 0-14.3-4.6L4 8M4 4v4h4M4 13a8 8 0 0 0 14.3 4.6L20 16M20 20v-4h-4" />',
        'lock' => '<rect x="5" y="11" width="14" height="10" rx="2.5" /><path d="M8 11V8a4 4 0 0 1 8 0v3" />',
        'home' => '<path d="M4 10.5 12 4l8 6.5V20a1 1 0 0 1-1 1h-4.5v-6h-5v6H5a1 1 0 0 1-1-1z" />',
        'chat' => '<path d="M20 12a8 8 0 0 1-11.8 7L4 20l1.2-3.8A8 8 0 1 1 20 12z" />',
        'user' => '<circle cx="12" cy="8" r="4" /><path d="M4 21a8 8 0 0 1 16 0" />',
        'swap' => '<path d="M7 4 3 8l4 4M3 8h14M17 20l4-4-4-4M21 16H7" />',
        'mail' => '<rect x="3" y="5" width="18" height="14" rx="3" /><path d="m4 7 8 6 8-6" />',
        'eye' => '<path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z" /><circle cx="12" cy="12" r="3" />',
        'pencil' => '<path d="M4 20h4L19 9l-4-4L4 16z" /><path d="m13.5 6.5 4 4" />',
        'logout' => '<path d="M15 4h3a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2h-3M10 16l-4-4 4-4M6 12h10" />',
        'chevron-down' => '<path d="m6 9 6 6 6-6" />',
    ];
@endphp

<svg {{ $attributes }} width="{{ $size }}" height="{{ $size }}" viewBox="0 0 24 24" fill="{{ $filled ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="{{ $stroke }}" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">{!! $icons[$name] !!}</svg>
