@extends('layouts.app')

@section('titulo', 'Recetas')

@section('contenido')
    @php
        $categoria = $filters['categoria'] ?? null;
        $hayFiltros = collect($filters)->filter()->isNotEmpty();
    @endphp

    {{-- --- Buscador --- --}}
    <section class="relative overflow-hidden rounded-[32px] bg-tomato-500 px-6 py-10 text-white md:px-12 md:py-14">
        <div class="pointer-events-none absolute -top-24 -right-20 size-80 rounded-full bg-[#FF7A5C]"></div>
        <div class="pointer-events-none absolute -right-6 -bottom-28 size-64 rounded-full bg-tomato-600"></div>
        <div class="pointer-events-none absolute top-8 right-16 hidden gap-3 lg:flex">
            <x-sticker kind="tomate" size="92" rotate="-10" />
            <x-sticker kind="choclo" size="92" rotate="8" />
        </div>
        <div class="pointer-events-none absolute right-40 bottom-6 hidden lg:block"><x-sticker kind="panqueque" size="84" rotate="-6" /></div>

        <div class="relative max-w-[640px]">
            <p class="font-display text-sm font-extrabold tracking-wider text-white/80 uppercase">Hola, {{ auth()->user()->first_name }}</p>
            <h1 class="mt-2 text-[34px] leading-[1.05] font-black md:text-[48px]">¿Qué cocinamos hoy?</h1>

            {{-- Un solo formulario GET para todos los filtros. Los selectores de más
                 abajo no están dentro de él: se le unen con form="filtros". --}}
            <form id="filtros" role="search" method="GET" action="{{ route('recetas.index') }}" class="mt-7">
                @if ($categoria)
                    <input type="hidden" name="categoria" value="{{ $categoria }}">
                @endif
                <label class="flex h-15 items-center gap-3 rounded-2xl bg-white pr-2 pl-5 text-charcoal shadow-lift">
                    <x-icon name="search" size="22" class="text-charcoal-soft" />
                    <input
                        name="q"
                        value="{{ $filters['q'] ?? '' }}"
                        placeholder="Busca por plato o ingrediente: huevo, arroz, zapallo…"
                        class="h-14 w-full bg-transparent text-[16px] outline-none placeholder:text-[#B3A99D]"
                    >
                    <button class="hidden h-11 shrink-0 rounded-xl bg-charcoal px-5 font-display font-bold text-white sm:block">Buscar</button>
                </label>
            </form>

            <p class="mt-4 text-sm text-white/85">
                Populares:
                @foreach (['huevo', 'arroz', 'zapallo'] as $popular)
                    <a href="{{ route('recetas.index', ['q' => $popular]) }}" class="font-semibold underline-offset-2 hover:underline">{{ $popular }}</a>{{ $loop->last ? '' : ' ·' }}
                @endforeach
            </p>
        </div>
    </section>
    {{-- --- Filtros --- --}}
    <section class="mt-10 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <div class="-mx-5 flex gap-2 overflow-x-auto px-5 pb-1 lg:mx-0 lg:px-0">
            @foreach (['Todas', ...App\Models\Recipe::CATEGORIES] as $nombre)
                @php
                    $valor = $nombre === 'Todas' ? null : $nombre;
                    $activa = $categoria === $valor;
                @endphp
                {{-- Cambia la categoría y conserva los demás filtros; vuelve a la página 1. --}}
                <a
                    href="{{ request()->fullUrlWithQuery(['categoria' => $valor, 'page' => null]) }}"
                    @if ($activa) aria-current="true" @endif
                    class="flex h-11 shrink-0 items-center rounded-full px-5 font-display text-[15px] font-bold transition {{ $activa ? 'bg-charcoal text-white' : 'border-2 border-cream-300 bg-white text-charcoal hover:border-tomato-200' }}"
                >
                    {{ $nombre }}
                </a>
            @endforeach
        </div>
        <div class="flex gap-2">
            <x-select
                form="filtros" onchange="this.form.submit()"
                label="Tiempo" name="tiempo" :value="$filters['tiempo'] ?? null"
                :options="['' => 'Cualquier tiempo', '15' => 'Hasta 15 min', '30' => 'Hasta 30 min', '60' => 'Hasta 1 hora']"
            />
            <x-select
                form="filtros" onchange="this.form.submit()"
                label="Dificultad" name="dificultad" :value="$filters['dificultad'] ?? null"
                :options="['' => 'Toda dificultad', 'Fácil' => 'Fácil', 'Media' => 'Media']"
            />
        </div>
    </section>
    {{-- --- Resultados --- --}}
    <div class="mt-8 mb-5 flex items-baseline justify-between">
        <h2 class="text-2xl font-black">{{ $categoria ?? 'Todas las recetas' }}</h2>
        <span class="text-sm text-charcoal-soft">{{ $recipes->count() }} de {{ $total }} recetas</span>
    </div>

    @if ($recipes->isNotEmpty())
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($recipes as $recipe)
                <x-recipe-card
                    :recipe="$recipe"
                    :saved="in_array($recipe->id, $savedIds)"
                    :cooked="in_array($recipe->id, $cookedIds)"
                />
            @endforeach
        </div>

        {{ $recipes->links('pagination.youcook') }}
    @else
        <div class="rounded-3xl border-2 border-dashed border-cream-300 px-6 py-16 text-center">
            <div class="mx-auto w-fit"><x-sticker kind="olla" size="96" /></div>
            <h3 class="mt-4 text-xl font-extrabold">No encontramos recetas con esos filtros</h3>
            <p class="mt-1 text-charcoal-soft">Prueba con otro ingrediente o quita algún filtro.</p>
            @if ($hayFiltros)
                <a href="{{ route('recetas.index') }}" class="mt-5 inline-block rounded-full bg-charcoal px-5 py-2.5 font-display font-bold text-white">Limpiar filtros</a>
            @endif
        </div>
    @endif

    {{-- --- Invitación al asistente --- --}}
    <a href="{{ route('asistente.show') }}" class="mt-14 flex flex-col items-start gap-5 rounded-[28px] bg-basil-500 p-7 text-white sm:flex-row sm:items-center md:p-9">
        <span class="grid size-14 shrink-0 place-items-center rounded-2xl bg-white/15"><x-icon name="sparkle" size="28" /></span>
        <span class="flex-1">
            <span class="block font-display text-2xl font-black">¿No sabes qué hacer con lo que tienes?</span>
            <span class="mt-1 block text-white/85">Cuéntale al asistente qué hay en tu refri y te sugiere una receta en segundos.</span>
        </span>
        <span class="flex items-center gap-2 rounded-full bg-white px-5 py-3 font-display font-extrabold text-basil-600">Abrir asistente <x-icon name="arrow-right" size="18" /></span>
    </a>
@endsection
