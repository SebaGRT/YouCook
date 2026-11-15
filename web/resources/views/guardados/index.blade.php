{{-- Versión provisional: sin diseño, solo para comprobar los datos. Emma la reemplaza. --}}
<p><a href="{{ route('recetas.index') }}">← Recetas</a></p>
<h1>Guardados</h1>

@forelse ($recipes as $recipe)
    <p>
        <a href="{{ route('recetas.show', $recipe) }}">{{ $recipe->title }}</a>
        @if (in_array($recipe->id, $cookedIds)) · cocinada @endif
    </p>
@empty
    <p>Aún no guardas recetas</p>
@endforelse