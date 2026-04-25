<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-ys-l font-semibold text-dc leading-tight">Страны</h2>
            @auth
                @if(auth()->user()->hasAnyRole(['admin', 'manager']))
                    <x-dc.button variant="action" size="s" href="{{ route('kb.countries.create') }}">+ Добавить</x-dc.button>
                @endif
            @endauth
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">

            {{-- Filters --}}
            <x-dc.card padding="md" shadow="card">
                <form method="GET" action="{{ route('kb.countries.index') }}" class="flex flex-wrap gap-3 items-end">
                    <div>
                        <label class="block text-ys-xs font-medium text-dc-secondary mb-1">Поиск по названию</label>
                        <input type="text" name="name" value="{{ request('name') }}"
                            class="text-ys-s h-9 px-3 rounded-2xs border dc-transition bg-surface focus:outline-none focus-visible:ring-2 focus-visible:ring-dc-yellow-100"
                            style="border-color:var(--color-border);color:var(--color-text)"
                            placeholder="Название или ISO...">
                    </div>
                    <div class="flex gap-2">
                        <x-dc.button type="submit" variant="action" size="s">Применить</x-dc.button>
                        <x-dc.button variant="contour" size="s" href="{{ route('kb.countries.index') }}">Сбросить</x-dc.button>
                    </div>
                </form>
            </x-dc.card>

            {{-- Table --}}
            <x-dc.card padding="none" shadow="card">
                @if($countries->isEmpty())
                    <div class="p-8 text-center text-dc-secondary text-ys-s">
                        Страны не найдены.
                    </div>
                @else
                    <x-dc.table :headers="['ISO', 'Название (RU)', 'Название (EN)', 'Направлений', 'Партнёров', '']">
                        @foreach($countries as $country)
                            <x-dc.table-row href="{{ route('kb.countries.show', $country) }}">
                                <x-dc.table-cell class="font-mono text-dc-secondary">{{ $country->iso2 }}</x-dc.table-cell>
                                <x-dc.table-cell class="font-medium text-dc">{{ $country->name_ru }}</x-dc.table-cell>
                                <x-dc.table-cell class="text-dc-secondary">{{ $country->name_en }}</x-dc.table-cell>
                                <x-dc.table-cell class="text-dc-secondary">{{ $country->directions_count }}</x-dc.table-cell>
                                <x-dc.table-cell class="text-dc-secondary">{{ $country->partners_count }}</x-dc.table-cell>
                                <x-dc.table-cell class="text-right">
                                    <span class="text-dc-primary text-ys-xs hover:underline">Подробнее</span>
                                </x-dc.table-cell>
                            </x-dc.table-row>
                        @endforeach
                    </x-dc.table>
                    @if($countries->hasPages())
                        <div class="px-4 py-3" style="border-top:1px solid var(--color-border)">
                            {{ $countries->links() }}
                        </div>
                    @endif
                @endif
            </x-dc.card>
        </div>
    </div>
</x-app-layout>
