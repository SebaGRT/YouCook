<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('titulo') · {{ config('app.name') }}</title>
        <link rel="icon" href="/favicon.png" type="image/png">

        @fonts
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body>
        <div class="grid min-h-screen lg:grid-cols-[1.05fr_1fr]">
            {{-- --- Mitad izquierda: foto y frase (solo en pantallas grandes) --- --}}
            <aside class="relative hidden overflow-hidden lg:block">
                <img
                    src="https://images.unsplash.com/photo-1653233797467-1a528819fd4f?crop=entropy&cs=tinysrgb&fit=crop&fm=jpg&q=80&w=1400"
                    alt="Persona picando verduras en una tabla"
                    class="absolute inset-0 size-full object-cover"
                >
                <div class="absolute inset-0 bg-gradient-to-t from-[#2E2B28]/85 via-[#2E2B28]/25 to-transparent"></div>
                <div class="absolute top-10 left-10 rounded-2xl bg-cream px-4 py-3"><x-logo /></div>
                <div class="absolute right-12 bottom-12 left-12 text-white">
                    <div class="mb-6 flex gap-2">
                        <x-sticker kind="huevo" size="76" rotate="-8" />
                        <x-sticker kind="tallarin" size="76" rotate="6" />
                        <x-sticker kind="palta" size="76" rotate="-4" />
                    </div>
                    <h2 class="max-w-md text-[44px] leading-[1.05] font-black">Cocina rico con lo que tienes en el refri.</h2>
                    <p class="mt-4 max-w-md text-lg text-white/85">Recetas simples, alternativas para ingredientes que te faltan y un asistente que responde en segundos.</p>
                </div>
            </aside>
            {{-- --- Mitad derecha: el formulario de cada página --- --}}
            <main class="flex items-center justify-center px-6 py-12 sm:px-12">
                <div class="w-full max-w-[420px]">
                    <x-logo class="mb-10 lg:hidden" />
                    @yield('contenido')
                </div>
            </main>
        </div>
    </body>
</html>
