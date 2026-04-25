<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('niches.index') }}" class="text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300 text-sm">← Ниши</a>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">{{ $niche->name }}</h2>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <h3 class="font-semibold text-gray-700 dark:text-gray-300 mb-4">Информация о нише</h3>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-3 text-sm">
                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">Код</dt>
                        <dd class="text-gray-800 dark:text-gray-200 mt-0.5 font-mono">{{ $niche->code }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">Порядок</dt>
                        <dd class="text-gray-800 dark:text-gray-200 mt-0.5">{{ $niche->sort_order }}</dd>
                    </div>
                </dl>
                @if($niche->description)
                    <div class="mt-4 border-t border-gray-100 dark:border-gray-700 pt-3">
                        <dt class="text-gray-500 dark:text-gray-400 text-sm">Описание</dt>
                        <dd class="text-gray-800 dark:text-gray-200 text-sm mt-1">{{ $niche->description }}</dd>
                    </div>
                @endif
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                    <h3 class="font-semibold text-gray-700 dark:text-gray-300 mb-3">Партнёры в нише</h3>
                    @if($niche->partners->count())
                        <ul class="space-y-1 text-sm">
                            @foreach($niche->partners as $partner)
                                <li>• <a href="{{ route('partners.show', $partner) }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">{{ $partner->name }}</a>
                                    <span class="text-gray-500 dark:text-gray-400 text-xs ml-1">({{ $partner->type }})</span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-sm text-gray-400 dark:text-gray-500 italic">Партнёры не указаны</p>
                    @endif
                </div>
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                    <h3 class="font-semibold text-gray-700 dark:text-gray-300 mb-3">Направления по странам</h3>
                    @if($niche->countryDirections->count())
                        <ul class="space-y-1 text-sm">
                            @foreach($niche->countryDirections as $dir)
                                <li>• <a href="{{ route('country-directions.show', $dir) }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">{{ $dir->title }}</a>
                                    @if($dir->country)
                                        <span class="text-gray-500 dark:text-gray-400 text-xs ml-1">({{ $dir->country->name_ru }})</span>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-sm text-gray-400 dark:text-gray-500 italic">Направления не добавлены</p>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
