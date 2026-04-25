<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <a href="{{ route('kb.niches.index') }}" class="text-dc-secondary hover:text-dc text-ys-s dc-transition">← Ниши</a>
                <h2 class="text-ys-l font-semibold text-dc leading-tight">{{ $niche->name }}</h2>
            </div>
            @auth
                @if(auth()->user()->hasAnyRole(['admin', 'manager']))
                    <x-dc-button variant="contour" size="s" href="{{ route('kb.niches.edit', $niche) }}">Редактировать</x-dc-button>
                @endif
            @endauth
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <x-dc-card padding="lg" shadow="card">
                <h3 class="text-ys-m-s font-semibold text-dc mb-4">Информация о нише</h3>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-3">
                    <div>
                        <dt class="text-ys-xs text-dc-secondary">Код</dt>
                        <dd class="text-ys-s text-dc mt-0.5 font-mono">{{ $niche->code }}</dd>
                    </div>
                    <div>
                        <dt class="text-ys-xs text-dc-secondary">Порядок сортировки</dt>
                        <dd class="text-ys-s text-dc mt-0.5">{{ $niche->sort_order ?? '—' }}</dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="text-ys-xs text-dc-secondary">Описание</dt>
                        <dd class="text-ys-s text-dc mt-0.5">{{ $niche->description ?? '—' }}</dd>
                    </div>
                </dl>
            </x-dc-card>

            <x-dc-card padding="lg" shadow="card">
                <h3 class="text-ys-s font-semibold text-dc mb-3">Партнёры ({{ $niche->partners->count() }})</h3>
                @if($niche->partners->isEmpty())
                    <p class="text-ys-s text-dc-secondary">Партнёры не добавлены.</p>
                @else
                    <ul class="divide-y border-dc">
                        @foreach($niche->partners as $partner)
                            <li class="py-2 flex justify-between items-center">
                                <div>
                                    <span class="text-ys-s font-medium text-dc">{{ $partner->name }}</span>
                                    @if($partner->country)
                                        <span class="text-ys-xs text-dc-secondary ml-2">{{ $partner->country->name_ru }}</span>
                                    @endif
                                </div>
                                <a href="{{ route('kb.partners.show', $partner) }}" class="text-dc-primary text-ys-xs hover:underline ml-4 dc-transition">Открыть</a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </x-dc-card>

            <x-dc-card padding="lg" shadow="card">
                <h3 class="text-ys-s font-semibold text-dc mb-3">Направления по странам ({{ $niche->countryDirections->count() }})</h3>
                @if($niche->countryDirections->isEmpty())
                    <p class="text-ys-s text-dc-secondary">Направления не добавлены.</p>
                @else
                    <ul class="divide-y border-dc">
                        @foreach($niche->countryDirections as $direction)
                            <li class="py-2 flex justify-between items-center">
                                <div>
                                    <span class="text-ys-s font-medium text-dc">{{ $direction->title }}</span>
                                    @if($direction->country)
                                        <span class="text-ys-xs text-dc-secondary ml-2">{{ $direction->country->name_ru }}</span>
                                    @endif
                                </div>
                                <a href="{{ route('kb.country-directions.show', $direction) }}" class="text-dc-primary text-ys-xs hover:underline ml-4 dc-transition">Открыть</a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </x-dc-card>

        </div>
    </div>
</x-app-layout>
