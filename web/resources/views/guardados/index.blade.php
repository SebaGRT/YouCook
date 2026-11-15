@extends('layouts.app')

@section('titulo', 'Guardados')

@section('contenido')
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <h1 class="text-[34px] leading-tight font-black md:text-[44px]">Guardados</h1>
            <p class="mt-1 text-charcoal-soft">
                {{ $recipes->isEmpty() ? 'Tu recetario personal.' : trans_choice('{1} 1 receta para cuando te dé hambre.|[2,*] :count recetas para cuando te dé hambre.', $recipes->count()) }}
            </p>
        </div>
    </div>

    @if ($recipes->isNotEmpty())
        <div class="mt-8 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($recipes as $recipe)
                <x-recipe-card :recipe="$recipe" saved :cooked="in_array($recipe->id, $cookedIds)" />
            @endforeach
        </div>
    @else
        <div class="mt-8 flex flex-col items-center rounded-[32px] bg-white px-6 py-16 text-center shadow-soft md:py-24">
            <div class="relative h-32 w-48">
                <div class="absolute top-6 left-2"><x-sticker kind="pan" size="84" rotate="-14" /></div>
                <div class="absolute top-0 right-2"><x-sticker kind="tomate" size="96" rotate="10" /></div>
            </div>
            <h2 class="mt-4 text-[26px] font-black">Aún no guardas recetas</h2>
            <p class="mt-2 max-w-sm text-charcoal-soft">
                Toca el ícono <x-icon name="bookmark" size="16" class="inline -translate-y-px text-tomato-500" /> en cualquier receta y aparecerá aquí para tenerla a mano.
            </p>
            <a href="{{ route('recetas.index') }}" class="mt-7 rounded-2xl bg-tomato-500 px-6 py-3.5 font-display font-extrabold text-white shadow-[inset_0_-4px_0_rgb(0_0_0/0.14)] hover:bg-tomato-600">
                Explorar recetas
            </a>
        </div>
    @endif
@endsection
