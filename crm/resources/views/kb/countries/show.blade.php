<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('countries.index') }}" class="text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300 text-sm">← Страны</a>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">{{ $country->name_ru }}</h2>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <h3 class="font-semibold text-gray-700 dark:text-gray-300 mb-4">Основная информация</h3>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-3 text-sm">
                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">ISO-код</dt>
                        <dd class="text-gray-800 dark:text-gray-200 mt-0.5 font-mono">{{ $country->iso2 }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">Название (EN)</dt>
                        <dd class="text-gray-800 dark:text-gray-200 mt-0.5">{{ $country->name_en }}</dd>
                    </div>
                </dl>
            </div>

            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <h3 class="font-semibold text-gray-700 dark:text-gray-300 mb-3">Направления по стране</h3>
                @if($country->directions->count())
                    <div class="space-y-4">
                        @foreach($country->directions as $dir)
                            <div class="border border-gray-100 dark:border-gray-700 rounded-lg p-4">
                                <div class="flex items-center gap-2 mb-2">
                                    <h4 class="font-medium text-gray-800 dark:text-gray-200 text-sm">{{ $dir->title }}</h4>
                                    @if($dir->niche)
                                        <span class="text-xs bg-indigo-100 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-300 px-2 py-0.5 rounded">{{ $dir->niche->name }}</span>
                                    @endif
                                </div>
                                @if($dir->what_to_look_for)
                                    <p class="text-xs text-gray-600 dark:text-gray-400 mb-1"><span class="font-medium">Что искать:</span> {{ $dir->what_to_look_for }}</p>
                                @endif
                                @if($dir->search_queries)
                                    <p class="text-xs text-gray-500 dark:text-gray-500"><span class="font-medium">Запросы:</span> {{ $dir->search_queries }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-gray-400 dark:text-gray-500 italic">Направления не добавлены</p>
                @endif
            </div>

            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <h3 class="font-semibold text-gray-700 dark:text-gray-300 mb-3">Партнёры в стране</h3>
                @if($country->partners->count())
                    <ul class="space-y-1 text-sm">
                        @foreach($country->partners as $partner)
                            <li>• <a href="{{ route('partners.show', $partner) }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">{{ $partner->name }}</a>
                                <span class="text-gray-500 dark:text-gray-400 text-xs ml-1">({{ $partner->type }})</span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-sm text-gray-400 dark:text-gray-500 italic">Партнёры не указаны</p>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
