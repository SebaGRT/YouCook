@extends('layouts.app')

@section('titulo', 'Asistente')

@section('contenido')
    <div class="grid gap-8 lg:grid-cols-[300px_1fr]">
        {{-- --- Columna izquierda (solo en pantallas grandes) --- --}}
        <aside class="hidden space-y-5 lg:block">
            <div>
                <h1 class="text-[32px] leading-tight font-black">Asistente</h1>
                <p class="mt-1 text-charcoal-soft">Respuestas cortas, sin videos de 15 minutos.</p>
            </div>
            @if ($recipe)
                <a href="{{ route('recetas.show', $recipe) }}" class="block overflow-hidden rounded-3xl bg-white shadow-soft">
                    <x-recipe-photo :recipe="$recipe" class="aspect-[16/10] w-full object-cover" />
                    <div class="p-4">
                        <p class="text-xs font-bold tracking-wide text-charcoal-soft uppercase">Hablando sobre</p>
                        <p class="font-display text-lg font-extrabold">{{ $recipe->title }}</p>
                    </div>
                </a>
            @endif
            <div class="rounded-3xl bg-cream-200 p-5 text-sm">
                <p class="font-display font-extrabold">Puedes preguntar cosas como</p>
                <ul class="mt-2 list-disc space-y-1.5 pl-4 text-charcoal-soft">
                    <li>¿Qué hago con arroz, huevo y cebolla?</li>
                    <li>¿Cuánto dura en el refri?</li>
                    <li>Se me pasó de sal, ¿cómo lo arreglo?</li>
                </ul>
            </div>
        </aside>
        {{-- --- El chat: su estado vive en resources/js/chat.js --- --}}
        <section
            x-data="chat({ url: @js(route('asistente.send')), mensajes: @js($mensajes) })"
            class="flex h-[calc(100vh-170px)] min-h-[560px] flex-col overflow-hidden rounded-[32px] bg-cream-200/60 ring-1 ring-cream-300 md:h-[calc(100vh-150px)]"
        >
            <header class="flex items-center gap-3 border-b border-cream-300 bg-white/70 px-6 py-4">
                <span class="grid size-11 place-items-center rounded-2xl bg-basil-500 text-white"><x-icon name="sparkle" size="22" /></span>
                <div class="flex-1">
                    <p class="font-display text-lg font-extrabold">Asistente YouCook</p>
                    <p class="flex items-center gap-1.5 text-xs font-semibold text-basil-600" :class="{ 'text-tomato-600': caida, 'text-basil-600': ! caida }">
                        <span class="size-2 rounded-full bg-basil-500" :class="{ 'bg-tomato-500': caida, 'bg-basil-500': ! caida }"></span>
                        <span x-text="caida ? 'Sin conexión con la IA' : 'En línea · responde en segundos'">En línea · responde en segundos</span>
                    </p>
                </div>
                <form method="POST" action="{{ route('asistente.reset') }}">
                    @csrf
                    @method('DELETE')
                    <button class="rounded-full px-3 py-1.5 text-sm font-semibold text-charcoal-soft hover:bg-cream-200">Nueva conversación</button>
                </form>
            </header>
            {{-- --- Mensajes --- --}}
            <div class="flex-1 space-y-5 overflow-y-auto px-4 py-6 md:px-8" aria-live="polite">
                <p class="mx-auto w-fit rounded-full bg-white/80 px-3 py-1 text-xs text-charcoal-soft">Hoy, {{ now()->format('H:i') }}</p>

                {{-- El saludo siempre va primero y no se guarda en la conversación. --}}
                <div class="flex gap-3">
                    <span class="grid size-[34px] shrink-0 place-items-center rounded-full bg-basil-500 text-white"><x-icon name="sparkle" size="17" /></span>
                    <p class="max-w-[78%] rounded-3xl rounded-bl-md bg-white px-5 py-3 text-[15px] leading-relaxed shadow-soft">
                        ¡Hola, {{ auth()->user()->first_name }}! Soy tu asistente de cocina.
                        @if ($recipe)
                            Estás viendo {{ mb_strtolower($recipe->title) }}: pregúntame lo que necesites, te respondo corto y al grano.
                        @else
                            Cuéntame qué tienes en el refri o pregúntame lo que necesites: te respondo corto y al grano.
                        @endif
                    </p>
                </div>

                <template x-for="m in mensajes" :key="m.id">
                    <div>
                        {{-- Burbuja de la persona --}}
                        <div x-show="m.de === 'usuario'" class="rise flex justify-end gap-3">
                            <p class="max-w-[78%] rounded-3xl rounded-br-md bg-tomato-500 px-5 py-3 text-[15px] leading-relaxed text-white" x-text="m.texto"></p>
                            <x-avatar size="34" />
                        </div>

                        {{-- Burbuja de la IA: pensando, error o respuesta --}}
                        <div x-show="m.de === 'ia'" class="rise flex gap-3">
                            <span class="grid size-[34px] shrink-0 place-items-center rounded-full bg-basil-500 text-white"><x-icon name="sparkle" size="17" /></span>
                            <div
                                class="max-w-[78%] rounded-3xl rounded-bl-md px-5 py-3 text-[15px] leading-relaxed"
                                :class="m.estado === 'error' ? 'border border-tomato-100 bg-tomato-50/60' : 'bg-white shadow-soft'"
                            >
                                <span x-show="m.estado === 'cargando'" role="status" class="flex items-center gap-3 py-1 text-sm text-charcoal-soft">
                                    <span class="flex gap-1">
                                        <span class="dot size-2 rounded-full bg-basil-500"></span>
                                        <span class="dot size-2 rounded-full bg-basil-500 [animation-delay:.15s]"></span>
                                        <span class="dot size-2 rounded-full bg-basil-500 [animation-delay:.3s]"></span>
                                    </span>
                                    Pensando la respuesta…
                                </span>
                                <div x-show="m.estado === 'error'">
                                    <x-ia-error x-on:click="responder(m.id)" />
                                </div>
                                <span x-show="! m.estado" class="whitespace-pre-line" x-text="m.texto"></span>
                            </div>
                        </div>
                    </div>
                </template>

                <div x-ref="fin"></div>
            </div>
            {{-- --- Sugerencias y caja de texto --- --}}
            <div class="border-t border-cream-300 bg-white px-4 pt-4 pb-3 md:px-6">
                <div class="-mx-1 mb-3 flex gap-2 overflow-x-auto px-1 pb-1">
                    @foreach ($sugerencias as $sugerencia)
                        <button
                            type="button"
                            :disabled="ocupado"
                            x-on:click="enviar(@js($sugerencia))"
                            class="shrink-0 rounded-full border-2 border-basil-100 bg-basil-50 px-4 py-2 text-sm font-semibold text-basil-600 transition hover:border-basil-500 disabled:opacity-50"
                        >
                            {{ $sugerencia }}
                        </button>
                    @endforeach
                </div>
                <form
                    x-on:submit.prevent="enviar()"
                    class="flex items-center gap-2 rounded-2xl border-2 border-cream-300 bg-cream/60 p-1.5 pl-4 focus-within:border-tomato-500"
                >
                    <input
                        name="mensaje"
                        x-model="texto"
                        maxlength="500"
                        autocomplete="off"
                        placeholder="Escribe tu duda de cocina…"
                        class="h-11 flex-1 bg-transparent text-[15px] outline-none placeholder:text-[#B3A99D]"
                    >
                    <button :disabled="! texto.trim() || ocupado" class="grid size-11 place-items-center rounded-xl bg-tomato-500 text-white transition hover:bg-tomato-600 disabled:bg-cream-300" aria-label="Enviar">
                        <x-icon name="send" size="19" />
                    </button>
                </form>
                <p class="mt-2.5 flex items-center justify-center gap-1.5 text-xs text-charcoal-soft">
                    <x-icon name="info" size="13" /> La IA puede equivocarse. Verifica alérgenos y tiempos de cocción.
                </p>
            </div>
        </section>
    </div>
@endsection

