@extends('layouts.app')

@section('titulo', 'En construcción')

@section('contenido')
    <div class="mx-auto max-w-md rounded-3xl bg-white p-8 text-center shadow-soft">
        <div class="mx-auto w-fit"><x-sticker kind="olla" size="96" /></div>
        <h1 class="mt-4 text-3xl font-black">En construcción</h1>
        <p class="mt-2 text-charcoal-soft">Esta pantalla todavía no existe: <code>/{{ request()->path() }}</code></p>

        <form method="POST" action="{{ route('logout') }}" class="mt-6">
            @csrf
            <button class="text-sm font-semibold text-charcoal-soft underline">Cerrar sesión</button>
        </form>
    </div>
@endsection
