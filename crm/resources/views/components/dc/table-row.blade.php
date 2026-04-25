@props([
    'href'     => null,
    'selected' => false,
])

@php
$baseClass = 'dc-table-row cursor-pointer' . ($selected ? ' selected' : '');
@endphp

@if ($href)
    <tr class="{{ $baseClass }}" onclick="window.location='{{ $href }}'" {{ $attributes }}>
        {{ $slot }}
    </tr>
@else
    <tr class="{{ $baseClass }}" {{ $attributes }}>
        {{ $slot }}
    </tr>
@endif
