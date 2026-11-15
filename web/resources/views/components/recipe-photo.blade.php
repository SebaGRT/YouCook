@props(['recipe', 'sticker' => 120])

{{-- La foto de la receta. Si todavía no tiene, se muestra su sticker sobre un fondo crema. --}}
@if ($recipe->image)
    <img src="{{ $recipe->image }}" alt="{{ $recipe->title }}" {{ $attributes }}>
@else
    <div {{ $attributes->class('grid place-items-center bg-cream-200') }}>
        <x-sticker :kind="$recipe->sticker" :size="$sticker" />
    </div>
@endif
