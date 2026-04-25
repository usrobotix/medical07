<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('country-directions.index') }}" class="text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300 text-sm">← Направления</a>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">{{ $countryDirection->title }}</h2>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-3 text-sm">
                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">Страна</dt>
                        <dd class="text-gray-800 dark:text-gray-200 mt-0.5">
                            @if($countryDirection->country)
                                <a href="{{ route('countries.show', $countryDirection->country) }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">{{ $countryDirection->country->name_ru }}</a>
                            @else
                                —
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">Ниша</dt>
                        <dd class="text-gray-800 dark:text-gray-200 mt-0.5">
                            @if($countryDirection->niche)
                                <a href="{{ route('niches.show', $countryDirection->niche) }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">{{ $countryDirection->niche->name }}</a>
                            @else
                                —
                            @endif
                        </dd>
                    </div>
                </dl>

                @if($countryDirection->what_to_look_for)
                    <div class="mt-4 border-t border-gray-100 dark:border-gray-700 pt-4">
                        <dt class="text-gray-500 dark:text-gray-400 text-sm font-medium">Что искать</dt>
                        <dd class="text-gray-800 dark:text-gray-200 text-sm mt-2 leading-relaxed">{{ $countryDirection->what_to_look_for }}</dd>
                    </div>
                @endif

                @if($countryDirection->search_queries)
                    <div class="mt-4 border-t border-gray-100 dark:border-gray-700 pt-4">
                        <dt class="text-gray-500 dark:text-gray-400 text-sm font-medium">Поисковые запросы</dt>
                        <dd class="text-gray-700 dark:text-gray-300 text-sm mt-2 font-mono whitespace-pre-wrap bg-gray-50 dark:bg-gray-900/50 rounded p-3">{{ $countryDirection->search_queries }}</dd>
                    </div>
                @endif

                @if($countryDirection->notes)
                    <div class="mt-4 border-t border-gray-100 dark:border-gray-700 pt-4">
                        <dt class="text-gray-500 dark:text-gray-400 text-sm font-medium">Заметки</dt>
                        <dd class="text-gray-800 dark:text-gray-200 text-sm mt-2">{{ $countryDirection->notes }}</dd>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
