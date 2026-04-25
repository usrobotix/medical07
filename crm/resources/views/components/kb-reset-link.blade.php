@props(['href'])
<a href="{{ $href }}" {{ $attributes->except('href')->merge(['class' => 'dc-btn inline-flex items-center justify-center h-9 px-[14px] text-ys-s font-medium rounded-2xs gap-2 bg-transparent text-dc shadow-[inset_0_0_0_1px_var(--color-border)] hover:shadow-[inset_0_0_0_1px_var(--color-border-hover)] dc-transition focus:outline-none focus-visible:ring-2 focus-visible:ring-dc-yellow-100']) }}>
    {{ $slot->isEmpty() ? 'Сбросить' : $slot }}
</a>
