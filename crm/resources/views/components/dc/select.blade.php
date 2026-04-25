@props([
    'name'        => '',
    'options'     => [],
    'placeholder' => null,
    'selected'    => null,
    'error'       => null,
    'label'       => null,
    'size'        => 's',
])

@php
$sizeClasses = [
    'xs' => 'h-8 px-3 text-ys-s rounded-xs',
    's'  => 'h-9 px-3 text-ys-s rounded-2xs',
    'm'  => 'h-11 px-4 text-ys-m-s rounded-2xs',
];
$sc = $sizeClasses[$size] ?? $sizeClasses['s'];
@endphp

<div {{ $attributes->only('class') }}>
    @if ($label)
        <label for="{{ $name }}" class="block text-ys-xs font-medium text-dc-secondary mb-1">{{ $label }}</label>
    @endif
    <select
        id="{{ $name }}"
        name="{{ $name }}"
        {{ $attributes->except(['class', 'label'])->merge([
            'class' => $sc
                . ' block w-full appearance-none dc-transition bg-surface cursor-pointer'
                . ' focus:outline-none focus-visible:ring-2 focus-visible:ring-dc-yellow-100'
                . ($error
                    ? ' border border-dc-red-100'
                    : ' border border-dc-gray-30')
        ]) }}
    >
        @if ($placeholder)
            <option value="">{{ $placeholder }}</option>
        @endif
        @foreach ($options as $val => $label)
            <option value="{{ $val }}" @selected($selected == $val)>{{ $label }}</option>
        @endforeach
        {{ $slot }}
    </select>
    @if ($error)
        <p class="mt-1 text-ys-xs text-dc-red-100">{{ $error }}</p>
    @endif
</div>
