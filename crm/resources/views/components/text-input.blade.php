@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'bg-surface border border-dc rounded-2xs text-dc text-ys-s px-3 py-2 w-full dc-transition focus:outline-none focus:ring-2 focus:ring-dc-blue-100 focus:border-dc-blue-100 disabled:opacity-50 disabled:cursor-not-allowed']) !!}>
