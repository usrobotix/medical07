<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('kb.message-templates.index') }}" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 text-sm">← Шаблоны сообщений</a>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $messageTemplate->title }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Main info --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">Параметры шаблона</h3>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-3 text-sm">
                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">Код</dt>
                        <dd class="text-gray-900 dark:text-gray-100 font-mono">{{ $messageTemplate->code }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">Канал</dt>
                        <dd class="text-gray-900 dark:text-gray-100">{{ strtoupper($messageTemplate->channel) }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">Язык</dt>
                        <dd class="text-gray-900 dark:text-gray-100">{{ strtoupper($messageTemplate->language) }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">Для типа партнёра</dt>
                        <dd class="text-gray-900 dark:text-gray-100">
                            @match($messageTemplate->target_partner_type)
                                'clinic' => 'Клиника',
                                'translator' => 'Переводчик',
                                'curator' => 'Куратор',
                                default => '—',
                            @endmatch
                        </dd>
                    </div>
                    @if($messageTemplate->subject)
                        <div class="sm:col-span-2">
                            <dt class="text-gray-500 dark:text-gray-400">Тема письма</dt>
                            <dd class="text-gray-900 dark:text-gray-100 font-medium">{{ $messageTemplate->subject }}</dd>
                        </div>
                    @endif
                </dl>
            </div>

            {{-- Body --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <h3 class="text-base font-semibold text-gray-800 dark:text-gray-100 mb-3">Текст сообщения</h3>
                <pre class="bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-200 text-sm p-4 rounded-md overflow-x-auto whitespace-pre-wrap font-mono border border-gray-200 dark:border-gray-700">{{ $messageTemplate->body }}</pre>
            </div>

        </div>
    </div>
</x-app-layout>
