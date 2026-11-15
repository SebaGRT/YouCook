@props(['ingredient', 'recipe'])

{{-- Una fila de ingrediente. El estado (tengo, estado, alternativas) vive en
     resources/js/ingrediente.js; aquí solo se dibuja según ese estado. --}}
<li
    class="border-b border-cream-200 last:border-0"
    x-data="ingrediente(@js(route('recetas.alternativas', [$recipe, $ingredient])))"
>
    {{-- --- La fila --- --}}
    <div class="flex items-center gap-3 py-3.5">
        <button
            type="button"
            x-on:click="tengo = ! tengo"
            :aria-pressed="tengo.toString()"
            aria-label="Marcar {{ $ingredient->name }}"
            class="grid size-6 shrink-0 place-items-center rounded-lg border-2 border-cream-300 bg-white transition"
            :class="{ 'border-basil-500 bg-basil-500 text-white': tengo, 'border-cream-300 bg-white': ! tengo }"
        >
            <x-icon name="check" size="14" stroke="3" x-show="tengo" x-cloak />
        </button>
        <div class="flex-1" :class="{ 'text-charcoal-soft line-through decoration-cream-300': tengo }">
            <span class="font-semibold">{{ $ingredient->name }}</span><span class="ml-2 text-charcoal-soft">{{ $ingredient->quantity }}</span>
        </div>
        <button
            type="button"
            x-show="estado === 'inactivo'"
            x-on:click="pedir()"
            class="flex shrink-0 items-center gap-1.5 rounded-full bg-cream-200 px-3 py-1.5 text-[13px] font-bold text-charcoal transition hover:bg-basil-100 hover:text-basil-600"
        >
            <x-icon name="swap" size="14" /> No lo tengo
        </button>
        <button
            type="button"
            x-show="estado !== 'inactivo'"
            x-cloak
            x-on:click="cerrar()"
            class="grid size-8 place-items-center rounded-full text-charcoal-soft hover:bg-cream-200"
            aria-label="Cerrar alternativas"
        >
            <x-icon name="x" size="16" />
        </button>
    </div>
    {{-- --- El panel de la IA --- --}}
    <div x-show="estado !== 'inactivo'" x-cloak class="rise mb-4 rounded-2xl border border-basil-100 bg-basil-50 p-4">
        <p class="mb-3 flex items-center gap-1.5 text-xs font-extrabold tracking-wide text-basil-600 uppercase">
            <x-icon name="sparkle" size="14" /> Alternativas sugeridas por IA
        </p>

        <div x-show="estado === 'cargando'">
            <x-ia-loading label="Buscando reemplazos para {{ mb_strtolower($ingredient->name) }}…" />
        </div>

        <div x-show="estado === 'error'">
            <x-ia-error x-on:click="pedir()" />
        </div>

        <div x-show="estado === 'listo'">
            <ul class="space-y-2">
                <template x-for="alternativa in alternativas" :key="alternativa.hacia">
                    <li class="flex items-start gap-3 rounded-xl bg-white p-3 text-[15px] shadow-soft">
                        <x-icon name="arrow-right" size="16" class="mt-1 shrink-0 text-basil-500" />
                        <span>
                            <span class="text-charcoal-soft" x-show="alternativa.desde" x-text="alternativa.desde + ' → '"></span>
                            <span class="font-semibold" x-text="alternativa.hacia"></span>
                        </span>
                    </li>
                </template>
            </ul>
            <p class="mt-3 text-xs text-charcoal-soft">Sugerencia generada por IA para «{{ $recipe->title }}». Revisa alérgenos antes de reemplazar.</p>
        </div>
    </div>
</li>

