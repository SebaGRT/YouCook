@props(['recipe', 'saved' => false, 'cooked' => false])

<article id="receta-{{ $recipe->slug }}" class="group relative flex scroll-mt-24 flex-col overflow-hidden rounded-3xl bg-white shadow-soft transition duration-300 hover:-translate-y-1 hover:shadow-lift">
    <div class="relative aspect-[4/3] overflow-hidden bg-cream-200">
        <x-recipe-photo :recipe="$recipe" loading="lazy" class="size-full object-cover transition duration-500 group-hover:scale-105" />
        <x-bookmark-button :recipe="$recipe" :saved="$saved" class="absolute top-3 right-3 z-10" />
        <span class="absolute bottom-3 left-3 rounded-full bg-white/95 px-3 py-1 text-xs font-semibold text-charcoal">{{ $recipe->category }}</span>
        @if ($cooked)
            <span class="absolute top-3 left-3 rounded-full bg-basil-500 px-2.5 py-1 text-[11px] font-bold text-white">Cocinada</span>
        @endif
    </div>
    <div class="flex flex-1 flex-col gap-3 p-5">
        <h3 class="text-[19px] leading-snug font-extrabold">
            {{-- after:inset-0 estira el enlace para que toda la tarjeta lleve al detalle. --}}
            <a href="{{ route('recetas.show', $recipe) }}" class="after:absolute after:inset-0">{{ $recipe->title }}</a>
        </h3>
        <div class="mt-auto flex items-center gap-4 text-[13px] font-medium text-charcoal-soft">
            <span class="inline-flex items-center gap-1">
                <x-icon name="clock" size="15" />
                {{ $recipe->minutes }} min
            </span>
            <x-difficulty :level="$recipe->difficulty" />
            <span class="ml-auto font-semibold text-charcoal">{{ $recipe->cost_label }}</span>
        </div>
    </div>
</article>
