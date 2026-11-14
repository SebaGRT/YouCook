{{-- Versión provisional: sin diseño, solo para comprobar los datos. Emma la reemplaza. --}}
<h1>Recetas</h1>
<p>{{ $recipes->count() }} de {{ $total }} recetas</p>

<form method="GET" action="{{ route('recetas.index') }}">
    <input name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Buscar">
    <button>Buscar</button>
</form>

@forelse ($recipes as $recipe)
    <p>
        <a href="{{ route('recetas.show', $recipe) }}">{{ $recipe->title }}</a>
        · {{ $recipe->category }} · {{ $recipe->minutes }} min · {{ $recipe->difficulty }} · {{ $recipe->cost_label }}
        @if (in_array($recipe->id, $savedIds)) · guardada @endif
        @if (in_array($recipe->id, $cookedIds)) · cocinada @endif
    </p>
@empty
    <p>No encontramos recetas con esos filtros</p>
@endforelse

{{ $recipes->links() }}
