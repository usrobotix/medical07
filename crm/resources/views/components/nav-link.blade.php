@props(['active'])

@php
$classes = ($active ?? false)
    ? 'inline-flex items-center px-1 pt-1 border-b-2 text-ys-s font-medium leading-5 dc-transition focus:outline-none'
        . ' border-dc-blue-100 text-dc'
    : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-ys-s font-medium leading-5 dc-transition focus:outline-none'
        . ' text-dc-secondary hover:text-dc hover:border-dc-gray-30';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
