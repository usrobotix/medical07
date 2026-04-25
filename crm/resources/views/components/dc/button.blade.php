@props([
    'variant' => 'normal',
    'size'    => 's',
    'type'    => 'button',
    'href'    => null,
    'icon'    => null,
    'iconOnly'=> false,
])

@php
// Normal sizes vs icon-only sizes defined separately to avoid fragile string replacement
$sizeClasses = [
    'xxs' => 'h-6 px-[10px] text-ys-xs rounded-3xs gap-1',
    'xs'  => 'h-8 px-3 text-ys-s rounded-xs gap-2',
    's'   => 'h-9 px-[14px] text-ys-s rounded-2xs gap-2',
    'm'   => 'h-11 px-5 text-ys-m-s rounded-2xs gap-2',
    'l'   => 'h-[52px] px-6 text-ys-l rounded-md gap-2',
];

$iconOnlySizeClasses = [
    'xxs' => 'h-6 px-1.5 text-ys-xs rounded-3xs',
    'xs'  => 'h-8 px-2 text-ys-s rounded-xs',
    's'   => 'h-9 px-2 text-ys-s rounded-2xs',
    'm'   => 'h-11 px-3 text-ys-m-s rounded-2xs',
    'l'   => 'h-[52px] px-3 text-ys-l rounded-md',
];

$variantClasses = [
    'action'  => 'bg-dc-blue-100 text-white hover:bg-dc-blue-200 focus-visible:ring-2 focus-visible:ring-dc-yellow-100',
    'normal'  => 'bg-dc-gray-20 text-dc hover:bg-dc-gray-30 focus-visible:ring-2 focus-visible:ring-dc-yellow-100',
    'contour' => 'bg-transparent text-dc shadow-[inset_0_0_0_1px_var(--color-border)] hover:shadow-[inset_0_0_0_1px_var(--color-border-hover)] focus-visible:ring-2 focus-visible:ring-dc-yellow-100',
    'text'    => 'bg-transparent text-dc-primary hover:bg-dc-blue-10 focus-visible:ring-2 focus-visible:ring-dc-yellow-100',
    'link'    => 'bg-transparent text-dc-primary underline hover:text-dc-blue-200 p-0 h-auto focus-visible:ring-2 focus-visible:ring-dc-yellow-100',
    'danger'  => 'bg-dc-red-100 text-white hover:bg-dc-red-60 focus-visible:ring-2 focus-visible:ring-dc-yellow-100',
];

$base = 'dc-btn inline-flex items-center justify-center font-medium dc-transition active:scale-[.98] focus:outline-none focus-visible:outline-none select-none cursor-pointer border-0';

$resolvedSize = $iconOnly
    ? ($iconOnlySizeClasses[$size] ?? $iconOnlySizeClasses['s'])
    : ($sizeClasses[$size] ?? $sizeClasses['s']);

$classes = $base
    . ' ' . $resolvedSize
    . ' ' . ($variantClasses[$variant] ?? $variantClasses['normal']);
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        @if ($icon)
            <span class="inline-flex items-center justify-center w-4 h-4">{!! $icon !!}</span>
        @endif
        @if (!$iconOnly)
            {{ $slot }}
        @endif
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        @if ($icon)
            <span class="inline-flex items-center justify-center w-4 h-4">{!! $icon !!}</span>
        @endif
        @if (!$iconOnly)
            {{ $slot }}
        @endif
    </button>
@endif
