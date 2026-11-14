{{-- Versión provisional: sin diseño, solo para poder entrar. Emma la reemplaza. --}}
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Iniciar sesión · {{ config('app.name') }}</title>

        @fonts
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="grid min-h-screen place-items-center p-6">
        <main class="w-full max-w-md rounded-3xl bg-white p-8 shadow-soft">
            <h1 class="text-3xl font-black">Iniciar sesión</h1>

            <form method="POST" action="{{ route('login') }}" class="mt-6 space-y-4">
                @csrf

                <input type="email" name="email" value="{{ old('email') }}" placeholder="Correo" required class="w-full rounded-xl border p-3">
                @error('email') <p class="text-sm text-tomato-600">{{ $message }}</p> @enderror

                <input type="password" name="password" placeholder="Contraseña" required class="w-full rounded-xl border p-3">

                <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="remember"> Recordarme</label>

                <button class="w-full rounded-xl bg-tomato-500 p-3 font-bold text-white">Entrar</button>
            </form>

            <p class="mt-6 text-sm">¿No tienes cuenta? <a href="{{ route('registro') }}" class="font-bold underline">Regístrate</a></p>
        </main>
    </body>
</html>

