@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-medium text-ys-s text-dc']) }}>
    {{ $value ?? $slot }}
</label>
