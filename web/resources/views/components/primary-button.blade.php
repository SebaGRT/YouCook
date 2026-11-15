<button {{ $attributes->merge(['type' => 'submit', 'class' => 'h-13 w-full rounded-2xl bg-tomato-500 px-6 font-display text-[17px] font-extrabold text-white shadow-[inset_0_-4px_0_rgb(0_0_0/0.14)] transition hover:bg-tomato-600 active:translate-y-px']) }}>
    {{ $slot }}
</button>
