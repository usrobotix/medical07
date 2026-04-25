<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('kb.country-directions.index') }}" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 text-sm">← Направления</a>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Новое направление
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('kb.country-directions.store') }}">
                @csrf
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6 space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Страна <span class="text-red-500">*</span></label>
                            <select name="country_id" required class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                <option value="">— выберите —</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country->id }}" @selected(old('country_id') == $country->id)>{{ $country->name_ru }}</option>
                                @endforeach
                            </select>
                            @error('country_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Ниша <span class="text-red-500">*</span></label>
                            <select name="niche_id" required class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                <option value="">— выберите —</option>
                                @foreach($niches as $niche)
                                    <option value="{{ $niche->id }}" @selected(old('niche_id') == $niche->id)>{{ $niche->name }}</option>
                                @endforeach
                            </select>
                            @error('niche_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Заголовок <span class="text-red-500">*</span></label>
                        <input type="text" name="title" value="{{ old('title') }}" required
                            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        @error('title')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">На что обращать внимание</label>
                        <textarea name="what_to_look_for" rows="4"
                            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">{{ old('what_to_look_for') }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Поисковые запросы</label>
                        <textarea name="search_queries" rows="3"
                            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">{{ old('search_queries') }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Заметки</label>
                        <textarea name="notes" rows="2"
                            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">{{ old('notes') }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Порядок сортировки</label>
                        <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}"
                            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    </div>
                    <div class="flex justify-end gap-3 pt-2">
                        <a href="{{ route('kb.country-directions.index') }}" class="px-4 py-2 text-sm bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600 transition">Отмена</a>
                        <button type="submit" class="px-6 py-2 text-sm bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition font-medium">Сохранить</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
