<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Muestra · YouCook</title>

        @fonts
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="space-y-8 p-8">
        <x-logo />

        <section>
            <h2 class="text-2xl font-black">Íconos</h2>
            <div class="mt-3 flex flex-wrap gap-4 text-tomato-600">
                @foreach (['search', 'bookmark', 'clock', 'flame', 'users', 'wallet', 'check', 'sparkle', 'send', 'alert', 'info', 'arrow-left', 'arrow-right', 'x', 'refresh', 'lock', 'home', 'chat', 'user', 'swap', 'mail', 'eye', 'pencil', 'logout'] as $nombre)
                    <span class="flex flex-col items-center gap-1 text-xs text-charcoal-soft">
                        <x-icon :name="$nombre" size="24" class="text-tomato-600" /> {{ $nombre }}
                    </span>
                @endforeach
                <x-icon name="bookmark" size="24" filled />
            </div>
        </section>

        <section>
            <h2 class="text-2xl font-black">Stickers</h2>
            <div class="mt-3 flex flex-wrap gap-4">
                @foreach (['tomate', 'huevo', 'huevo2', 'tallarin', 'panqueque', 'palta', 'pan', 'olla', 'olla2', 'choclo', 'zapallo', 'estrella'] as $i => $tipo)
                    <span class="flex flex-col items-center gap-1 text-xs text-charcoal-soft">
                        <x-sticker :kind="$tipo" :rotate="$i % 2 ? 6 : -6" /> {{ $tipo }}
                    </span>
                @endforeach
                <x-sticker kind="tomate" locked />
                <x-sticker kind="no-existe" />
            </div>
        </section>
    </body>
</html>
