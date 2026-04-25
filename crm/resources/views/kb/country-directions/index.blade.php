<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Направления по странам
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">

            {{-- Filters --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-4">
                <form method="GET" action="{{ route('kb.country-directions.index') }}" class="flex flex-wrap gap-3 items-end">
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
                        <a href="{{ route('kb.country-directions.index') }}" class="px-4 py-2 text-sm bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600 transition">Сбросить</a>
                    </div>
                </form>
            </div>

            {{-- Table --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg overflow-hidden">
                @if($directions->isEmpty())
                    <div class="p-8 text-center text-gray-500 dark:text-gray-400">
                        Направления не найдены.
                    </div>
                @else
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300 uppercase text-xs">
                            <tr>
                                <th class="px-4 py-3">Заголовок</th>
                                <th class="px-4 py-3">Страна</th>
                                <th class="px-4 py-3">Ниша</th>
                                <th class="px-4 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @foreach($directions as $direction)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-750">
                                    <td class="px-4 py-3 font-medium text-gray-900 dark:text-gray-100">{{ $direction->title }}</td>
                                    <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ $direction->country?->name_ru ?? '—' }}</td>
                                    <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ $direction->niche?->name ?? '—' }}</td>
                                    <td class="px-4 py-3 text-right">
                                        <a href="{{ route('kb.country-directions.show', $direction) }}" class="text-indigo-600 dark:text-indigo-400 hover:underline text-xs">Подробнее</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @if($directions->hasPages())
                        <div class="px-4 py-3 border-t border-gray-100 dark:border-gray-700">
                            {{ $directions->links() }}
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
