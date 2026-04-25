<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('kb.verification-checklists.show', $verificationChecklist) }}" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 text-sm">← {{ $verificationChecklist->name }}</a>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Редактировать чек-лист
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Checklist metadata form --}}
            <form method="POST" action="{{ route('kb.verification-checklists.update', $verificationChecklist) }}">
                @csrf
                @method('PATCH')
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6 space-y-4">
                    <h3 class="text-base font-semibold text-gray-800 dark:text-gray-100">Основные параметры</h3>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Название <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $verificationChecklist->name) }}" required
                            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Тип партнёра <span class="text-red-500">*</span></label>
                        <select name="partner_type" required class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            <option value="clinic" @selected(old('partner_type', $verificationChecklist->partner_type) === 'clinic')>Клиника</option>
                            <option value="translator" @selected(old('partner_type', $verificationChecklist->partner_type) === 'translator')>Переводчик</option>
                            <option value="curator" @selected(old('partner_type', $verificationChecklist->partner_type) === 'curator')>Куратор</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Описание</label>
                        <textarea name="description" rows="3"
                            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">{{ old('description', $verificationChecklist->description) }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Порядок сортировки</label>
                        <input type="number" name="sort_order" value="{{ old('sort_order', $verificationChecklist->sort_order) }}"
                            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    </div>
                    <div class="flex justify-between items-center pt-2">
                        <form method="POST" action="{{ route('kb.verification-checklists.destroy', $verificationChecklist) }}" onsubmit="return confirm('Удалить чек-лист? Все пункты будут удалены.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2 text-sm bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-200 rounded-md hover:bg-red-200 dark:hover:bg-red-800 transition">Удалить чек-лист</button>
                        </form>
                        <button type="submit" class="px-6 py-2 text-sm bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition font-medium">Сохранить</button>
                    </div>
                </div>
            </form>

            {{-- Checklist items --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <h3 class="text-base font-semibold text-gray-800 dark:text-gray-100 mb-4">Пункты чек-листа ({{ $verificationChecklist->items->count() }})</h3>

                @if($verificationChecklist->items->isNotEmpty())
                    <ol class="space-y-3 mb-6">
                        @foreach($verificationChecklist->items as $item)
                            <li class="flex items-start gap-3 group">
                                <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300 rounded-full flex items-center justify-center text-xs font-medium mt-1">
                                    {{ $loop->iteration }}
                                </span>
                                <div class="flex-1">
                                    <form method="POST" action="{{ route('kb.verification-checklists.items.update', [$verificationChecklist, $item]) }}" class="flex gap-2 items-center">
                                        @csrf
                                        @method('PATCH')
                                        <input type="text" name="text" value="{{ $item->text }}" required
                                            class="flex-1 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                        <button type="submit" class="px-3 py-1.5 text-xs bg-indigo-100 text-indigo-700 dark:bg-indigo-900 dark:text-indigo-200 rounded hover:bg-indigo-200 dark:hover:bg-indigo-800 transition">Обновить</button>
                                    </form>
                                </div>
                                <form method="POST" action="{{ route('kb.verification-checklists.items.destroy', [$verificationChecklist, $item]) }}" onsubmit="return confirm('Удалить пункт?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-400 hover:text-red-600 dark:text-red-500 dark:hover:text-red-300 mt-1" title="Удалить">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </form>
                            </li>
                        @endforeach
                    </ol>
                @endif

                {{-- Add new item --}}
                <form method="POST" action="{{ route('kb.verification-checklists.items.store', $verificationChecklist) }}" class="flex gap-2 items-center border-t border-gray-100 dark:border-gray-700 pt-4">
                    @csrf
                    <input type="text" name="text" placeholder="Текст нового пункта..." required
                        class="flex-1 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    <button type="submit" class="px-4 py-2 text-sm bg-green-600 text-white rounded-md hover:bg-green-700 transition font-medium whitespace-nowrap">+ Добавить</button>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
