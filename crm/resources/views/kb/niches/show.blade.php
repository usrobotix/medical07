<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('kb.niches.index') }}" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 text-sm">← Ниши</a>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $niche->name }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Main info --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">Информация о нише</h3>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-3 text-sm">
                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">Код</dt>
                        <dd class="text-gray-900 dark:text-gray-100 font-mono">{{ $niche->code }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">Порядок сортировки</dt>
                        <dd class="text-gray-900 dark:text-gray-100">{{ $niche->sort_order ?? '—' }}</dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="text-gray-500 dark:text-gray-400">Описание</dt>
                        <dd class="text-gray-900 dark:text-gray-100">{{ $niche->description ?? '—' }}</dd>
                    </div>
                </dl>
            </div>

            {{-- Partners --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <h3 class="text-base font-semibold text-gray-800 dark:text-gray-100 mb-3">Партнёры ({{ $niche->partners->count() }})</h3>
                @if($niche->partners->isEmpty())
                    <p class="text-sm text-gray-500 dark:text-gray-400">Партнёры не добавлены.</p>
                @else
                    <ul class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach($niche->partners as $partner)
                            <li class="py-2 flex justify-between items-center">
                                <div>
                                    <span class="text-sm font-medium text-gray-800 dark:text-gray-100">{{ $partner->name }}</span>
                                    @if($partner->country)
                                        <span class="text-xs text-gray-500 dark:text-gray-400 ml-2">{{ $partner->country->name_ru }}</span>
                                    @endif
                                </div>
                                <a href="{{ route('kb.partners.show', $partner) }}" class="text-indigo-600 dark:text-indigo-400 hover:underline text-xs ml-4">Открыть</a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

            {{-- Country Directions --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <h3 class="text-base font-semibold text-gray-800 dark:text-gray-100 mb-3">Направления по странам ({{ $niche->countryDirections->count() }})</h3>
                @if($niche->countryDirections->isEmpty())
                    <p class="text-sm text-gray-500 dark:text-gray-400">Направления не добавлены.</p>
                @else
                    <ul class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach($niche->countryDirections as $direction)
                            <li class="py-2 flex justify-between items-center">
                                <div>
                                    <span class="text-sm font-medium text-gray-800 dark:text-gray-100">{{ $direction->title }}</span>
                                    @if($direction->country)
                                        <span class="text-xs text-gray-500 dark:text-gray-400 ml-2">{{ $direction->country->name_ru }}</span>
                                    @endif
                                </div>
                                <a href="{{ route('kb.country-directions.show', $direction) }}" class="text-indigo-600 dark:text-indigo-400 hover:underline text-xs ml-4">Открыть</a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
