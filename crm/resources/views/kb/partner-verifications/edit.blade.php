<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('kb.partner-verifications.show', $partnerVerification) }}" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 text-sm">← Просмотр</a>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Верификация: {{ $partnerVerification->partner?->name ?? '—' }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Overall status form --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <h3 class="text-base font-semibold text-gray-800 dark:text-gray-100 mb-4">Общий статус верификации</h3>
                <form method="POST" action="{{ route('kb.partner-verifications.update', $partnerVerification) }}" class="space-y-4">
                    @csrf
                    @method('PATCH')
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Статус <span class="text-red-500">*</span></label>
                            <select name="status" required class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                <option value="not_started" @selected($partnerVerification->status === 'not_started')>Не начата</option>
                                <option value="in_progress" @selected($partnerVerification->status === 'in_progress')>В процессе</option>
                                <option value="passed" @selected($partnerVerification->status === 'passed')>Пройдена</option>
                                <option value="failed" @selected($partnerVerification->status === 'failed')>Провалена</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Дата верификации</label>
                            <input type="datetime-local" name="verified_at"
                                value="{{ $partnerVerification->verified_at ? $partnerVerification->verified_at->format('Y-m-d\TH:i') : '' }}"
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Верифицировал</label>
                            <select name="verified_by_user_id" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                <option value="">— не выбрано —</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" @selected($partnerVerification->verified_by_user_id == $user->id)>{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Партнёр</label>
                            <p class="text-sm text-gray-800 dark:text-gray-200 py-2">{{ $partnerVerification->partner?->name ?? '—' }}</p>
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Заметки</label>
                            <textarea name="notes" rows="3"
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">{{ $partnerVerification->notes }}</textarea>
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="px-6 py-2 text-sm bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition font-medium">Сохранить статус</button>
                    </div>
                </form>
            </div>

            {{-- Checklist items --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <h3 class="text-base font-semibold text-gray-800 dark:text-gray-100 mb-4">
                    Пункты проверки ({{ $partnerVerification->items->count() }})
                </h3>
                @if($partnerVerification->items->isEmpty())
                    <p class="text-sm text-gray-500 dark:text-gray-400">Пункты не найдены. Возможно, чек-лист не содержит пунктов.</p>
                @else
                    <form method="POST" action="{{ route('kb.partner-verifications.items.update-bulk', $partnerVerification) }}" class="space-y-3">
                        @csrf
                        @foreach($partnerVerification->items as $item)
                            <input type="hidden" name="items[{{ $loop->index }}][id]" value="{{ $item->id }}">
                            <div class="border border-gray-100 dark:border-gray-700 rounded-lg p-4 space-y-2">
                                <div class="flex items-start gap-3">
                                    <input type="hidden" name="items[{{ $loop->index }}][is_checked]" value="0">
                                    <input type="checkbox" name="items[{{ $loop->index }}][is_checked]" value="1"
                                        @checked($item->is_checked)
                                        id="item_{{ $item->id }}"
                                        class="mt-0.5 rounded border-gray-300 dark:border-gray-600 text-indigo-600 focus:ring-indigo-500">
                                    <label for="item_{{ $item->id }}" class="flex-1 text-sm text-gray-800 dark:text-gray-100 cursor-pointer">
                                        {{ $item->checklistItem?->text ?? '—' }}
                                    </label>
                                    @if($item->is_checked && $item->checked_at)
                                        <span class="text-xs text-green-600 dark:text-green-400 whitespace-nowrap">✓ {{ $item->checked_at->format('d.m.Y') }}</span>
                                    @endif
                                </div>
                                <div class="pl-7">
                                    <input type="text" name="items[{{ $loop->index }}][notes]"
                                        value="{{ $item->notes }}"
                                        placeholder="Заметка к пункту..."
                                        class="w-full text-xs rounded-md border-gray-200 dark:border-gray-700 dark:bg-gray-750 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                            </div>
                        @endforeach
                        <div class="flex justify-end pt-2">
                            <button type="submit" class="px-6 py-2 text-sm bg-green-600 text-white rounded-md hover:bg-green-700 transition font-medium">Сохранить пункты</button>
                        </div>
                    </form>
                @endif
            </div>

            {{-- Danger zone --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <h3 class="text-sm font-semibold text-red-600 dark:text-red-400 mb-3">Опасная зона</h3>
                <form method="POST" action="{{ route('kb.partner-verifications.destroy', $partnerVerification) }}" onsubmit="return confirm('Удалить верификацию?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 text-sm bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-200 rounded-md hover:bg-red-200 dark:hover:bg-red-800 transition">Удалить верификацию</button>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
