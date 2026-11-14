{{-- Versión provisional: sin diseño, solo para poder crear una cuenta. Emma la reemplaza. --}}
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Crear cuenta · {{ config('app.name') }}</title>

        @fonts
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="grid min-h-screen place-items-center p-6">
        <main class="w-full max-w-md rounded-3xl bg-white p-8 shadow-soft">
            <h1 class="text-3xl font-black">Crear cuenta</h1>

            <form method="POST" action="{{ route('registro') }}" class="mt-6 space-y-4">
                @csrf

                <input name="name" value="{{ old('name') }}" placeholder="Nombre" required class="w-full rounded-xl border p-3">
                @error('name') <p class="text-sm text-tomato-600">{{ $message }}</p> @enderror

                <input type="email" name="email" value="{{ old('email') }}" placeholder="Correo" required class="w-full rounded-xl border p-3">
                @error('email') <p class="text-sm text-tomato-600">{{ $message }}</p> @enderror

                <input type="password" name="password" placeholder="Contraseña (mínimo 8)" required class="w-full rounded-xl border p-3">
                @error('password') <p class="text-sm text-tomato-600">{{ $message }}</p> @enderror

                <button class="w-full rounded-xl bg-tomato-500 p-3 font-bold text-white">Crear cuenta</button>
            </form>

            <p class="mt-6 text-sm">¿Ya tienes cuenta? <a href="{{ route('login') }}" class="font-bold underline">Inicia sesión</a></p>
        </main>
    </body>
</html>
