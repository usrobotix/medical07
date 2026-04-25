<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Шаблоны сообщений
            </h2>
            @auth
                @if(auth()->user()->hasAnyRole(['admin', 'manager']))
                    <a href="{{ route('kb.message-templates.create') }}" class="px-4 py-2 text-sm bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition font-medium">+ Добавить</a>
                @endif
            @endauth
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">

            {{-- Filters --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-4">
                <form method="GET" action="{{ route('kb.message-templates.index') }}" class="flex flex-wrap gap-3 items-end">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Канал</label>
                        <select name="channel" class="text-sm rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Все каналы</option>
                            <option value="email" @selected(request('channel') === 'email')>Email</option>
                            <option value="whatsapp" @selected(request('channel') === 'whatsapp')>WhatsApp</option>
                            <option value="telegram" @selected(request('channel') === 'telegram')>Telegram</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Язык</label>
                        <select name="language" class="text-sm rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Все языки</option>
                            <option value="ru" @selected(request('language') === 'ru')>RU</option>
                            <option value="en" @selected(request('language') === 'en')>EN</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Тип партнёра</label>
                        <select name="target_partner_type" class="text-sm rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Все типы</option>
                            <option value="clinic" @selected(request('target_partner_type') === 'clinic')>Клиника</option>
                            <option value="translator" @selected(request('target_partner_type') === 'translator')>Переводчик</option>
                            <option value="curator" @selected(request('target_partner_type') === 'curator')>Куратор</option>
                        </select>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="px-4 py-2 text-sm bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">Применить</button>
                        <a href="{{ route('kb.message-templates.index') }}" class="px-4 py-2 text-sm bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600 transition">Сбросить</a>
                    </div>
                </form>
            </div>

            {{-- Table --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg overflow-hidden">
                @if($templates->isEmpty())
                    <div class="p-8 text-center text-gray-500 dark:text-gray-400">
                        Шаблоны не найдены.
                    </div>
                @else
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300 uppercase text-xs">
                            <tr>
                                <th class="px-4 py-3">Название</th>
                                <th class="px-4 py-3">Канал</th>
                                <th class="px-4 py-3">Язык</th>
                                <th class="px-4 py-3">Для партнёра</th>
                                <th class="px-4 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @foreach($templates as $template)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-750">
                                    <td class="px-4 py-3 font-medium text-gray-900 dark:text-gray-100">{{ $template->title }}</td>
                                    <td class="px-4 py-3 text-gray-600 dark:text-gray-400">
                                        @php
                                            $channelIcons = ['email' => '✉️', 'whatsapp' => '💬', 'telegram' => '📨'];
                                        @endphp
                                        {{ ($channelIcons[$template->channel] ?? '') . ' ' . strtoupper($template->channel) }}
                                    </td>
                                    <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ strtoupper($template->language) }}</td>
                                    <td class="px-4 py-3 text-gray-600 dark:text-gray-400">
                                        @php $tl = ['clinic' => 'Клиника', 'translator' => 'Переводчик', 'curator' => 'Куратор']; @endphp
                                        {{ $tl[$template->target_partner_type] ?? '—' }}
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <a href="{{ route('kb.message-templates.show', $template) }}" class="text-indigo-600 dark:text-indigo-400 hover:underline text-xs">Подробнее</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @if($templates->hasPages())
                        <div class="px-4 py-3 border-t border-gray-100 dark:border-gray-700">
                            {{ $templates->links() }}
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
