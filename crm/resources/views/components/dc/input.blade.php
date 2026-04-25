@props([
    'name'        => '',
    'type'        => 'text',
    'placeholder' => '',
    'error'       => null,
    'size'        => 's',
    'label'       => null,
])

@php
$sizeClasses = [
    'xs' => 'h-8 px-3 text-ys-s rounded-xs',
    's'  => 'h-9 px-4 text-ys-s rounded-2xs',
    'm'  => 'h-11 px-5 text-ys-m-s rounded-2xs',
];
$sc = $sizeClasses[$size] ?? $sizeClasses['s'];
@endphp

<div {{ $attributes->only('class') }}>
    @if ($label)
        <label for="{{ $name }}" class="block text-ys-xs font-medium text-dc-secondary mb-1">{{ $label }}</label>
    @endif
    <input
        id="{{ $name }}"
        name="{{ $name }}"
        type="{{ $type }}"
        placeholder="{{ $placeholder }}"
        {{ $attributes->except(['class', 'label'])->merge([
            'class' => $sc
                . ' block w-full dc-transition'
                . ' placeholder:text-dc-gray-500'
                . ' focus:outline-none focus-visible:ring-2 focus-visible:ring-dc-yellow-100'
                . ($error
                    ? ' border border-dc-red-100 bg-surface'
                    : ' border border-dc-gray-30 bg-surface')
        ]) }}
    />
    @if ($error)
        <p class="mt-1 text-ys-xs text-dc-red-100">{{ $error }}</p>
    @endif
</div>
