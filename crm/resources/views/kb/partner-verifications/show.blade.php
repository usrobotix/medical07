<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <a href="{{ route('kb.partner-verifications.index') }}" class="text-dc-secondary hover:text-dc text-ys-s dc-transition">← Проверки партнёров</a>
                <h2 class="text-ys-l font-semibold text-dc leading-tight">Проверка: {{ $partnerVerification->partner?->name ?? '—' }}</h2>
            </div>
            @auth
                @if(auth()->user()->hasAnyRole(['admin', 'manager']))
                    <x-dc-button variant="action" size="s" href="{{ route('kb.partner-verifications.edit', $partnerVerification) }}">Выполнить проверку</x-dc-button>
                @endif
            @endauth
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <x-dc-card padding="lg" shadow="card">
                <h3 class="text-ys-m-s font-semibold text-dc mb-4">Информация о проверке</h3>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-3">
                    <div>
                        <dt class="text-ys-xs text-dc-secondary">Партнёр</dt>
                        <dd class="text-ys-s mt-0.5">
                            @if($partnerVerification->partner)
                                <a href="{{ route('kb.partners.show', $partnerVerification->partner) }}" class="text-dc-primary hover:underline dc-transition">{{ $partnerVerification->partner->name }}</a>
                            @else
                                <span class="text-dc">—</span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-ys-xs text-dc-secondary">Чек-лист</dt>
                        <dd class="text-ys-s mt-0.5">
                            @if($partnerVerification->checklist)
                                <a href="{{ route('kb.verification-checklists.show', $partnerVerification->checklist) }}" class="text-dc-primary hover:underline dc-transition">{{ $partnerVerification->checklist->name }}</a>
                            @else
                                <span class="text-dc">—</span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-ys-xs text-dc-secondary">Статус</dt>
                        <dd class="mt-0.5">
                            @php
                                $vColors = ['not_started' => 'gray', 'in_progress' => 'info', 'passed' => 'success', 'failed' => 'error'];
                                $vLabels = ['not_started' => 'Не начата', 'in_progress' => 'В процессе', 'passed' => 'Пройдена', 'failed' => 'Провалена'];
                            @endphp
                            <x-dc-badge :color="$vColors[$partnerVerification->status] ?? 'gray'" size="20">
                                {{ $vLabels[$partnerVerification->status] ?? $partnerVerification->status }}
                            </x-dc-badge>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-ys-xs text-dc-secondary">Дата верификации</dt>
                        <dd class="text-ys-s text-dc mt-0.5">{{ $partnerVerification->verified_at?->format('d.m.Y H:i') ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-ys-xs text-dc-secondary">Верифицировал</dt>
                        <dd class="text-ys-s text-dc mt-0.5">{{ $partnerVerification->verifiedBy?->name ?? '—' }}</dd>
                    </div>
                    @if($partnerVerification->notes)
                        <div class="sm:col-span-2">
                            <dt class="text-ys-xs text-dc-secondary">Заметки</dt>
                            <dd class="text-ys-s text-dc mt-0.5 whitespace-pre-wrap">{{ $partnerVerification->notes }}</dd>
                        </div>
                    @endif
                </dl>
            </x-dc-card>

            <x-dc-card padding="lg" shadow="card">
                <h3 class="text-ys-s font-semibold text-dc mb-4">Пункты проверки ({{ $partnerVerification->items->count() }})</h3>
                @if($partnerVerification->items->isEmpty())
                    <p class="text-ys-s text-dc-secondary">Пункты не добавлены.</p>
                @else
                    <ul class="space-y-3">
                        @foreach($partnerVerification->items as $item)
                            <li class="flex items-start gap-3">
                                <span class="flex-shrink-0 mt-0.5">
                                    @if($item->is_checked)
                                        <svg class="w-5 h-5 text-dc-green-100" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                    @else
                                        <svg class="w-5 h-5 text-dc-gray-30" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm0-2a6 6 0 100-12 6 6 0 000 12z" clip-rule="evenodd"/>
                                        </svg>
                                    @endif
                                </span>
                                <div class="flex-1">
                                    <p class="text-ys-s text-dc">{{ $item->checklistItem?->text ?? '—' }}</p>
                                    @if($item->is_checked && $item->checked_at)
                                        <p class="text-ys-xs text-dc-secondary mt-0.5">Отмечено: {{ $item->checked_at->format('d.m.Y H:i') }}</p>
                                    @endif
                                    @if($item->notes)
                                        <p class="text-ys-xs text-dc-secondary mt-0.5 italic">{{ $item->notes }}</p>
                                    @endif
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </x-dc-card>

        </div>
    </div>
</x-app-layout>
