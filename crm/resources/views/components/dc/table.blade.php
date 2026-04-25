@props([
    'headers' => [],
])

<div {{ $attributes->merge(['class' => 'w-full overflow-auto rounded-xs']) }}
     style="background-color:var(--color-surface-2)">
    <table class="w-full text-left border-collapse">
        @if (count($headers))
            <thead>
                <tr>
                    @foreach ($headers as $header)
                        <th class="px-4 py-3 text-ys-xs font-medium uppercase tracking-wide"
                            style="color:var(--color-text-secondary);border-bottom:1px solid var(--color-border)">
                            {{ $header }}
                        </th>
                    @endforeach
                </tr>
            </thead>
        @endif
        <tbody>
            {{ $slot }}
        </tbody>
    </table>
</div>
