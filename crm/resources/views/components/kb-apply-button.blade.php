<button {{ $attributes->merge(['type' => 'submit', 'class' => 'dc-btn inline-flex items-center justify-center h-9 px-[14px] text-ys-s font-medium rounded-2xs gap-2 bg-dc-blue-100 text-white hover:bg-dc-blue-200 dc-transition focus:outline-none focus-visible:ring-2 focus-visible:ring-dc-yellow-100']) }}>
    {{ $slot->isEmpty() ? 'Применить' : $slot }}
</button>
