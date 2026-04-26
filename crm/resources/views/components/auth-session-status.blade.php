@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'text-ys-s font-medium text-dc-green-100 p-3 bg-dc-green-20 rounded-2xs']) }}>
        {{ $status }}
    </div>
@endif
