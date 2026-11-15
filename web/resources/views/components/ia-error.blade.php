{{-- Estado "error" de la IA --}}
<div role="alert" class="flex items-start gap-3">
    <span class="grid size-9 shrink-0 place-items-center rounded-full bg-tomato-100 text-tomato-600"><x-icon name="alert" size="18" /></span>
    <div class="flex-1">
        <p class="font-semibold text-charcoal">El asistente no está disponible</p>
        <p class="text-sm text-charcoal-soft">Intenta de nuevo en un momento.</p>
    </div>
    <button type="button" {{ $attributes->class('flex items-center gap-1.5 rounded-full border-2 border-tomato-200 px-3.5 py-1.5 text-sm font-bold text-tomato-600 hover:bg-tomato-50') }}><x-icon name="refresh" size="15" /> Reintentar
    </button>
</div>
