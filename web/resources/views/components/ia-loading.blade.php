@props(['label'])

{{-- Estado "cargando" de la IA --}}
<div role="status" class="space-y-3">
    <p class="flex items-center gap-2 text-sm font-semibold text-basil-600">
        <span class="flex gap-1">
            <span class="dot size-1.5 rounded-full bg-basil-500"></span>
            <span class="dot size-1.5 rounded-full bg-basil-500 [animation-delay:.15s]"></span>
            <span class="dot size-1.5 rounded-full bg-basil-500 [animation-delay:.3s]"></span>
        </span>
        {{ $label }}
    </p>
    <div class="skeleton h-3.5 w-11/12 rounded-full"></div>
    <div class="skeleton h-3.5 w-2/3 rounded-full"></div>
</div>
