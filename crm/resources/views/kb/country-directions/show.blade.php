<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <a href="{{ route('kb.country-directions.index') }}" class="text-dc-secondary hover:text-dc text-ys-s dc-transition">← Направления по странам</a>
                <h2 class="text-ys-l font-semibold text-dc leading-tight">{{ $countryDirection->title }}</h2>
            </div>
            @auth
                @if(auth()->user()->hasAnyRole(['admin', 'manager']))
                    <x-dc-button variant="contour" size="s" href="{{ route('kb.country-directions.edit', $countryDirection) }}">Редактировать</x-dc-button>
                @endif
            @endauth
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <x-dc-card padding="lg" shadow="card">
                <h3 class="text-ys-m-s font-semibold text-dc mb-4">Основная информация</h3>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-3">
                    <div>
                        <dt class="text-ys-xs text-dc-secondary">Страна</dt>
                        <dd class="text-ys-s text-dc mt-0.5">{{ $countryDirection->country?->name_ru ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-ys-xs text-dc-secondary">Ниша</dt>
                        <dd class="text-ys-s text-dc mt-0.5">{{ $countryDirection->niche?->name ?? '—' }}</dd>
                    </div>
                </dl>
            </x-dc-card>

            @if($countryDirection->what_to_look_for)
                <x-dc-card padding="lg" shadow="card">
                    <h3 class="text-ys-s font-semibold text-dc mb-3">Что искать</h3>
                    <p class="text-ys-s text-dc whitespace-pre-wrap">{{ $countryDirection->what_to_look_for }}</p>
                </x-dc-card>
            @endif

            @if($countryDirection->search_queries)
                <x-dc-card padding="lg" shadow="card">
                    <h3 class="text-ys-s font-semibold text-dc mb-3">Поисковые запросы</h3>
                    <pre class="text-ys-s text-dc bg-dc-gray-10 p-3 rounded-2xs font-mono whitespace-pre-wrap overflow-x-auto">{{ $countryDirection->search_queries }}</pre>
                </x-dc-card>
            @endif

            @if($countryDirection->notes)
                <x-dc-card padding="lg" shadow="card">
                    <h3 class="text-ys-s font-semibold text-dc mb-3">Заметки</h3>
                    <p class="text-ys-s text-dc whitespace-pre-wrap">{{ $countryDirection->notes }}</p>
                </x-dc-card>
            @endif

        </div>
    </div>
</x-app-layout>
