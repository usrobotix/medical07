<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <a href="{{ route('kb.partner-verifications.index') }}" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 text-sm">← Проверки партнёров</a>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    Проверка: {{ $partnerVerification->partner?->name ?? '—' }}
                </h2>
            </div>
            @auth
                @if(auth()->user()->hasAnyRole(['admin', 'manager']))
                    <a href="{{ route('kb.partner-verifications.edit', $partnerVerification) }}" class="px-4 py-1.5 text-sm bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">Выполнить проверку</a>
                @endif
            @endauth
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Main info --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">Информация о проверке</h3>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-3 text-sm">
                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">Партнёр</dt>
                        <dd class="text-gray-900 dark:text-gray-100">
                            @if($partnerVerification->partner)
                                <a href="{{ route('kb.partners.show', $partnerVerification->partner) }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">{{ $partnerVerification->partner->name }}</a>
                            @else
                                —
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">Чек-лист</dt>
                        <dd class="text-gray-900 dark:text-gray-100">
                            @if($partnerVerification->checklist)
                                <a href="{{ route('kb.verification-checklists.show', $partnerVerification->checklist) }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">{{ $partnerVerification->checklist->name }}</a>
                            @else
                                —
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">Статус</dt>
                        <dd class="text-gray-900 dark:text-gray-100">
                            @php
                                $vLabels = ['not_started' => 'Не начата', 'in_progress' => 'В процессе', 'passed' => 'Пройдена', 'failed' => 'Провалена'];
                            @endphp
                            {{ $vLabels[$partnerVerification->status] ?? $partnerVerification->status }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">Дата верификации</dt>
                        <dd class="text-gray-900 dark:text-gray-100">{{ $partnerVerification->verified_at?->format('d.m.Y H:i') ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">Верифицировал</dt>
                        <dd class="text-gray-900 dark:text-gray-100">{{ $partnerVerification->verifiedBy?->name ?? '—' }}</dd>
                    </div>
                    @if($partnerVerification->notes)
                        <div class="sm:col-span-2">
                            <dt class="text-gray-500 dark:text-gray-400">Заметки</dt>
                            <dd class="text-gray-900 dark:text-gray-100 whitespace-pre-wrap">{{ $partnerVerification->notes }}</dd>
                        </div>
                    @endif
                </dl>
            </div>

            {{-- Checklist Items --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <h3 class="text-base font-semibold text-gray-800 dark:text-gray-100 mb-4">
                    Пункты проверки ({{ $partnerVerification->items->count() }})
                </h3>
                @if($partnerVerification->items->isEmpty())
                    <p class="text-sm text-gray-500 dark:text-gray-400">Пункты не добавлены.</p>
                @else
                    <ul class="space-y-3">
                        @foreach($partnerVerification->items as $item)
                            <li class="flex items-start gap-3">
                                <span class="flex-shrink-0 mt-0.5">
                                    @if($item->is_checked)
                                        <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                    @else
                                        <svg class="w-5 h-5 text-gray-300 dark:text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm0-2a6 6 0 100-12 6 6 0 000 12z" clip-rule="evenodd"/>
                                        </svg>
                                    @endif
                                </span>
                                <div class="flex-1">
                                    <p class="text-sm text-gray-800 dark:text-gray-100">{{ $item->checklistItem?->text ?? '—' }}</p>
                                    @if($item->is_checked && $item->checked_at)
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Отмечено: {{ $item->checked_at->format('d.m.Y H:i') }}</p>
                                    @endif
                                    @if($item->notes)
                                        <p class="text-xs text-gray-600 dark:text-gray-300 mt-0.5 italic">{{ $item->notes }}</p>
                                    @endif
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
