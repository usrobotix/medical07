<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-ys-l font-semibold text-dc leading-tight">Направления по странам</h2>
            @auth
                @if(auth()->user()->hasAnyRole(['admin', 'manager']))
                    <x-dc.button variant="action" size="s" href="{{ route('kb.country-directions.create') }}">+ Добавить</x-dc.button>
                @endif
            @endauth
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">

            {{-- Filters --}}
            <x-dc.card padding="md" shadow="card">
                <form method="GET" action="{{ route('kb.country-directions.index') }}" class="flex flex-wrap gap-3 items-end">
                    <div>
                        <label class="block text-ys-xs font-medium text-dc-secondary mb-1">Страна</label>
                        <select name="country_id" class="text-ys-s h-9 px-3 rounded-2xs border dc-transition bg-surface focus:outline-none focus-visible:ring-2 focus-visible:ring-dc-yellow-100" style="border-color:var(--color-border);color:var(--color-text)">
                            <option value="">Все страны</option>
                            @foreach($countries as $country)
                                <option value="{{ $country->id }}" @selected(request('country_id') == $country->id)>{{ $country->name_ru }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-ys-xs font-medium text-dc-secondary mb-1">Ниша</label>
                        <select name="niche_id" class="text-ys-s h-9 px-3 rounded-2xs border dc-transition bg-surface focus:outline-none focus-visible:ring-2 focus-visible:ring-dc-yellow-100" style="border-color:var(--color-border);color:var(--color-text)">
                            <option value="">Все ниши</option>
                            @foreach($niches as $niche)
                                <option value="{{ $niche->id }}" @selected(request('niche_id') == $niche->id)>{{ $niche->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex gap-2">
                        <x-dc.button type="submit" variant="action" size="s">Применить</x-dc.button>
                        <x-dc.button variant="contour" size="s" href="{{ route('kb.country-directions.index') }}">Сбросить</x-dc.button>
                    </div>
                </form>
            </x-dc.card>

            {{-- Table --}}
            <x-dc.card padding="none" shadow="card">
                @if($directions->isEmpty())
                    <div class="p-8 text-center text-dc-secondary text-ys-s">
                        Направления не найдены.
                    </div>
                @else
                    <x-dc.table :headers="['Заголовок', 'Страна', 'Ниша', '']">
                        @foreach($directions as $direction)
                            <x-dc.table-row href="{{ route('kb.country-directions.show', $direction) }}">
                                <x-dc.table-cell class="font-medium text-dc">{{ $direction->title }}</x-dc.table-cell>
                                <x-dc.table-cell class="text-dc-secondary">{{ $direction->country?->name_ru ?? '—' }}</x-dc.table-cell>
                                <x-dc.table-cell class="text-dc-secondary">{{ $direction->niche?->name ?? '—' }}</x-dc.table-cell>
                                <x-dc.table-cell class="text-right">
                                    <span class="text-dc-primary text-ys-xs hover:underline">Подробнее</span>
                                </x-dc.table-cell>
                            </x-dc.table-row>
                        @endforeach
                    </x-dc.table>
                    @if($directions->hasPages())
                        <div class="px-4 py-3" style="border-top:1px solid var(--color-border)">
                            {{ $directions->links() }}
                        </div>
                    @endif
                @endif
            </x-dc.card>
        </div>
    </div>
</x-app-layout>
