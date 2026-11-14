{{-- Versión provisional: sin diseño, solo para comprobar los datos. Emma la reemplaza. --}}
<p><a href="{{ route('recetas.index') }}">← Volver a recetas</a></p>
<h1>{{ $recipe->title }}</h1>
<p>{{ $recipe->description }}</p>
<p>{{ $recipe->category }} · {{ $recipe->minutes }} min · {{ $recipe->difficulty }} · {{ $recipe->servings }} porciones · {{ $recipe->cost_label }}</p>
<p>{{ $isSaved ? 'Guardada' : 'Sin guardar' }} · {{ $isCooked ? 'Cocinada' : 'Sin cocinar' }}</p>

<form method="POST" action="{{ route('recetas.guardar', $recipe) }}">@csrf <button>Guardar / quitar</button></form>
<form method="POST" action="{{ route('recetas.cocinada', $recipe) }}">@csrf <button>Cocinada / desmarcar</button></form>

@if (session('toast'))
    <p><strong>{{ session('toast')['title'] }}</strong> {{ session('toast')['body'] ?? '' }}</p>
@endif

<h2>Alérgenos</h2>
<p>{{ implode(' · ', $recipe->allergens) }}</p>

<h2>Ingredientes</h2>
<ul>
    @foreach ($recipe->ingredients as $ingredient)
        <li>{{ $ingredient->name }}: {{ $ingredient->quantity }}</li>
    @endforeach
</ul>

<h2>Preparación</h2>
<ol>
    @foreach ($recipe->steps as $step)
        <li>{{ $step }}</li>
    @endforeach
</ol>
