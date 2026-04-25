<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('partner-verifications.index') }}" class="text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300 text-sm">← Проверки</a>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Проверка: {{ $partnerVerification->partner?->name ?? '#'.$partnerVerification->id }}
            </h2>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-3 text-sm">
                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">Партнёр</dt>
                        <dd class="text-gray-800 dark:text-gray-200 mt-0.5">
                            @if($partnerVerification->partner)
                                <a href="{{ route('partners.show', $partnerVerification->partner) }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">{{ $partnerVerification->partner->name }}</a>
                            @else
                                —
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">Чек-лист</dt>
                        <dd class="text-gray-800 dark:text-gray-200 mt-0.5">
                            @if($partnerVerification->checklist)
                                <a href="{{ route('verification-checklists.show', $partnerVerification->checklist) }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">{{ $partnerVerification->checklist->name }}</a>
                            @else
                                —
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">Статус</dt>
                        <dd class="mt-0.5">
                            <span class="inline-block px-2 py-0.5 rounded text-xs font-medium
                                {{ $partnerVerification->status === 'passed' ? 'bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-300' : '' }}
                                {{ $partnerVerification->status === 'failed' ? 'bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-300' : '' }}
                                {{ $partnerVerification->status === 'in_progress' ? 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/40 dark:text-yellow-300' : '' }}
                                {{ $partnerVerification->status === 'not_started' ? 'bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400' : '' }}
                            ">{{ $partnerVerification->status }}</span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">Дата проверки</dt>
                        <dd class="text-gray-800 dark:text-gray-200 mt-0.5">{{ $partnerVerification->verified_at?->format('d.m.Y H:i') ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">Проверил</dt>
                        <dd class="text-gray-800 dark:text-gray-200 mt-0.5">{{ $partnerVerification->verifiedBy?->name ?? '—' }}</dd>
                    </div>
                </dl>
                @if($partnerVerification->notes)
                    <div class="mt-4 border-t border-gray-100 dark:border-gray-700 pt-3">
                        <dt class="text-gray-500 dark:text-gray-400 text-sm">Заметки</dt>
                        <dd class="text-gray-800 dark:text-gray-200 text-sm mt-1">{{ $partnerVerification->notes }}</dd>
                    </div>
                @endif
            </div>

            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <h3 class="font-semibold text-gray-700 dark:text-gray-300 mb-4">Пункты проверки</h3>
                @if($partnerVerification->items->count())
                    <div class="space-y-2">
                        @foreach($partnerVerification->items as $item)
                            <div class="flex items-start gap-3 text-sm p-2 rounded {{ $item->is_checked ? 'bg-green-50 dark:bg-green-900/20' : 'bg-gray-50 dark:bg-gray-900/30' }}">
                                <span class="shrink-0 mt-0.5 {{ $item->is_checked ? 'text-green-600 dark:text-green-400' : 'text-gray-300 dark:text-gray-600' }}">
                                    @if($item->is_checked)
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                    @else
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke-width="2"/></svg>
                                    @endif
                                </span>
                                <div class="flex-1">
                                    <p class="text-gray-800 dark:text-gray-200">{{ $item->checklistItem?->text ?? '—' }}</p>
                                    @if($item->notes)
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ $item->notes }}</p>
                                    @endif
                                </div>
                                @if($item->checked_at)
                                    <span class="text-xs text-gray-400 dark:text-gray-500 shrink-0">{{ $item->checked_at->format('d.m.Y') }}</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @elseif($partnerVerification->checklist?->items->count())
                    <div class="space-y-2">
                        @foreach($partnerVerification->checklist->items as $item)
                            <div class="flex items-start gap-3 text-sm p-2 rounded bg-gray-50 dark:bg-gray-900/30">
                                <span class="shrink-0 mt-0.5 text-gray-300 dark:text-gray-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke-width="2"/></svg>
                                </span>
                                <p class="text-gray-700 dark:text-gray-300">{{ $item->text }}</p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-gray-400 dark:text-gray-500 italic">Пункты ещё не заполнены</p>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
