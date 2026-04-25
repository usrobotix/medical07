<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-ys-l font-semibold text-dc leading-tight">Партнёры</h2>
            @auth
                @if(auth()->user()->hasAnyRole(['admin', 'manager']))
                    <x-dc-button variant="action" size="s" href="{{ route('kb.partners.create') }}">+ Добавить</x-dc-button>
                @endif
            @endauth
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">

            {{-- Filters --}}
            <x-dc-card padding="md" shadow="card">
                <form method="GET" action="{{ route('kb.partners.index') }}" class="flex flex-wrap gap-3 items-end">
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
                        <label class="block text-ys-xs font-medium text-dc-secondary mb-1">Тип</label>
                        <select name="type" class="text-ys-s h-9 px-3 rounded-2xs border dc-transition bg-surface focus:outline-none focus-visible:ring-2 focus-visible:ring-dc-yellow-100" style="border-color:var(--color-border);color:var(--color-text)">
                            <option value="">Все типы</option>
                            <option value="clinic" @selected(request('type') === 'clinic')>Клиника</option>
                            <option value="translator" @selected(request('type') === 'translator')>Переводчик</option>
                            <option value="curator" @selected(request('type') === 'curator')>Куратор</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-ys-xs font-medium text-dc-secondary mb-1">Статус</label>
                        <select name="status" class="text-ys-s h-9 px-3 rounded-2xs border dc-transition bg-surface focus:outline-none focus-visible:ring-2 focus-visible:ring-dc-yellow-100" style="border-color:var(--color-border);color:var(--color-text)">
                            <option value="">Все статусы</option>
                            <option value="new" @selected(request('status') === 'new')>Новый</option>
                            <option value="verified" @selected(request('status') === 'verified')>Верифицирован</option>
                            <option value="active" @selected(request('status') === 'active')>Активен</option>
                            <option value="frozen" @selected(request('status') === 'frozen')>Заморожен</option>
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
                        <x-dc-button type="submit" variant="action" size="s">Применить</x-dc-button>
                        <x-dc-button variant="contour" size="s" href="{{ route('kb.partners.index') }}">Сбросить</x-dc-button>
                    </div>
                </form>
            </x-dc-card>

            {{-- Table --}}
            <x-dc-card padding="none" shadow="card">
                @if($partners->isEmpty())
                    <div class="p-8 text-center text-dc-secondary text-ys-s">
                        Партнёры не найдены.
                    </div>
                @else
                    <x-dc-table :headers="['Название', 'Тип', 'Страна', 'Статус', 'Ниши', '']">
                        @foreach($partners as $partner)
                            <x-dc-table-row href="{{ route('kb.partners.show', $partner) }}">
                                <x-dc-table-cell class="font-medium text-dc">{{ $partner->name }}</x-dc-table-cell>
                                <x-dc-table-cell class="text-dc-secondary">
                                    @php $tl = ['clinic' => 'Клиника', 'translator' => 'Переводчик', 'curator' => 'Куратор']; @endphp
                                    {{ $tl[$partner->type] ?? $partner->type }}
                                </x-dc-table-cell>
                                <x-dc-table-cell class="text-dc-secondary">{{ $partner->country?->name_ru ?? '—' }}</x-dc-table-cell>
                                <x-dc-table-cell>
                                    @php
                                        $sc = [
                                            'new'      => 'info',
                                            'verified' => 'warning',
                                            'active'   => 'success',
                                            'frozen'   => 'gray',
                                        ];
                                        $sl = ['new' => 'Новый', 'verified' => 'Верифицирован', 'active' => 'Активен', 'frozen' => 'Заморожен'];
                                    @endphp
                                    <x-dc-badge :color="$sc[$partner->status] ?? 'gray'" size="20">
                                        {{ $sl[$partner->status] ?? $partner->status }}
                                    </x-dc-badge>
                                </x-dc-table-cell>
                                <x-dc-table-cell class="text-dc-secondary">
                                    {{ $partner->niches->pluck('name')->join(', ') ?: '—' }}
                                </x-dc-table-cell>
                                <x-dc-table-cell class="text-right">
                                    <span class="text-dc-primary text-ys-xs hover:underline">Подробнее</span>
                                </x-dc-table-cell>
                            </x-dc-table-row>
                        @endforeach
                    </x-dc-table>
                    @if($partners->hasPages())
                        <div class="px-4 py-3" style="border-top:1px solid var(--color-border)">
                            {{ $partners->links() }}
                        </div>
                    @endif
                @endif
            </x-dc-card>
        </div>
    </div>
</x-app-layout>
