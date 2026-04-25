<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('kb.countries.show', $country) }}" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 text-sm">← {{ $country->name_ru }}</a>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Редактировать страну
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('kb.countries.update', $country) }}">
                @csrf
                @method('PATCH')
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">ISO2 код <span class="text-red-500">*</span></label>
                        <input type="text" name="iso2" value="{{ old('iso2', $country->iso2) }}" required maxlength="2"
                            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm uppercase">
                        @error('iso2')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Название (рус) <span class="text-red-500">*</span></label>
                        <input type="text" name="name_ru" value="{{ old('name_ru', $country->name_ru) }}" required
                            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        @error('name_ru')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Название (eng) <span class="text-red-500">*</span></label>
                        <input type="text" name="name_en" value="{{ old('name_en', $country->name_en) }}" required
                            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        @error('name_en')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Порядок сортировки</label>
                        <input type="number" name="sort_order" value="{{ old('sort_order', $country->sort_order) }}"
                            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    </div>
                    <div class="flex justify-between items-center pt-2">
                        <form method="POST" action="{{ route('kb.countries.destroy', $country) }}" onsubmit="return confirm('Удалить страну?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2 text-sm bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-200 rounded-md hover:bg-red-200 dark:hover:bg-red-800 transition">Удалить</button>
                        </form>
                        <div class="flex gap-3">
                            <a href="{{ route('kb.countries.show', $country) }}" class="px-4 py-2 text-sm bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600 transition">Отмена</a>
                            <button type="submit" class="px-6 py-2 text-sm bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition font-medium">Сохранить</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
