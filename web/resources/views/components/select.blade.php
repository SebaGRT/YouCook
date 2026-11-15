@props(['label', 'name', 'options', 'value' => null])

{{-- Selector redondeado de los filtros. $options es ['valor' => 'Texto']. --}}
<label class="relative flex items-center">
    <span class="sr-only">{{ $label }}</span>
    <select
        name="{{ $name }}"
        {{ $attributes->class('h-11 appearance-none rounded-full border-2 border-cream-300 bg-white pr-10 pl-4 text-sm font-semibold outline-none hover:border-cream-300 focus:border-tomato-500') }}
    >
        @foreach ($options as $optionValue => $optionLabel)
            <option value="{{ $optionValue }}" @selected((string) $value === (string) $optionValue)>{{ $optionLabel }}</option>
        @endforeach
    </select>
    <svg class="pointer-events-none absolute right-4" width="12" height="12" viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m2 4 4 4 4-4" /></svg>
</label>
