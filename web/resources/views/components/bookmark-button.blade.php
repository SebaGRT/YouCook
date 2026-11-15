@props(['recipe', 'saved' => false, 'size' => 'size-10'])

{{-- Guardar o quitar de guardados. Es un formulario: el cambio lo hace el servidor. --}}
<form method="POST" action="{{ route('recetas.guardar', $recipe) }}" {{ $attributes }}>
    @csrf
    <button
        type="submit"
        aria-pressed="{{ $saved ? 'true' : 'false' }}"
        aria-label="{{ $saved ? 'Quitar de guardados' : 'Guardar receta' }}"
        class="grid {{ $size }} place-items-center rounded-full shadow-soft transition active:scale-90 {{ $saved ? 'bg-tomato-500 text-white' : 'bg-white/95 text-charcoal hover:text-tomato-500' }}"
    >
        <x-icon name="bookmark" size="18" :filled="$saved" />
    </button>
</form>
