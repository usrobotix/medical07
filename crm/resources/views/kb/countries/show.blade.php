<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <a href="{{ route('kb.countries.index') }}" class="text-dc-secondary hover:text-dc text-ys-s dc-transition">← Страны</a>
                <h2 class="text-ys-l font-semibold text-dc leading-tight">{{ $country->name_ru }} ({{ $country->iso2 }})</h2>
            </div>
            @auth
                @if(auth()->user()->hasAnyRole(['admin', 'manager']))
                    <x-dc.button variant="contour" size="s" href="{{ route('kb.countries.edit', $country) }}">Редактировать</x-dc.button>
                @endif
            @endauth
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <x-dc.card padding="lg" shadow="card">
                <h3 class="text-ys-m-s font-semibold text-dc mb-4">Информация о стране</h3>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-3">
                    <div>
                        <dt class="text-ys-xs text-dc-secondary">ISO код</dt>
                        <dd class="text-ys-s text-dc mt-0.5 font-mono">{{ $country->iso2 }}</dd>
                    </div>
                    <div>
                        <dt class="text-ys-xs text-dc-secondary">Порядок сортировки</dt>
                        <dd class="text-ys-s text-dc mt-0.5">{{ $country->sort_order ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-ys-xs text-dc-secondary">Название (RU)</dt>
                        <dd class="text-ys-s text-dc mt-0.5">{{ $country->name_ru }}</dd>
                    </div>
                    <div>
                        <dt class="text-ys-xs text-dc-secondary">Название (EN)</dt>
                        <dd class="text-ys-s text-dc mt-0.5">{{ $country->name_en }}</dd>
                    </div>
                </dl>
            </x-dc.card>

            <x-dc.card padding="lg" shadow="card">
                <h3 class="text-ys-s font-semibold text-dc mb-3">Направления ({{ $country->directions->count() }})</h3>
                @if($country->directions->isEmpty())
                    <p class="text-ys-s text-dc-secondary">Направления не добавлены.</p>
                @else
                    <ul class="divide-y border-dc">
                        @foreach($country->directions as $direction)
                            <li class="py-3">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="text-ys-s font-medium text-dc">{{ $direction->title }}</p>
                                        @if($direction->niche)
                                            <p class="text-ys-xs text-dc-secondary mt-0.5">Ниша: {{ $direction->niche->name }}</p>
                                        @endif
                                    </div>
                                    <a href="{{ route('kb.country-directions.show', $direction) }}" class="text-dc-primary text-ys-xs hover:underline ml-4 dc-transition">Открыть</a>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </x-dc.card>

            <x-dc.card padding="lg" shadow="card">
                <h3 class="text-ys-s font-semibold text-dc mb-3">Партнёры ({{ $country->partners->count() }})</h3>
                @if($country->partners->isEmpty())
                    <p class="text-ys-s text-dc-secondary">Партнёры не добавлены.</p>
                @else
                    <ul class="divide-y border-dc">
                        @foreach($country->partners as $partner)
                            <li class="py-2 flex justify-between items-center">
                                <span class="text-ys-s text-dc">{{ $partner->name }}</span>
                                <a href="{{ route('kb.partners.show', $partner) }}" class="text-dc-primary text-ys-xs hover:underline ml-4 dc-transition">Открыть</a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </x-dc.card>

        </div>
    </div>
</x-app-layout>
