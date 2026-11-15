@props(['value'])

<label {{ $attributes->merge(['class' => 'mb-2 block text-sm font-semibold']) }}>
    {{ $value ?? $slot }}
</label>
