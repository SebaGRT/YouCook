@props(['label', 'name', 'icon', 'type' => 'text', 'hint' => null])

{{-- Campo de los formularios de ingreso: ícono, input y, en las contraseñas, el ojo para verla. --}}
<label class="block" x-data="{ ver: false }">
    <span class="mb-2 block text-sm font-semibold">{{ $label }}</span>
    <span class="flex h-13 items-center gap-3 rounded-2xl border-2 bg-white px-4 transition focus-within:border-tomato-500 focus-within:ring-4 focus-within:ring-tomato-100 {{ $errors->has($name) ? 'border-tomato-500' : 'border-cream-300' }}">
        <x-icon :name="$icon" size="19" class="text-charcoal-soft" />
        <input
            name="{{ $name }}"
            @if ($type === 'password')
                :type="ver ? 'text' : 'password'"
            @else
                type="{{ $type }}"
                value="{{ old($name) }}"
            @endif
            required
            {{ $attributes->class('h-12 w-full bg-transparent text-[15px] outline-none placeholder:text-[#B3A99D]') }}
        >
        @if ($type === 'password')
            <button type="button" x-on:click="ver = ! ver" class="text-charcoal-soft hover:text-charcoal" aria-label="Mostrar contraseña">
                <x-icon name="eye" size="19" />
            </button>
        @endif
    </span>
    @if ($hint && ! $errors->has($name))
        <span class="mt-1.5 block text-xs text-charcoal-soft">{{ $hint }}</span>
    @endif
    <x-input-error :messages="$errors->get($name)" class="mt-1.5" />
</label>
