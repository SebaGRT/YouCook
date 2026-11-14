<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>En construcción · {{ config('app.name') }}</title>

        @fonts
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="grid min-h-screen place-items-center p-6">
        <main class="w-full max-w-md rounded-3xl bg-white p-8 text-center shadow-soft">
            <p class="font-display text-sm font-extrabold tracking-wider text-tomato-600 uppercase">YouCook</p>
            <h1 class="mt-2 text-3xl font-black">En construcción</h1>
            <p class="mt-2 text-charcoal-soft">Esta pantalla todavía no existe: <code>/{{ request()->path() }}</code></p>

            <nav class="mt-6 flex flex-wrap justify-center gap-2 text-sm font-bold">
                @foreach (['recetas.index' => 'Recetas', 'guardados.index' => 'Guardados', 'asistente.show' => 'Asistente', 'perfil.show' => 'Perfil'] as $ruta => $nombre)
                    <a href="{{ route($ruta) }}" class="rounded-full bg-cream-200 px-4 py-2 hover:bg-tomato-50 hover:text-tomato-600">{{ $nombre }}</a>
                @endforeach
            </nav>

            <form method="POST" action="{{ route('logout') }}" class="mt-6">
                @csrf
                <button class="text-sm font-semibold text-charcoal-soft underline">Cerrar sesión ({{ auth()->user()->name }})</button>
            </form>
        </main>
    </body>
</html>

