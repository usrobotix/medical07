@props(['active'])

@php
$classes = ($active ?? false)
    ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-dc-blue-100 text-start text-ys-s font-medium text-dc-primary bg-dc-blue-10 dc-transition focus:outline-none'
    : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-ys-s font-medium text-dc-secondary hover:text-dc hover:bg-surface-hover dc-transition focus:outline-none';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
