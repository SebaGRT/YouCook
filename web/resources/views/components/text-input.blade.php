@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'h-13 rounded-2xl border-2 border-cream-300 bg-white px-4 text-[15px] outline-none transition placeholder:text-[#B3A99D] focus:border-tomato-500 focus:ring-4 focus:ring-tomato-100']) }}>
