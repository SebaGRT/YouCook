@extends('layouts.guest')

@section('titulo', 'Crear cuenta')

@section('contenido')
    <h1 class="text-[34px] leading-tight font-black">Crea tu cuenta</h1>
    <p class="mt-2 text-charcoal-soft">Guarda recetas, colecciona stickers y pregúntale todo al asistente.</p>

    <form method="POST" action="{{ route('registro') }}" class="mt-8 space-y-5">
        @csrf

        <x-auth-field label="Nombre" name="name" icon="user" placeholder="Camila Rojas" autofocus autocomplete="name" />
        <x-auth-field label="Correo" type="email" name="email" icon="mail" placeholder="camila@uni.cl" autocomplete="username" />
        <x-auth-field label="Contraseña" type="password" name="password" icon="lock" placeholder="••••••••" hint="Mínimo 8 caracteres." autocomplete="new-password" />

        <x-primary-button>Crear cuenta</x-primary-button>
    </form>

    <p class="mt-8 text-center text-[15px] text-charcoal-soft">
        ¿Ya tienes cuenta?
        <a href="{{ route('login') }}" class="font-bold text-tomato-600 hover:underline">Inicia sesión</a>
    </p>
@endsection
