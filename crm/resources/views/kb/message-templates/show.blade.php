<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('message-templates.index') }}" class="text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300 text-sm">← Шаблоны</a>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">{{ $messageTemplate->title }}</h2>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <dl class="grid grid-cols-1 sm:grid-cols-3 gap-x-8 gap-y-3 text-sm">
                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">Канал</dt>
                        <dd class="mt-0.5">
                            <span class="inline-block px-2 py-0.5 rounded text-xs font-medium
                                {{ $messageTemplate->channel === 'email' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300' : '' }}
                                {{ $messageTemplate->channel === 'whatsapp' ? 'bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-300' : '' }}
                                {{ $messageTemplate->channel === 'telegram' ? 'bg-sky-100 text-sky-700 dark:bg-sky-900/40 dark:text-sky-300' : '' }}
                            ">{{ $messageTemplate->channel }}</span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">Язык</dt>
                        <dd class="text-gray-800 dark:text-gray-200 mt-0.5 uppercase text-xs font-semibold">{{ $messageTemplate->language }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">Тип партнёра</dt>
                        <dd class="text-gray-800 dark:text-gray-200 mt-0.5">{{ $messageTemplate->target_partner_type ?? 'любой' }}</dd>
                    </div>
                </dl>

                @if($messageTemplate->subject)
                    <div class="mt-4 border-t border-gray-100 dark:border-gray-700 pt-4">
                        <dt class="text-gray-500 dark:text-gray-400 text-sm font-medium">Тема письма</dt>
                        <dd class="text-gray-800 dark:text-gray-200 text-sm mt-1 font-medium">{{ $messageTemplate->subject }}</dd>
                    </div>
                @endif
            </div>

            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <h3 class="font-semibold text-gray-700 dark:text-gray-300 mb-3">Текст сообщения</h3>
                <div class="bg-gray-50 dark:bg-gray-900/50 rounded-lg p-4 text-sm text-gray-800 dark:text-gray-200 whitespace-pre-wrap leading-relaxed font-mono">{{ $messageTemplate->body }}</div>
            </div>

        </div>
    </div>
</x-app-layout>
