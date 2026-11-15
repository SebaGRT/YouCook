@extends('layouts.app')

@section('titulo', $recipe->title)

@section('contenido')
    <article>
        <a href="{{ route('recetas.index') }}" class="mb-6 inline-flex items-center gap-2 text-sm font-semibold text-charcoal-soft hover:text-charcoal">
            <x-icon name="arrow-left" size="17" /> Volver a recetas
        </a>

        {{-- --- Foto y resumen --- --}}
        <div class="grid gap-8 lg:grid-cols-[1.15fr_1fr] lg:gap-12">
            <div class="relative overflow-hidden rounded-[32px] bg-cream-200 shadow-soft">
                <x-recipe-photo :recipe="$recipe" sticker="220" class="aspect-[4/3] size-full object-cover lg:aspect-auto lg:h-[520px]" />
                <x-bookmark-button :recipe="$recipe" :saved="$isSaved" size="size-12" class="absolute top-5 right-5 lg:hidden" />
            </div>

            <div class="flex flex-col">
                <span class="w-fit rounded-full bg-tomato-50 px-3 py-1 text-sm font-bold text-tomato-600">{{ $recipe->category }}</span>
                <h1 class="mt-4 text-[38px] leading-[1.05] font-black md:text-[52px]">{{ $recipe->title }}</h1>
                <p class="mt-4 text-lg leading-relaxed text-charcoal-soft">{{ $recipe->description }}</p>

                <dl class="mt-7 grid grid-cols-2 gap-2 gap-y-4 rounded-3xl bg-white p-4 shadow-soft sm:grid-cols-4">
                    <div class="text-center">
                        <dt class="text-xs text-charcoal-soft">Tiempo</dt>
                        <dd class="mt-1 flex items-center justify-center gap-1 font-display text-[17px] font-extrabold">
                            <x-icon name="clock" size="15" class="text-tomato-500" /> {{ $recipe->minutes }} min
                        </dd>
                    </div>
                    <div class="text-center">
                        <dt class="text-xs text-charcoal-soft">Dificultad</dt>
                        <dd class="mt-1 flex items-center justify-center gap-1 font-display text-[17px] font-extrabold">
                            <x-difficulty :level="$recipe->difficulty" />
                        </dd>
                    </div>
                    <div class="text-center">
                        <dt class="text-xs text-charcoal-soft">Porciones</dt>
                        <dd class="mt-1 flex items-center justify-center gap-1 font-display text-[17px] font-extrabold">
                            <x-icon name="users" size="15" class="text-tomato-500" /> {{ $recipe->servings }}
                        </dd>
                    </div>
                    <div class="text-center">
                        <dt class="text-xs text-charcoal-soft">Costo aprox.</dt>
                        <dd class="mt-1 flex items-center justify-center gap-1 font-display text-[17px] font-extrabold">
                            <x-icon name="wallet" size="15" class="text-tomato-500" /> {{ $recipe->cost_label }}
                        </dd>
                    </div>
                </dl>
                {{-- --- Cocinada y Guardar: dos formularios POST --- --}}
                <div class="mt-5 flex gap-3">
                    <form method="POST" action="{{ route('recetas.cocinada', $recipe) }}" class="flex-1">
                        @csrf
                        <button class="flex h-14 w-full items-center justify-center gap-2 rounded-2xl font-display text-[17px] font-extrabold transition active:translate-y-px {{ $isCooked ? 'bg-basil-100 text-basil-600' : 'bg-basil-500 text-white shadow-[inset_0_-4px_0_rgb(0_0_0/0.14)] hover:bg-basil-600' }}">
                            <x-icon name="check" size="20" stroke="2.6" />
                            {{ $isCooked ? '¡Ya la cocinaste!' : 'Marcar como cocinada' }}
                        </button>
                    </form>
                    <form method="POST" action="{{ route('recetas.guardar', $recipe) }}">
                        @csrf
                        <button
                            aria-pressed="{{ $isSaved ? 'true' : 'false' }}"
                            class="flex h-14 items-center gap-2 rounded-2xl border-2 px-5 font-display font-extrabold transition {{ $isSaved ? 'border-tomato-500 bg-tomato-50 text-tomato-600' : 'border-cream-300 bg-white hover:border-tomato-200' }}"
                        >
                            <x-icon name="bookmark" size="19" :filled="$isSaved" />
                            <span class="hidden sm:inline">{{ $isSaved ? 'Guardada' : 'Guardar' }}</span>
                        </button>
                    </form>
                </div>

                <div class="mt-5 flex gap-3 rounded-2xl border border-[#F3D18A] bg-[#FFF4D6] p-4">
                    <x-icon name="alert" size="20" class="mt-0.5 shrink-0 text-[#B07A0B]" />
                    <div class="text-[15px]">
                        <p class="font-bold text-[#7A5304]">Alérgenos</p>
                        <p class="mt-0.5 text-[#7A5304]/90">
                            {{ implode(' · ', $recipe->allergens) }}. Revisa las etiquetas de tus productos: pueden contener trazas.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-12 grid gap-8 lg:grid-cols-[1fr_1.15fr] lg:gap-12">
            {{-- --- Ingredientes --- --}}
            <section class="h-fit rounded-[28px] bg-white p-6 shadow-soft md:p-8">
                <div class="flex items-baseline justify-between">
                    <h2 class="text-2xl font-black">Ingredientes</h2>
                    <span class="text-sm text-charcoal-soft">{{ $recipe->servings }} {{ $recipe->servings === 1 ? 'porción' : 'porciones' }}</span>
                </div>
                <p class="mt-1 mb-3 text-sm text-charcoal-soft">¿Te falta algo? Toca «No lo tengo» y la IA te sugiere un reemplazo.</p>
                <ul>
                    @foreach ($recipe->ingredients as $ingredient)
                        <x-ingredient-row :ingredient="$ingredient" :recipe="$recipe" />
                    @endforeach
                </ul>
            </section>

            {{-- --- Preparación --- --}}
            {{-- «hechos» cuenta los pasos marcados; cada paso recuerda si está «listo». --}}
            <section x-data="{ hechos: 0 }">
                <div class="flex items-baseline justify-between">
                    <h2 class="text-2xl font-black">Preparación</h2>
                    <span class="text-sm text-charcoal-soft"><span x-text="hechos">0</span> de {{ count($recipe->steps) }} pasos</span>
                </div>
                <ol class="mt-5 space-y-3">
                    @foreach ($recipe->steps as $step)
                        <li x-data="{ listo: false }">
                            <button
                                type="button"
                                x-on:click="listo = ! listo; hechos += listo ? 1 : -1"
                                class="flex w-full gap-4 rounded-2xl bg-white p-4 text-left shadow-soft transition hover:shadow-lift"
                                :class="{ 'bg-basil-50': listo, 'bg-white shadow-soft hover:shadow-lift': ! listo }"
                            >
                                <span
                                    class="grid size-10 shrink-0 place-items-center rounded-full bg-tomato-50 font-display text-lg font-black text-tomato-600"
                                    :class="{ 'bg-basil-500 text-white': listo, 'bg-tomato-50 text-tomato-600': ! listo }"
                                >
                                    <x-icon name="check" size="18" stroke="3" x-show="listo" x-cloak />
                                    <span x-show="! listo">{{ $loop->iteration }}</span>
                                </span>
                                <span class="pt-1.5 text-[16px] leading-relaxed" :class="{ 'text-charcoal-soft': listo }">{{ $step }}</span>
                            </button>
                        </li>
                    @endforeach
                </ol>

                <a href="{{ route('asistente.show', ['receta' => $recipe->slug]) }}" class="mt-6 flex items-center gap-4 rounded-2xl border-2 border-dashed border-basil-100 bg-basil-50/60 p-5 transition hover:border-basil-500">
                    <span class="grid size-11 place-items-center rounded-xl bg-basil-500 text-white"><x-icon name="chat" size="20" /></span>
                    <span class="flex-1">
                        <span class="block font-display font-extrabold">¿Dudas con algún paso?</span>
                        <span class="block text-sm text-charcoal-soft">Pregúntale al asistente sobre {{ mb_strtolower($recipe->title) }}.</span>
                    </span>
                    <x-icon name="arrow-right" size="18" class="text-basil-600" />
                </a>
            </section>
        </div>
    </article>
@endsection
