@props(['messages'])

@if ($messages)
    <ul {{ $attributes->merge(['class' => 'text-ys-xs text-dc-red-100 space-y-1 mt-1']) }}>
        @foreach ((array) $messages as $message)
            <li>{{ $message }}</li>
        @endforeach
    </ul>
@endif
