<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <a href="{{ route('kb.countries.index') }}" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 text-sm">← Страны</a>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ $country->name_ru }} ({{ $country->iso2 }})
                </h2>
            </div>
            @auth
                @if(auth()->user()->hasAnyRole(['admin', 'manager']))
                    <a href="{{ route('kb.countries.edit', $country) }}" class="px-4 py-1.5 text-sm bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">Редактировать</a>
                @endif
            @endauth
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Main info --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">Информация о стране</h3>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-3 text-sm">
                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">ISO код</dt>
                        <dd class="text-gray-900 dark:text-gray-100 font-mono">{{ $country->iso2 }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">Порядок сортировки</dt>
                        <dd class="text-gray-900 dark:text-gray-100">{{ $country->sort_order ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">Название (RU)</dt>
                        <dd class="text-gray-900 dark:text-gray-100">{{ $country->name_ru }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">Название (EN)</dt>
                        <dd class="text-gray-900 dark:text-gray-100">{{ $country->name_en }}</dd>
                    </div>
                </dl>
            </div>

            {{-- Directions --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <h3 class="text-base font-semibold text-gray-800 dark:text-gray-100 mb-3">Направления ({{ $country->directions->count() }})</h3>
                @if($country->directions->isEmpty())
                    <p class="text-sm text-gray-500 dark:text-gray-400">Направления не добавлены.</p>
                @else
                    <ul class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach($country->directions as $direction)
                            <li class="py-3">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="text-sm font-medium text-gray-800 dark:text-gray-100">{{ $direction->title }}</p>
                                        @if($direction->niche)
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Ниша: {{ $direction->niche->name }}</p>
                                        @endif
                                    </div>
                                    <a href="{{ route('kb.country-directions.show', $direction) }}" class="text-indigo-600 dark:text-indigo-400 hover:underline text-xs ml-4">Открыть</a>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

            {{-- Partners --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <h3 class="text-base font-semibold text-gray-800 dark:text-gray-100 mb-3">Партнёры ({{ $country->partners->count() }})</h3>
                @if($country->partners->isEmpty())
                    <p class="text-sm text-gray-500 dark:text-gray-400">Партнёры не добавлены.</p>
                @else
                    <ul class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach($country->partners as $partner)
                            <li class="py-2 flex justify-between items-center">
                                <span class="text-sm text-gray-800 dark:text-gray-100">{{ $partner->name }}</span>
                                <a href="{{ route('kb.partners.show', $partner) }}" class="text-indigo-600 dark:text-indigo-400 hover:underline text-xs ml-4">Открыть</a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
