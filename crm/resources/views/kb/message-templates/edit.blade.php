<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('kb.message-templates.show', $messageTemplate) }}" class="text-dc-secondary hover:text-dc text-ys-s dc-transition">← {{ $messageTemplate->title }}</a>
            <h2 class="text-ys-l font-semibold text-dc leading-tight">Редактировать шаблон</h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('kb.message-templates.update', $messageTemplate) }}">
                @csrf
                @method('PATCH')
                <x-dc-card padding="lg" shadow="card">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-ys-xs font-medium text-dc-secondary mb-1">Название <span class="text-dc-red-100">*</span></label>
                            <input type="text" name="title" value="{{ old('title', $messageTemplate->title) }}" required class="block w-full h-9 px-4 text-ys-s rounded-2xs border border-dc-gray-30 bg-surface text-dc dc-transition focus:outline-none focus-visible:ring-2 focus-visible:ring-dc-yellow-100">
                            @error('title')<p class="text-dc-red-100 text-ys-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-ys-xs font-medium text-dc-secondary mb-1">Канал <span class="text-dc-red-100">*</span></label>
                                <select name="channel" required class="block w-full h-9 px-3 text-ys-s rounded-2xs border border-dc-gray-30 bg-surface text-dc dc-transition focus:outline-none focus-visible:ring-2 focus-visible:ring-dc-yellow-100">
                                    <option value="email" @selected(old('channel', $messageTemplate->channel) === 'email')>Email</option>
                                    <option value="whatsapp" @selected(old('channel', $messageTemplate->channel) === 'whatsapp')>WhatsApp</option>
                                    <option value="telegram" @selected(old('channel', $messageTemplate->channel) === 'telegram')>Telegram</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-ys-xs font-medium text-dc-secondary mb-1">Язык <span class="text-dc-red-100">*</span></label>
                                <select name="language" required class="block w-full h-9 px-3 text-ys-s rounded-2xs border border-dc-gray-30 bg-surface text-dc dc-transition focus:outline-none focus-visible:ring-2 focus-visible:ring-dc-yellow-100">
                                    <option value="ru" @selected(old('language', $messageTemplate->language) === 'ru')>RU</option>
                                    <option value="en" @selected(old('language', $messageTemplate->language) === 'en')>EN</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-ys-xs font-medium text-dc-secondary mb-1">Тип партнёра</label>
                                <select name="target_partner_type" class="block w-full h-9 px-3 text-ys-s rounded-2xs border border-dc-gray-30 bg-surface text-dc dc-transition focus:outline-none focus-visible:ring-2 focus-visible:ring-dc-yellow-100">
                                    <option value="">Любой</option>
                                    <option value="clinic" @selected(old('target_partner_type', $messageTemplate->target_partner_type) === 'clinic')>Клиника</option>
                                    <option value="translator" @selected(old('target_partner_type', $messageTemplate->target_partner_type) === 'translator')>Переводчик</option>
                                    <option value="curator" @selected(old('target_partner_type', $messageTemplate->target_partner_type) === 'curator')>Куратор</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="block text-ys-xs font-medium text-dc-secondary mb-1">Тема письма</label>
                            <input type="text" name="subject" value="{{ old('subject', $messageTemplate->subject) }}" class="block w-full h-9 px-4 text-ys-s rounded-2xs border border-dc-gray-30 bg-surface text-dc dc-transition focus:outline-none focus-visible:ring-2 focus-visible:ring-dc-yellow-100">
                        </div>
                        <div>
                            <label class="block text-ys-xs font-medium text-dc-secondary mb-1">Текст сообщения <span class="text-dc-red-100">*</span></label>
                            <textarea name="body" rows="8" required class="block w-full p-3 text-ys-s rounded-2xs border border-dc-gray-30 bg-surface text-dc dc-transition focus:outline-none focus-visible:ring-2 focus-visible:ring-dc-yellow-100 font-mono">{{ old('body', $messageTemplate->body) }}</textarea>
                            @error('body')<p class="text-dc-red-100 text-ys-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div class="flex justify-between items-center pt-2">
                            <form method="POST" action="{{ route('kb.message-templates.destroy', $messageTemplate) }}" onsubmit="return confirm('Удалить шаблон?')">
                                @csrf
                                @method('DELETE')
                                <x-dc-button type="submit" variant="danger" size="s">Удалить</x-dc-button>
                            </form>
                            <div class="flex gap-3">
                                <x-dc-button variant="contour" size="s" href="{{ route('kb.message-templates.show', $messageTemplate) }}">Отмена</x-dc-button>
                                <x-dc-button type="submit" variant="action" size="s">Сохранить</x-dc-button>
                            </div>
                        </div>
                    </div>
                </x-dc-card>
            </form>
        </div>
    </div>
</x-app-layout>
