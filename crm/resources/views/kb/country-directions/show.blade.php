<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <a href="{{ route('kb.country-directions.index') }}" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 text-sm">← Направления по странам</a>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ $countryDirection->title }}
                </h2>
            </div>
            @auth
                @if(auth()->user()->hasAnyRole(['admin', 'manager']))
                    <a href="{{ route('kb.country-directions.edit', $countryDirection) }}" class="px-4 py-1.5 text-sm bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">Редактировать</a>
                @endif
            @endauth
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Main info --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">Основная информация</h3>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-3 text-sm">
                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">Страна</dt>
                        <dd class="text-gray-900 dark:text-gray-100">{{ $countryDirection->country?->name_ru ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">Ниша</dt>
                        <dd class="text-gray-900 dark:text-gray-100">{{ $countryDirection->niche?->name ?? '—' }}</dd>
                    </div>
                </dl>
            </div>

            {{-- What to look for --}}
            @if($countryDirection->what_to_look_for)
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-base font-semibold text-gray-800 dark:text-gray-100 mb-3">Что искать</h3>
                    <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $countryDirection->what_to_look_for }}</p>
                </div>
            @endif

            {{-- Search queries --}}
            @if($countryDirection->search_queries)
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-base font-semibold text-gray-800 dark:text-gray-100 mb-3">Поисковые запросы</h3>
                    <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-wrap font-mono bg-gray-50 dark:bg-gray-900 p-3 rounded-md">{{ $countryDirection->search_queries }}</p>
                </div>
            @endif

            {{-- Notes --}}
            @if($countryDirection->notes)
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-base font-semibold text-gray-800 dark:text-gray-100 mb-3">Заметки</h3>
                    <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $countryDirection->notes }}</p>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
