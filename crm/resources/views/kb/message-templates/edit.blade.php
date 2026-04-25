<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('kb.message-templates.show', $messageTemplate) }}" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 text-sm">← {{ $messageTemplate->title }}</a>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Редактировать шаблон
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('kb.message-templates.update', $messageTemplate) }}">
                @csrf
                @method('PATCH')
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Название <span class="text-red-500">*</span></label>
                        <input type="text" name="title" value="{{ old('title', $messageTemplate->title) }}" required
                            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        @error('title')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Канал <span class="text-red-500">*</span></label>
                            <select name="channel" required class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                <option value="email" @selected(old('channel', $messageTemplate->channel) === 'email')>Email</option>
                                <option value="whatsapp" @selected(old('channel', $messageTemplate->channel) === 'whatsapp')>WhatsApp</option>
                                <option value="telegram" @selected(old('channel', $messageTemplate->channel) === 'telegram')>Telegram</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Язык <span class="text-red-500">*</span></label>
                            <select name="language" required class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                <option value="ru" @selected(old('language', $messageTemplate->language) === 'ru')>RU</option>
                                <option value="en" @selected(old('language', $messageTemplate->language) === 'en')>EN</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Тип партнёра</label>
                            <select name="target_partner_type" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                <option value="">Любой</option>
                                <option value="clinic" @selected(old('target_partner_type', $messageTemplate->target_partner_type) === 'clinic')>Клиника</option>
                                <option value="translator" @selected(old('target_partner_type', $messageTemplate->target_partner_type) === 'translator')>Переводчик</option>
                                <option value="curator" @selected(old('target_partner_type', $messageTemplate->target_partner_type) === 'curator')>Куратор</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Тема письма</label>
                        <input type="text" name="subject" value="{{ old('subject', $messageTemplate->subject) }}"
                            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Текст сообщения <span class="text-red-500">*</span></label>
                        <textarea name="body" rows="8" required
                            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm font-mono">{{ old('body', $messageTemplate->body) }}</textarea>
                        @error('body')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="flex justify-between items-center pt-2">
                        <form method="POST" action="{{ route('kb.message-templates.destroy', $messageTemplate) }}" onsubmit="return confirm('Удалить шаблон?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2 text-sm bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-200 rounded-md hover:bg-red-200 dark:hover:bg-red-800 transition">Удалить</button>
                        </form>
                        <div class="flex gap-3">
                            <a href="{{ route('kb.message-templates.show', $messageTemplate) }}" class="px-4 py-2 text-sm bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600 transition">Отмена</a>
                            <button type="submit" class="px-6 py-2 text-sm bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition font-medium">Сохранить</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
