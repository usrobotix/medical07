@props([
    'padding' => 'md',
    'shadow'  => 'card',
])

@php
$paddingClasses = [
    'none' => '',
    'sm'   => 'p-4',
    'md'   => 'p-6',
    'lg'   => 'p-8',
];
$shadowClasses = [
    'none'    => '',
    'card'    => 'shadow-card',
    'card-lg' => 'shadow-card-lg',
];
@endphp

<div {{ $attributes->merge([
    'class' => 'bg-surface rounded-md overflow-hidden'
        . ' ' . ($paddingClasses[$padding] ?? $paddingClasses['md'])
        . ' ' . ($shadowClasses[$shadow] ?? $shadowClasses['card'])
]) }}>
    {{ $slot }}
</div>
