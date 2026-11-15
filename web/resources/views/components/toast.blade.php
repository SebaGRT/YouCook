@props(['toast'])

{{-- Aviso que aparece abajo y se va solo a los 3,8 segundos. --}}
<div
    x-data="{ visible: true }"
    x-init="setTimeout(() => visible = false, 3800)"
    x-show="visible"
    x-transition.opacity
    role="status"
    class="rise fixed bottom-24 left-1/2 z-50 flex w-[calc(100%-2rem)] max-w-sm -translate-x-1/2 items-center gap-4 rounded-3xl bg-charcoal p-4 pr-6 text-white shadow-lift md:bottom-8"
>
    @isset($toast['sticker'])
        <div class="pop"><x-sticker :kind="$toast['sticker']" size="64" rotate="-8" /></div>
    @endisset
    <div>
        <p class="font-display text-lg font-black">{{ $toast['title'] }}</p>
        @isset($toast['body'])
            <p class="text-sm text-white/75">{{ $toast['body'] }}</p>
        @endisset
    </div>
</div>
