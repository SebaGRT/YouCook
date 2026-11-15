{{-- Versión provisional: sin diseño, solo para comprobar los datos. Emma la reemplaza. --}}
<p><a href="{{ route('recetas.index') }}">← Recetas</a></p>
<h1>{{ $user->name }}</h1>
<p>{{ $user->email }} · {{ $level }}</p>
<p>{{ $cooked }} de {{ $total }} recetas cocinadas ({{ $total > 0 ? round($cooked / $total * 100) : 0 }}%) · {{ $saved }} guardadas</p>

@if (session('toast'))
    <p><strong>{{ session('toast')['title'] }}</strong></p>
@endif

<form method="POST" action="{{ route('perfil.update') }}">
    @csrf
    @method('PATCH')
    <input name="name" value="{{ old('name', $user->name) }}">
    <input name="email" value="{{ old('email', $user->email) }}">
    <button>Guardar</button>
    @foreach ($errors->all() as $error)
        <p>{{ $error }}</p>
    @endforeach
</form>

<h2>Checklist y stickers</h2>
<ul>
    @foreach ($recipes as $recipe)
        @php $ganado = in_array($recipe->id, $cookedIds); @endphp
        <li>{{ $ganado ? '✔' : '·' }} {{ $recipe->title }} — {{ $ganado ? $recipe->sticker_name : '???' }}</li>
    @endforeach
</ul>

<form method="POST" action="{{ route('logout') }}">@csrf <button>Cerrar sesión</button></form>