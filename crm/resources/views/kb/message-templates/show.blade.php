<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <a href="{{ route('kb.message-templates.index') }}" class="text-dc-secondary hover:text-dc text-ys-s dc-transition">← Шаблоны сообщений</a>
                <h2 class="text-ys-l font-semibold text-dc leading-tight">{{ $messageTemplate->title }}</h2>
            </div>
            @auth
                @if(auth()->user()->hasAnyRole(['admin', 'manager']))
                    <x-dc-button variant="contour" size="s" href="{{ route('kb.message-templates.edit', $messageTemplate) }}">Редактировать</x-dc-button>
                @endif
            @endauth
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <x-dc-card padding="lg" shadow="card">
                <h3 class="text-ys-m-s font-semibold text-dc mb-4">Параметры шаблона</h3>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-3">
                    <div>
                        <dt class="text-ys-xs text-dc-secondary">Канал</dt>
                        <dd class="text-ys-s text-dc mt-0.5">{{ strtoupper($messageTemplate->channel) }}</dd>
                    </div>
                    <div>
                        <dt class="text-ys-xs text-dc-secondary">Язык</dt>
                        <dd class="text-ys-s text-dc mt-0.5">{{ strtoupper($messageTemplate->language) }}</dd>
                    </div>
                    <div>
                        <dt class="text-ys-xs text-dc-secondary">Для типа партнёра</dt>
                        <dd class="text-ys-s text-dc mt-0.5">
                            @php $typeLabels = ['clinic' => 'Клиника', 'translator' => 'Переводчик', 'curator' => 'Куратор']; @endphp
                            {{ $typeLabels[$messageTemplate->target_partner_type] ?? '—' }}
                        </dd>
                    </div>
                    @if($messageTemplate->subject)
                        <div class="sm:col-span-2">
                            <dt class="text-ys-xs text-dc-secondary">Тема письма</dt>
                            <dd class="text-ys-s text-dc mt-0.5 font-medium">{{ $messageTemplate->subject }}</dd>
                        </div>
                    @endif
                </dl>
            </x-dc-card>

            <x-dc-card padding="lg" shadow="card">
                <h3 class="text-ys-s font-semibold text-dc mb-3">Текст сообщения</h3>
                <pre class="text-ys-s text-dc bg-dc-gray-10 p-4 rounded-2xs overflow-x-auto whitespace-pre-wrap font-mono border border-dc-gray-30">{{ $messageTemplate->body }}</pre>
            </x-dc-card>

        </div>
    </div>
</x-app-layout>
