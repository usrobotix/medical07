<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <a href="{{ route('kb.partners.index') }}" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 text-sm">← Партнёры</a>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ $partner->name }}
                </h2>
            </div>
            @auth
                @if(auth()->user()->hasAnyRole(['admin', 'manager']))
                    <a href="{{ route('kb.partners.edit', $partner) }}" class="px-4 py-1.5 text-sm bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">Редактировать</a>
                @endif
            @endauth
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Main info --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">Основная информация</h3>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-3 text-sm">
                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">Слой</dt>
                        <dd class="text-gray-900 dark:text-gray-100">{{ $partner->layer?->name ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">Тип</dt>
                        <dd class="text-gray-900 dark:text-gray-100">
                            @php $typeLabels = ['clinic' => 'Клиника', 'translator' => 'Переводчик', 'curator' => 'Куратор']; @endphp
                            {{ $typeLabels[$partner->type] ?? $partner->type }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">Статус</dt>
                        <dd class="text-gray-900 dark:text-gray-100">
                            @php $statusLabels = ['new' => 'Новый', 'verified' => 'Верифицирован', 'active' => 'Активен', 'frozen' => 'Заморожен']; @endphp
                            {{ $statusLabels[$partner->status] ?? $partner->status }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">Основная страна</dt>
                        <dd class="text-gray-900 dark:text-gray-100">{{ $partner->country?->name_ru ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">Город</dt>
                        <dd class="text-gray-900 dark:text-gray-100">{{ $partner->city ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">Языки</dt>
                        <dd class="text-gray-900 dark:text-gray-100">{{ $partner->languages ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">Инвойс обязателен</dt>
                        <dd class="text-gray-900 dark:text-gray-100">{{ $partner->invoice_required ? 'Да' : 'Нет' }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">Рейтинг верификации</dt>
                        <dd class="text-gray-900 dark:text-gray-100">{{ $partner->verification_score ?? '—' }}</dd>
                    </div>
                </dl>
            </div>

            {{-- Contacts --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">Контакты</h3>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-3 text-sm">
                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">Контактное лицо</dt>
                        <dd class="text-gray-900 dark:text-gray-100">{{ $partner->contact_name ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">Email</dt>
                        <dd class="text-gray-900 dark:text-gray-100">{{ $partner->contact_email ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">Телефон</dt>
                        <dd class="text-gray-900 dark:text-gray-100">{{ $partner->contact_phone ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">WhatsApp</dt>
                        <dd class="text-gray-900 dark:text-gray-100">{{ $partner->contact_whatsapp ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">Telegram</dt>
                        <dd class="text-gray-900 dark:text-gray-100">{{ $partner->contact_telegram ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">Сайт</dt>
                        <dd class="text-gray-900 dark:text-gray-100">
                            @if($partner->website_url)
                                <a href="{{ $partner->website_url }}" target="_blank" class="text-indigo-600 dark:text-indigo-400 hover:underline">{{ $partner->website_url }}</a>
                            @else
                                —
                            @endif
                        </dd>
                    </div>
                </dl>
            </div>

            {{-- SLA --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">SLA и условия</h3>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-3 text-sm">
                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">Ответ (часы)</dt>
                        <dd class="text-gray-900 dark:text-gray-100">{{ $partner->sla_response_hours ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">Результат (дни)</dt>
                        <dd class="text-gray-900 dark:text-gray-100">{{ $partner->sla_result_days ?? '—' }}</dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="text-gray-500 dark:text-gray-400">Ценовые заметки</dt>
                        <dd class="text-gray-900 dark:text-gray-100 whitespace-pre-wrap">{{ $partner->pricing_notes ?? '—' }}</dd>
                    </div>
                </dl>
            </div>

            {{-- Countries & Niches --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-base font-semibold text-gray-800 dark:text-gray-100 mb-3">Страны работы</h3>
                    @if($partner->countries->isEmpty())
                        <p class="text-sm text-gray-500 dark:text-gray-400">—</p>
                    @else
                        <ul class="text-sm space-y-1">
                            @foreach($partner->countries as $country)
                                <li class="text-gray-700 dark:text-gray-300">{{ $country->name_ru }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-base font-semibold text-gray-800 dark:text-gray-100 mb-3">Ниши</h3>
                    @if($partner->niches->isEmpty())
                        <p class="text-sm text-gray-500 dark:text-gray-400">—</p>
                    @else
                        <ul class="text-sm space-y-1">
                            @foreach($partner->niches as $niche)
                                <li class="text-gray-700 dark:text-gray-300">{{ $niche->name }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>

            {{-- Notes --}}
            @if($partner->notes)
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-base font-semibold text-gray-800 dark:text-gray-100 mb-3">Заметки</h3>
                    <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $partner->notes }}</p>
                </div>
            @endif

            {{-- Verifications --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-base font-semibold text-gray-800 dark:text-gray-100">Проверки верификации</h3>
                    @auth
                        @if(auth()->user()->hasAnyRole(['admin', 'manager']))
                            <a href="{{ route('kb.partner-verifications.create', ['partner_id' => $partner->id]) }}" class="px-3 py-1.5 text-xs bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">+ Новая верификация</a>
                        @endif
                    @endauth
                </div>
                @if($partner->verifications->isEmpty())
                    <p class="text-sm text-gray-500 dark:text-gray-400">Проверки отсутствуют.</p>
                @else
                    <ul class="text-sm divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach($partner->verifications as $verification)
                            <li class="py-2 flex justify-between items-center">
                                <div>
                                    <span class="font-medium text-gray-800 dark:text-gray-100">{{ $verification->checklist?->name ?? '—' }}</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    @php
                                        $vColors = [
                                            'not_started' => 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300',
                                            'in_progress' => 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-200',
                                            'passed'      => 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-200',
                                            'failed'      => 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-200',
                                        ];
                                        $vLabels = ['not_started' => 'Не начата', 'in_progress' => 'В процессе', 'passed' => 'Пройдена', 'failed' => 'Провалена'];
                                    @endphp
                                    <span class="px-2 py-0.5 rounded-full text-xs {{ $vColors[$verification->status] ?? '' }}">
                                        {{ $vLabels[$verification->status] ?? $verification->status }}
                                    </span>
                                    <a href="{{ route('kb.partner-verifications.show', $verification) }}" class="text-indigo-600 dark:text-indigo-400 hover:underline text-xs">Открыть</a>
                                    @auth
                                        @if(auth()->user()->hasAnyRole(['admin', 'manager']))
                                            <a href="{{ route('kb.partner-verifications.edit', $verification) }}" class="text-gray-500 dark:text-gray-400 hover:underline text-xs">Выполнить</a>
                                        @endif
                                    @endauth
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
