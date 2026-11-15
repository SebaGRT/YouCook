@extends('layouts.app')

@section('titulo', 'Perfil')

@section('contenido')
    @php
        $porcentaje = $total > 0 ? (int) round($cooked / $total * 100) : 0;
        $faltan = 10 - $cooked;
        $giros = [-6, 4, -3, 7, -8, 3, -4, 6, -2, 5];
    @endphp

    <div class="grid gap-8 lg:grid-cols-[340px_1fr]">
        <aside class="h-fit space-y-5">
            {{-- --- Tarjeta de la persona --- --}}
            <div class="rounded-[28px] bg-white p-7 text-center shadow-soft">
                <div class="mx-auto w-fit"><x-avatar size="96" /></div>
                <h1 class="mt-4 text-[26px] font-black">{{ $user->name }}</h1>
                <p class="text-charcoal-soft">{{ $user->email }}</p>
                <span class="mt-3 inline-block rounded-full bg-tomato-50 px-3 py-1 text-sm font-bold text-tomato-600">{{ $level }}</span>
                <dl class="mt-6 grid grid-cols-3 divide-x divide-cream-200 rounded-2xl bg-cream p-3">
                    @foreach (['Cocinadas' => $cooked, 'Guardadas' => $saved, 'Stickers' => $cooked] as $nombre => $valor)
                        <div>
                            <dd class="font-display text-2xl font-black">{{ $valor }}</dd>
                            <dt class="text-xs text-charcoal-soft">{{ $nombre }}</dt>
                        </div>
                    @endforeach
                </dl>
            </div>
            {{-- --- Mis datos: se ven como texto y «Editar» los cambia por campos --- --}}
            <form
                method="POST"
                action="{{ route('perfil.update') }}"
                class="space-y-4 rounded-[28px] bg-white p-6 shadow-soft"
                x-data="{ editando: @js($errors->any()) }"
            >
                @csrf
                @method('PATCH')

                <h2 class="font-display text-lg font-extrabold">Mis datos</h2>

                <div x-show="! editando" class="space-y-4">
                    @foreach (['Nombre' => $user->name, 'Correo' => $user->email, 'Miembro desde' => Str::ucfirst($user->created_at->translatedFormat('F Y'))] as $nombre => $valor)
                        <div class="flex items-center justify-between gap-3 border-b border-cream-200 pb-3 text-sm last:border-0">
                            <span class="shrink-0 text-charcoal-soft">{{ $nombre }}</span>
                            <span class="truncate font-semibold">{{ $valor }}</span>
                        </div>
                    @endforeach
                </div>

                <div x-show="editando" x-cloak class="space-y-4">
                    <div>
                        <x-input-label for="name" value="Nombre" />
                        <x-text-input id="name" name="name" class="w-full" :value="old('name', $user->name)" required autocomplete="name" />
                        <x-input-error :messages="$errors->get('name')" class="mt-1.5" />
                    </div>
                    <div>
                        <x-input-label for="email" value="Correo" />
                        <x-text-input id="email" name="email" type="email" class="w-full" :value="old('email', $user->email)" required autocomplete="username" />
                        <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
                    </div>
                </div>

                <div class="flex gap-2 pt-1">
                    <button type="button" x-show="! editando" x-on:click="editando = true" class="flex flex-1 items-center justify-center gap-2 rounded-xl border-2 border-cream-300 py-2.5 text-sm font-bold hover:border-tomato-200">
                        <x-icon name="pencil" size="15" /> Editar
                    </button>
                    <button type="submit" x-show="editando" x-cloak class="flex flex-1 items-center justify-center gap-2 rounded-xl bg-tomato-500 py-2.5 text-sm font-bold text-white hover:bg-tomato-600">
                        <x-icon name="check" size="15" /> Guardar
                    </button>
                    {{-- Este botón envía el formulario «salir», que está más abajo
                         (un formulario no puede ir dentro de otro). --}}
                    <button type="submit" form="salir" class="flex flex-1 items-center justify-center gap-2 rounded-xl py-2.5 text-sm font-bold text-charcoal-soft hover:bg-cream-200">
                        <x-icon name="logout" size="15" /> Cerrar sesión
                    </button>
                </div>
            </form>

            <form id="salir" method="POST" action="{{ route('logout') }}" class="hidden">
                @csrf
            </form>
        </aside>

        <div class="space-y-8">
            {{-- --- Progreso del reto --- --}}
            <section class="relative overflow-hidden rounded-[32px] bg-charcoal p-7 text-white md:p-9">
                <div class="pointer-events-none absolute -top-6 right-6 hidden md:block"><x-sticker kind="olla2" size="120" rotate="12" /></div>
                <p class="font-display text-sm font-extrabold tracking-wider text-[#FFC23D] uppercase">Reto YouCook</p>
                <h2 class="mt-2 max-w-md text-[30px] leading-tight font-black md:text-[38px]">
                    {{ $cooked }} de {{ $total }} recetas cocinadas
                </h2>
                <p class="mt-2 text-white/75">
                    {{ $faltan > 0 ? "Te faltan {$faltan} para desbloquear la insignia «Chef de pensión»." : '¡Desbloqueaste «Chef de pensión»! Vamos por las 20.' }}
                </p>
                <div class="mt-6 flex items-center gap-4">
                    <div class="h-4 flex-1 overflow-hidden rounded-full bg-white/15" role="progressbar" aria-valuenow="{{ $cooked }}" aria-valuemin="0" aria-valuemax="{{ $total }}">
                        <div class="h-full rounded-full bg-gradient-to-r from-[#FFC23D] to-tomato-500 transition-all duration-700" style="width: {{ $porcentaje }}%"></div>
                    </div>
                    <span class="font-display text-xl font-black">{{ $porcentaje }}%</span>
                </div>
                <div class="mt-3 flex justify-between text-xs text-white/60">
                    <span>Inicio</span><span>5 · Aprendiz</span><span>10 · Chef de pensión</span><span>20 · Maestra</span>
                </div>
            </section>
            {{-- --- Stickers --- --}}
            <section>
                <div class="flex items-baseline justify-between">
                    <h2 class="text-2xl font-black">Mis stickers</h2>
                    <span class="text-sm text-charcoal-soft">{{ $cooked }} de {{ $total }} desbloqueados</span>
                </div>
                <div class="mt-5 grid grid-cols-3 gap-3 rounded-[28px] bg-white bg-[radial-gradient(circle_at_1px_1px,#efdcc0_1px,transparent_0)] [background-size:18px_18px] p-5 shadow-soft sm:grid-cols-5 xl:grid-cols-10">
                    @foreach ($recipes as $recipe)
                        @php $ganado = in_array($recipe->id, $cookedIds); @endphp
                        <div class="flex flex-col items-center gap-1 text-center" title="{{ $ganado ? $recipe->sticker_name : 'Bloqueado' }}">
                            <div class="{{ $ganado ? 'pop' : 'opacity-80' }}" style="animation-delay: {{ $loop->index * 40 }}ms">
                                <x-sticker :kind="$recipe->sticker" size="72" :locked="! $ganado" :rotate="$giros[$loop->index % count($giros)]" />
                            </div>
                            <span class="text-[11px] leading-tight font-semibold {{ $ganado ? 'text-charcoal' : 'text-[#B3A99D]' }}">{{ $ganado ? $recipe->sticker_name : '???' }}</span>
                        </div>
                    @endforeach
                </div>
            </section>

            {{-- --- Checklist --- --}}
            <section class="rounded-[28px] bg-white p-6 shadow-soft md:p-8">
                <h2 class="text-2xl font-black">Checklist de recetas</h2>
                <p class="mt-1 text-sm text-charcoal-soft">Cada receta que marques como cocinada te da un sticker nuevo.</p>
                <ul class="mt-5 grid gap-x-8 sm:grid-cols-2">
                    @foreach ($recipes as $recipe)
                        @php $ganado = in_array($recipe->id, $cookedIds); @endphp
                        <li class="flex items-center gap-3 border-b border-cream-200 py-3">
                            <span class="grid size-6 shrink-0 place-items-center rounded-lg {{ $ganado ? 'bg-basil-500 text-white' : 'border-2 border-cream-300' }}">
                                @if ($ganado)
                                    <x-icon name="check" size="14" stroke="3" />
                                @endif
                            </span>
                            <a href="{{ route('recetas.show', $recipe) }}" class="flex-1 hover:text-tomato-600 {{ $ganado ? 'text-charcoal-soft line-through decoration-cream-300' : 'font-medium' }}">{{ $recipe->title }}</a>
                            @unless ($ganado)
                                <x-icon name="lock" size="14" class="text-[#C9B394]" />
                            @endunless
                        </li>
                    @endforeach
                </ul>
            </section>
        </div>
    </div>
@endsection
