<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <a href="{{ route('kb.partners.index') }}" class="text-dc-secondary hover:text-dc text-ys-s dc-transition">← Партнёры</a>
                <h2 class="text-ys-l font-semibold text-dc leading-tight">{{ $partner->name }}</h2>
            </div>
            @auth
                @if(auth()->user()->hasAnyRole(['admin', 'manager']))
                    <x-dc.button variant="contour" size="s" href="{{ route('kb.partners.edit', $partner) }}">Редактировать</x-dc.button>
                @endif
            @endauth
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Main info --}}
            <x-dc.card padding="lg" shadow="card">
                <h3 class="text-ys-m-s font-semibold text-dc mb-4">Основная информация</h3>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-3">
                    <div>
                        <dt class="text-ys-xs text-dc-secondary">Слой</dt>
                        <dd class="text-ys-s text-dc mt-0.5">{{ $partner->layer?->name ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-ys-xs text-dc-secondary">Тип</dt>
                        <dd class="text-ys-s text-dc mt-0.5">
                            @php $typeLabels = ['clinic' => 'Клиника', 'translator' => 'Переводчик', 'curator' => 'Куратор']; @endphp
                            {{ $typeLabels[$partner->type] ?? $partner->type }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-ys-xs text-dc-secondary">Статус</dt>
                        <dd class="mt-0.5">
                            @php
                                $sc = ['new' => 'info', 'verified' => 'warning', 'active' => 'success', 'frozen' => 'gray'];
                                $sl = ['new' => 'Новый', 'verified' => 'Верифицирован', 'active' => 'Активен', 'frozen' => 'Заморожен'];
                            @endphp
                            <x-dc.badge :color="$sc[$partner->status] ?? 'gray'" size="20">
                                {{ $sl[$partner->status] ?? $partner->status }}
                            </x-dc.badge>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-ys-xs text-dc-secondary">Основная страна</dt>
                        <dd class="text-ys-s text-dc mt-0.5">{{ $partner->country?->name_ru ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-ys-xs text-dc-secondary">Город</dt>
                        <dd class="text-ys-s text-dc mt-0.5">{{ $partner->city ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-ys-xs text-dc-secondary">Языки</dt>
                        <dd class="text-ys-s text-dc mt-0.5">{{ $partner->languages ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-ys-xs text-dc-secondary">Инвойс обязателен</dt>
                        <dd class="text-ys-s text-dc mt-0.5">{{ $partner->invoice_required ? 'Да' : 'Нет' }}</dd>
                    </div>
                    <div>
                        <dt class="text-ys-xs text-dc-secondary">Рейтинг верификации</dt>
                        <dd class="text-ys-s text-dc mt-0.5">{{ $partner->verification_score ?? '—' }}</dd>
                    </div>
                </dl>
            </x-dc.card>

            {{-- Contacts --}}
            <x-dc.card padding="lg" shadow="card">
                <h3 class="text-ys-m-s font-semibold text-dc mb-4">Контакты</h3>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-3">
                    <div>
                        <dt class="text-ys-xs text-dc-secondary">Контактное лицо</dt>
                        <dd class="text-ys-s text-dc mt-0.5">{{ $partner->contact_name ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-ys-xs text-dc-secondary">Email</dt>
                        <dd class="text-ys-s text-dc mt-0.5">{{ $partner->contact_email ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-ys-xs text-dc-secondary">Телефон</dt>
                        <dd class="text-ys-s text-dc mt-0.5">{{ $partner->contact_phone ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-ys-xs text-dc-secondary">WhatsApp</dt>
                        <dd class="text-ys-s text-dc mt-0.5">{{ $partner->contact_whatsapp ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-ys-xs text-dc-secondary">Telegram</dt>
                        <dd class="text-ys-s text-dc mt-0.5">{{ $partner->contact_telegram ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-ys-xs text-dc-secondary">Сайт</dt>
                        <dd class="text-ys-s mt-0.5">
                            @if($partner->website_url)
                                <a href="{{ $partner->website_url }}" target="_blank" class="text-dc-primary hover:underline dc-transition">{{ $partner->website_url }}</a>
                            @else
                                <span class="text-dc">—</span>
                            @endif
                        </dd>
                    </div>
                </dl>
            </x-dc.card>

            {{-- SLA --}}
            <x-dc.card padding="lg" shadow="card">
                <h3 class="text-ys-m-s font-semibold text-dc mb-4">SLA и условия</h3>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-3">
                    <div>
                        <dt class="text-ys-xs text-dc-secondary">Ответ (часы)</dt>
                        <dd class="text-ys-s text-dc mt-0.5">{{ $partner->sla_response_hours ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-ys-xs text-dc-secondary">Результат (дни)</dt>
                        <dd class="text-ys-s text-dc mt-0.5">{{ $partner->sla_result_days ?? '—' }}</dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="text-ys-xs text-dc-secondary">Ценовые заметки</dt>
                        <dd class="text-ys-s text-dc mt-0.5 whitespace-pre-wrap">{{ $partner->pricing_notes ?? '—' }}</dd>
                    </div>
                </dl>
            </x-dc.card>

            {{-- Countries & Niches --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <x-dc.card padding="lg" shadow="card">
                    <h3 class="text-ys-s font-semibold text-dc mb-3">Страны работы</h3>
                    @if($partner->countries->isEmpty())
                        <p class="text-ys-s text-dc-secondary">—</p>
                    @else
                        <ul class="text-ys-s space-y-1">
                            @foreach($partner->countries as $country)
                                <li class="text-dc">{{ $country->name_ru }}</li>
                            @endforeach
                        </ul>
                    @endif
                </x-dc.card>
                <x-dc.card padding="lg" shadow="card">
                    <h3 class="text-ys-s font-semibold text-dc mb-3">Ниши</h3>
                    @if($partner->niches->isEmpty())
                        <p class="text-ys-s text-dc-secondary">—</p>
                    @else
                        <ul class="text-ys-s space-y-1">
                            @foreach($partner->niches as $niche)
                                <li class="text-dc">{{ $niche->name }}</li>
                            @endforeach
                        </ul>
                    @endif
                </x-dc.card>
            </div>

            {{-- Notes --}}
            @if($partner->notes)
                <x-dc.card padding="lg" shadow="card">
                    <h3 class="text-ys-s font-semibold text-dc mb-3">Заметки</h3>
                    <p class="text-ys-s text-dc whitespace-pre-wrap">{{ $partner->notes }}</p>
                </x-dc.card>
            @endif

            {{-- Verifications --}}
            <x-dc.card padding="lg" shadow="card">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-ys-s font-semibold text-dc">Проверки верификации</h3>
                    @auth
                        @if(auth()->user()->hasAnyRole(['admin', 'manager']))
                            <x-dc.button variant="contour" size="xs" href="{{ route('kb.partner-verifications.create', ['partner_id' => $partner->id]) }}">+ Новая верификация</x-dc.button>
                        @endif
                    @endauth
                </div>
                @if($partner->verifications->isEmpty())
                    <p class="text-ys-s text-dc-secondary">Проверки отсутствуют.</p>
                @else
                    <ul class="text-ys-s divide-y" style="border-color:var(--color-border)">
                        @foreach($partner->verifications as $verification)
                            <li class="py-2.5 flex justify-between items-center">
                                <span class="font-medium text-dc">{{ $verification->checklist?->name ?? '—' }}</span>
                                <div class="flex items-center gap-3">
                                    @php
                                        $vColors = ['not_started' => 'gray', 'in_progress' => 'info', 'passed' => 'success', 'failed' => 'error'];
                                        $vLabels = ['not_started' => 'Не начата', 'in_progress' => 'В процессе', 'passed' => 'Пройдена', 'failed' => 'Провалена'];
                                    @endphp
                                    <x-dc.badge :color="$vColors[$verification->status] ?? 'gray'" size="20">
                                        {{ $vLabels[$verification->status] ?? $verification->status }}
                                    </x-dc.badge>
                                    <a href="{{ route('kb.partner-verifications.show', $verification) }}" class="text-dc-primary text-ys-xs hover:underline dc-transition">Открыть</a>
                                    @auth
                                        @if(auth()->user()->hasAnyRole(['admin', 'manager']))
                                            <a href="{{ route('kb.partner-verifications.edit', $verification) }}" class="text-dc-secondary text-ys-xs hover:underline dc-transition">Выполнить</a>
                                        @endif
                                    @endauth
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </x-dc.card>

            {{-- Research Profile --}}
            @if($partner->researchProfile)
                @php $rp = $partner->researchProfile; @endphp
                <x-dc.card padding="lg" shadow="card">
                    <h3 class="text-ys-m-s font-semibold text-dc mb-4">Исследовательские данные</h3>
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-3">
                        @if($partner->address)
                            <div class="sm:col-span-2">
                                <dt class="text-ys-xs text-dc-secondary">Адрес</dt>
                                <dd class="text-ys-s text-dc mt-0.5">{{ $partner->address }}</dd>
                            </div>
                        @endif
                        @if($partner->international_page_url)
                            <div class="sm:col-span-2">
                                <dt class="text-ys-xs text-dc-secondary">Страница для иностранных пациентов</dt>
                                <dd class="text-ys-s mt-0.5">
                                    <a href="{{ $partner->international_page_url }}" target="_blank" class="text-dc-primary hover:underline dc-transition break-all">{{ $partner->international_page_url }}</a>
                                </dd>
                            </div>
                        @endif
                        @if(!empty($rp->accepts_foreigners['status']))
                            <div>
                                <dt class="text-ys-xs text-dc-secondary">Приём иностранцев</dt>
                                <dd class="text-ys-s text-dc mt-0.5">
                                    {{ $rp->accepts_foreigners['status'] }}
                                    @if(!empty($rp->accepts_foreigners['source_url']))
                                        — <a href="{{ $rp->accepts_foreigners['source_url'] }}" target="_blank" class="text-dc-primary hover:underline dc-transition">источник</a>
                                    @endif
                                    @if(!empty($rp->accepts_foreigners['note']))
                                        <span class="text-dc-secondary text-ys-xs block">{{ $rp->accepts_foreigners['note'] }}</span>
                                    @endif
                                </dd>
                            </div>
                        @endif
                        @if(!empty($rp->accepts_russians['status']))
                            <div>
                                <dt class="text-ys-xs text-dc-secondary">Приём пациентов из РФ</dt>
                                <dd class="text-ys-s text-dc mt-0.5">
                                    {{ $rp->accepts_russians['status'] }}
                                    @if(!empty($rp->accepts_russians['source_url']))
                                        — <a href="{{ $rp->accepts_russians['source_url'] }}" target="_blank" class="text-dc-primary hover:underline dc-transition">источник</a>
                                    @endif
                                    @if(!empty($rp->accepts_russians['note']))
                                        <span class="text-dc-secondary text-ys-xs block">{{ $rp->accepts_russians['note'] }}</span>
                                    @endif
                                </dd>
                            </div>
                        @endif
                        @if(!empty($rp->working_hours['clinic']) || !empty($rp->working_hours['international_dept']))
                            <div class="sm:col-span-2">
                                <dt class="text-ys-xs text-dc-secondary">Режим работы</dt>
                                <dd class="text-ys-s text-dc mt-0.5 space-y-0.5">
                                    @if(!empty($rp->working_hours['clinic']))
                                        <div>Клиника: {{ $rp->working_hours['clinic'] }}</div>
                                    @endif
                                    @if(!empty($rp->working_hours['international_dept']))
                                        <div>Международный отдел: {{ $rp->working_hours['international_dept'] }}</div>
                                    @endif
                                </dd>
                            </div>
                        @endif
                        @if(!empty($rp->key_services) && count($rp->key_services) > 0)
                            <div class="sm:col-span-2">
                                <dt class="text-ys-xs text-dc-secondary">Ключевые услуги</dt>
                                <dd class="mt-0.5">
                                    <ul class="text-ys-s text-dc list-disc list-inside space-y-0.5">
                                        @foreach($rp->key_services as $service)
                                            <li>{{ $service }}</li>
                                        @endforeach
                                    </ul>
                                </dd>
                            </div>
                        @endif
                        @if($partner->last_checked_date)
                            <div>
                                <dt class="text-ys-xs text-dc-secondary">Последняя проверка</dt>
                                <dd class="text-ys-s text-dc mt-0.5">{{ $partner->last_checked_date->format('d.m.Y') }}</dd>
                            </div>
                        @endif
                        @if(!empty($rp->sources) && count($rp->sources) > 0)
                            <div class="sm:col-span-2">
                                <dt class="text-ys-xs text-dc-secondary mb-1">Источники</dt>
                                <dd>
                                    <ul class="text-ys-s space-y-1">
                                        @foreach($rp->sources as $source)
                                            <li>
                                                @if(!empty($source['url']))
                                                    <a href="{{ $source['url'] }}" target="_blank" class="text-dc-primary hover:underline dc-transition break-all">{{ $source['url'] }}</a>
                                                @endif
                                                @if(!empty($source['description']))
                                                    <span class="text-dc-secondary"> — {{ $source['description'] }}</span>
                                                @endif
                                                @if(!empty($source['checked_at']))
                                                    <span class="text-dc-secondary text-ys-xs"> ({{ $source['checked_at'] }})</span>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                </dd>
                            </div>
                        @endif
                    </dl>
                    @if($rp->review_md)
                        <div class="mt-4 pt-4" style="border-top: 1px solid var(--color-border)">
                            <h4 class="text-ys-xs text-dc-secondary mb-2">Обзор</h4>
                            <pre class="text-ys-s text-dc whitespace-pre-wrap font-sans">{{ $rp->review_md }}</pre>
                        </div>
                    @endif
                </x-dc.card>
            @endif

        </div>
    </div>
</x-app-layout>
