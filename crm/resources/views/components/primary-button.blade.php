<button {{ $attributes->merge(['type' => 'submit', 'class' => 'dc-btn inline-flex items-center justify-center font-medium dc-transition active:scale-[.98] focus:outline-none focus-visible:outline-none select-none cursor-pointer border-0 h-11 px-5 text-ys-m-s rounded-2xs gap-2 bg-dc-blue-100 text-white hover:bg-dc-blue-200 focus-visible:ring-2 focus-visible:ring-dc-yellow-100']) }}>
    {{ $slot }}
</button>
