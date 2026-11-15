@props(['level'])

<span class="inline-flex items-center gap-1">
    <x-icon name="flame" size="15" class="{{ $level === 'Fácil' ? 'text-basil-500' : 'text-[#E39A1B]' }}" />
    {{ $level }}
</span>
