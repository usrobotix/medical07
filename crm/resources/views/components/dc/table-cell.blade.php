@props([
    'header' => false,
])

@if ($header)
    <th {{ $attributes->merge([
        'class' => 'dc-table-cell px-4 py-3 text-ys-xs font-medium uppercase tracking-wide text-dc-secondary'
    ]) }}
        style="background-color:var(--color-surface);border-bottom:1px solid var(--color-border)">
        {{ $slot }}
    </th>
@else
    <td {{ $attributes->merge([
        'class' => 'dc-table-cell px-4 py-3 text-ys-s'
    ]) }}
        style="background-color:var(--color-surface);border-top:1px solid var(--color-border)">
        {{ $slot }}
    </td>
@endif
