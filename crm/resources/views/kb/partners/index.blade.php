<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Партнёры
            </h2>
            @auth
                @if(auth()->user()->hasAnyRole(['admin', 'manager']))
                    <a href="{{ route('kb.partners.create') }}" class="px-4 py-2 text-sm bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition font-medium">+ Добавить</a>
                @endif
            @endauth
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">

            {{-- Filters --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-4">
                <form method="GET" action="{{ route('kb.partners.index') }}" class="flex flex-wrap gap-3 items-end">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Страна</label>
                        <select name="country_id" class="text-sm rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Все страны</option>
                            @foreach($countries as $country)
                                <option value="{{ $country->id }}" @selected(request('country_id') == $country->id)>{{ $country->name_ru }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Тип</label>
                        <select name="type" class="text-sm rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Все типы</option>
                            <option value="clinic" @selected(request('type') === 'clinic')>Клиника</option>
                            <option value="translator" @selected(request('type') === 'translator')>Переводчик</option>
                            <option value="curator" @selected(request('type') === 'curator')>Куратор</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Статус</label>
                        <select name="status" class="text-sm rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Все статусы</option>
                            <option value="new" @selected(request('status') === 'new')>Новый</option>
                            <option value="verified" @selected(request('status') === 'verified')>Верифицирован</option>
                            <option value="active" @selected(request('status') === 'active')>Активен</option>
                            <option value="frozen" @selected(request('status') === 'frozen')>Заморожен</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Ниша</label>
                        <select name="niche_id" class="text-sm rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Все ниши</option>
                            @foreach($niches as $niche)
                                <option value="{{ $niche->id }}" @selected(request('niche_id') == $niche->id)>{{ $niche->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="px-4 py-2 text-sm bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">Применить</button>
                        <a href="{{ route('kb.partners.index') }}" class="px-4 py-2 text-sm bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600 transition">Сбросить</a>
                    </div>
                </form>
            </div>

            {{-- Table --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg overflow-hidden">
                @if($partners->isEmpty())
                    <div class="p-8 text-center text-gray-500 dark:text-gray-400">
                        Партнёры не найдены.
                    </div>
                @else
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300 uppercase text-xs">
                            <tr>
                                <th class="px-4 py-3">Название</th>
                                <th class="px-4 py-3">Тип</th>
                                <th class="px-4 py-3">Страна</th>
                                <th class="px-4 py-3">Статус</th>
                                <th class="px-4 py-3">Ниши</th>
                                <th class="px-4 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @foreach($partners as $partner)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-750">
                                    <td class="px-4 py-3 font-medium text-gray-900 dark:text-gray-100">{{ $partner->name }}</td>
                                    <td class="px-4 py-3 text-gray-600 dark:text-gray-400">
                                        @php $tl = ['clinic' => 'Клиника', 'translator' => 'Переводчик', 'curator' => 'Куратор']; @endphp
                                        {{ $tl[$partner->type] ?? $partner->type }}
                                    </td>
                                    <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ $partner->country?->name_ru ?? '—' }}</td>
                                    <td class="px-4 py-3">
                                        @php
                                            $statusColors = [
                                                'new'      => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                                                'verified' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
                                                'active'   => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                                                'frozen'   => 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300',
                                            ];
                                            $statusLabels = ['new' => 'Новый', 'verified' => 'Верифицирован', 'active' => 'Активен', 'frozen' => 'Заморожен'];
                                        @endphp
                                        <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$partner->status] ?? '' }}">
                                            {{ $statusLabels[$partner->status] ?? $partner->status }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-gray-600 dark:text-gray-400">
                                        {{ $partner->niches->pluck('name')->join(', ') ?: '—' }}
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <a href="{{ route('kb.partners.show', $partner) }}" class="text-indigo-600 dark:text-indigo-400 hover:underline text-xs">Подробнее</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @if($partners->hasPages())
                        <div class="px-4 py-3 border-t border-gray-100 dark:border-gray-700">
                            {{ $partners->links() }}
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
