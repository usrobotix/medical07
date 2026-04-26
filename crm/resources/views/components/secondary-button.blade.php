<button {{ $attributes->merge(['type' => 'button', 'class' => 'dc-btn inline-flex items-center justify-center font-medium dc-transition active:scale-[.98] focus:outline-none focus-visible:outline-none select-none cursor-pointer border-0 h-11 px-5 text-ys-m-s rounded-2xs gap-2 bg-dc-gray-20 text-dc hover:bg-dc-gray-30 focus-visible:ring-2 focus-visible:ring-dc-yellow-100']) }}>
    {{ $slot }}
</button>
