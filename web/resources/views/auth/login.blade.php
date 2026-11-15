@extends('layouts.guest')

@section('titulo', 'Iniciar sesión')

@section('contenido')
    <h1 class="text-[34px] leading-tight font-black">¡Hola de nuevo!</h1>
    <p class="mt-2 text-charcoal-soft">Ingresa para ver tus recetas guardadas.</p>

    <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-5">
        @csrf

        <x-auth-field label="Correo" type="email" name="email" icon="mail" placeholder="camila@uni.cl" autofocus autocomplete="username" />
        <x-auth-field label="Contraseña" type="password" name="password" icon="lock" placeholder="••••••••" autocomplete="current-password" />

        <label class="flex items-center gap-2 text-sm">
            <input type="checkbox" name="remember" class="size-4 accent-tomato-500"> Recordarme
        </label>

        <x-primary-button>Iniciar sesión</x-primary-button>
    </form>

    <p class="mt-8 text-center text-[15px] text-charcoal-soft">
        ¿Primera vez en YouCook?
        <a href="{{ route('registro') }}" class="font-bold text-tomato-600 hover:underline">Regístrate gratis</a>
    </p>
@endsection
