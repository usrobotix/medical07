<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('kb.niches.index') }}" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 text-sm">← Ниши</a>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Новая ниша
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('kb.niches.store') }}">
                @csrf
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Название <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Описание</label>
                        <textarea name="description" rows="3"
                            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">{{ old('description') }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Порядок сортировки</label>
                        <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}"
                            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    </div>
                    <div class="flex justify-end gap-3 pt-2">
                        <a href="{{ route('kb.niches.index') }}" class="px-4 py-2 text-sm bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600 transition">Отмена</a>
                        <button type="submit" class="px-6 py-2 text-sm bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition font-medium">Сохранить</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
