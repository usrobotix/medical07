@props([
    'color' => 'gray',
    'size'  => '20',
    'dot'   => false,
])

@php
$colorClasses = [
    'gray'    => 'bg-dc-gray-20 text-dc',
    'info'    => 'bg-dc-blue-20 text-dc-blue-200',
    'success' => 'bg-dc-green-20 text-dc-green-100',
    'warning' => 'bg-dc-orange-30 text-dc-orange-100',
    'error'   => 'bg-dc-red-20 text-dc-red-100',
];

$sizeClasses = [
    '16' => 'min-w-[16px] h-4 text-[10px] leading-[15px] rounded-full px-1',
    '20' => 'min-w-[20px] h-5 text-ys-xs rounded-full px-1.5',
    '24' => 'min-w-[24px] h-6 text-ys-xs font-bold rounded-[40px] px-2',
    '32' => 'min-w-[32px] h-8 text-ys-s rounded-[20px] px-3',
];

$dotClass = $dot ? 'w-1.5 h-1.5 rounded-full p-0 min-w-0' : '';
@endphp

<span {{ $attributes->merge([
    'class' => 'inline-flex items-center justify-center font-medium whitespace-nowrap'
        . ' ' . ($colorClasses[$color] ?? $colorClasses['gray'])
        . ' ' . ($sizeClasses[$size] ?? $sizeClasses['20'])
        . ' ' . $dotClass
]) }}>
    @if (!$dot){{ $slot }}@endif
</span>
