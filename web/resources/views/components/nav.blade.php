@props(['user', 'saved' => 0, 'cooked' => 0, 'total' => 20])

@php
    $links = [
        ['route' => 'recetas.index', 'match' => 'recetas.*', 'label' => 'Recetas', 'icon' => 'home'],
        ['route' => 'guardados.index', 'match' => 'guardados.*', 'label' => 'Guardados', 'icon' => 'bookmark'],
        ['route' => 'asistente.show', 'match' => 'asistente.*', 'label' => 'Asistente', 'icon' => 'chat'],
    ];
    $enPerfil = request()->routeIs('perfil.*');
@endphp

{{-- --- Barra superior --- --}}
<header class="fixed inset-x-0 top-0 z-40 border-b border-cream-300/70 bg-cream/85 backdrop-blur-md">
    <nav class="mx-auto flex h-[72px] max-w-[1280px] items-center gap-8 px-5 md:px-10">
        <x-logo />

        <ul class="ml-4 hidden items-center gap-1 md:flex">
            @foreach ($links as $link)
                @php $activo = request()->routeIs($link['match']); @endphp
                <li>
                    <a
                        href="{{ route($link['route']) }}"
                        @if ($activo) aria-current="page" @endif
                        class="relative flex items-center gap-2 rounded-full px-4 py-2 font-display text-[15px] font-bold transition {{ $activo ? 'bg-tomato-50 text-tomato-600' : 'text-charcoal-soft hover:bg-cream-200 hover:text-charcoal' }}"
                    >
                        {{ $link['label'] }}
                        @if ($link['icon'] === 'bookmark' && $saved > 0)
                            <span class="grid h-5 min-w-5 place-items-center rounded-full bg-tomato-500 px-1.5 text-[11px] font-bold text-white">{{ $saved }}</span>
                        @endif
                        @if ($link['icon'] === 'chat')
                            <span class="rounded-full bg-basil-100 px-2 py-0.5 text-[10px] font-extrabold uppercase tracking-wide text-basil-600">IA</span>
                        @endif
                    </a>
                </li>
            @endforeach
        </ul>

        <a
            href="{{ route('perfil.show') }}"
            class="ml-auto flex items-center gap-3 rounded-full p-1 pr-1 transition md:pr-4 {{ $enPerfil ? 'bg-tomato-50' : 'hover:bg-cream-200' }}"
            aria-label="Perfil"
        >
            <x-avatar />
            <span class="hidden text-left leading-tight md:block">
                <span class="block font-display text-sm font-bold">{{ $user->short_name }}</span>
                <span class="block text-xs text-charcoal-soft">{{ $cooked }} de {{ $total }} recetas</span>
            </span>
        </a>
    </nav>
</header>
{{-- --- Barra inferior (solo en el teléfono) --- --}}
<nav class="fixed inset-x-0 bottom-0 z-40 grid grid-cols-4 border-t border-cream-300 bg-white/95 pb-[env(safe-area-inset-bottom)] backdrop-blur md:hidden">
    @foreach ([...$links, ['route' => 'perfil.show', 'match' => 'perfil.*', 'label' => 'Perfil', 'icon' => 'user']] as $link)
        <a
            href="{{ route($link['route']) }}"
            class="flex flex-col items-center gap-1 py-2.5 text-[11px] font-semibold {{ request()->routeIs($link['match']) ? 'text-tomato-600' : 'text-charcoal-soft' }}"
        >
            <x-icon :name="$link['icon']" size="22" />
            {{ $link['label'] }}
        </a>
    @endforeach
</nav>
