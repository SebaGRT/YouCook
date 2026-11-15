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
        {{-- Los números de la barra se cuentan aquí: este layout lo usan todas las páginas con sesión. --}}
        <x-nav
            :user="auth()->user()"
            :saved="auth()->user()->savedRecipes()->count()"
            :cooked="auth()->user()->cookedRecipes()->count()"
            :total="\App\Models\Recipe::count()"
        />

        <main class="mx-auto max-w-[1280px] px-5 pt-[96px] pb-28 md:px-10 md:pt-[104px] md:pb-16">
            @yield('contenido')
        </main>

        @if (session('toast'))
            <x-toast :toast="session('toast')" />
        @endif
    </body>
</html>
