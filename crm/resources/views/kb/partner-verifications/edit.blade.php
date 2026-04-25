<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('kb.partner-verifications.show', $partnerVerification) }}" class="text-dc-secondary hover:text-dc text-ys-s dc-transition">← Просмотр</a>
            <h2 class="text-ys-l font-semibold text-dc leading-tight">Верификация: {{ $partnerVerification->partner?->name ?? '—' }}</h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <x-dc-card padding="lg" shadow="card">
                <h3 class="text-ys-s font-semibold text-dc mb-4">Общий статус верификации</h3>
                <form method="POST" action="{{ route('kb.partner-verifications.update', $partnerVerification) }}" class="space-y-4">
                    @csrf
                    @method('PATCH')
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-ys-xs font-medium text-dc-secondary mb-1">Статус <span class="text-dc-red-100">*</span></label>
                            <select name="status" required class="block w-full h-9 px-3 text-ys-s rounded-2xs border border-dc-gray-30 bg-surface text-dc dc-transition focus:outline-none focus-visible:ring-2 focus-visible:ring-dc-yellow-100">
                                <option value="not_started" @selected($partnerVerification->status === 'not_started')>Не начата</option>
                                <option value="in_progress" @selected($partnerVerification->status === 'in_progress')>В процессе</option>
                                <option value="passed" @selected($partnerVerification->status === 'passed')>Пройдена</option>
                                <option value="failed" @selected($partnerVerification->status === 'failed')>Провалена</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-ys-xs font-medium text-dc-secondary mb-1">Дата верификации</label>
                            <input type="datetime-local" name="verified_at"
                                value="{{ $partnerVerification->verified_at ? $partnerVerification->verified_at->format('Y-m-d\TH:i') : '' }}"
                                class="block w-full h-9 px-4 text-ys-s rounded-2xs border border-dc-gray-30 bg-surface text-dc dc-transition focus:outline-none focus-visible:ring-2 focus-visible:ring-dc-yellow-100">
                        </div>
                        <div>
                            <label class="block text-ys-xs font-medium text-dc-secondary mb-1">Верифицировал</label>
                            <select name="verified_by_user_id" class="block w-full h-9 px-3 text-ys-s rounded-2xs border border-dc-gray-30 bg-surface text-dc dc-transition focus:outline-none focus-visible:ring-2 focus-visible:ring-dc-yellow-100">
                                <option value="">— не выбрано —</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" @selected($partnerVerification->verified_by_user_id == $user->id)>{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-ys-xs font-medium text-dc-secondary mb-1">Партнёр</label>
                            <p class="text-ys-s text-dc py-2">{{ $partnerVerification->partner?->name ?? '—' }}</p>
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-ys-xs font-medium text-dc-secondary mb-1">Заметки</label>
                            <textarea name="notes" rows="3" class="block w-full p-3 text-ys-s rounded-2xs border border-dc-gray-30 bg-surface text-dc dc-transition focus:outline-none focus-visible:ring-2 focus-visible:ring-dc-yellow-100">{{ $partnerVerification->notes }}</textarea>
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <x-dc-button type="submit" variant="action" size="s">Сохранить статус</x-dc-button>
                    </div>
                </form>
            </x-dc-card>

            <x-dc-card padding="lg" shadow="card">
                <h3 class="text-ys-s font-semibold text-dc mb-4">Пункты проверки ({{ $partnerVerification->items->count() }})</h3>
                @if($partnerVerification->items->isEmpty())
                    <p class="text-ys-s text-dc-secondary">Пункты не найдены. Возможно, чек-лист не содержит пунктов.</p>
                @else
                    <form method="POST" action="{{ route('kb.partner-verifications.items.update-bulk', $partnerVerification) }}" class="space-y-3">
                        @csrf
                        @foreach($partnerVerification->items as $item)
                            <input type="hidden" name="items[{{ $loop->index }}][id]" value="{{ $item->id }}">
                            <div class="border rounded-2xs p-4 space-y-2" style="border-color:var(--color-border)">
                                <div class="flex items-start gap-3">
                                    <input type="hidden" name="items[{{ $loop->index }}][is_checked]" value="0">
                                    <input type="checkbox" name="items[{{ $loop->index }}][is_checked]" value="1"
                                        @checked($item->is_checked)
                                        id="item_{{ $item->id }}"
                                        class="mt-0.5 rounded border-dc-gray-30 text-dc-blue-100 focus:ring-dc-yellow-100">
                                    <label for="item_{{ $item->id }}" class="flex-1 text-ys-s text-dc cursor-pointer">
                                        {{ $item->checklistItem?->text ?? '—' }}
                                    </label>
                                    @if($item->is_checked && $item->checked_at)
                                        <span class="text-ys-xs text-dc-green-100 whitespace-nowrap">✓ {{ $item->checked_at->format('d.m.Y') }}</span>
                                    @endif
                                </div>
                                <div class="pl-7">
                                    <input type="text" name="items[{{ $loop->index }}][notes]"
                                        value="{{ $item->notes }}"
                                        placeholder="Заметка к пункту..."
                                        class="block w-full h-8 px-3 text-ys-xs rounded-xs border border-dc-gray-30 bg-surface text-dc dc-transition focus:outline-none focus-visible:ring-2 focus-visible:ring-dc-yellow-100">
                                </div>
                            </div>
                        @endforeach
                        <div class="flex justify-end pt-2">
                            <x-dc-button type="submit" variant="action" size="s">Сохранить пункты</x-dc-button>
                        </div>
                    </form>
                @endif
            </x-dc-card>

            <x-dc-card padding="lg" shadow="card">
                <h3 class="text-ys-xs font-semibold mb-3" style="color:var(--color-dc-red-100)">Опасная зона</h3>
                <form method="POST" action="{{ route('kb.partner-verifications.destroy', $partnerVerification) }}" onsubmit="return confirm('Удалить верификацию?')">
                    @csrf
                    @method('DELETE')
                    <x-dc-button type="submit" variant="danger" size="s">Удалить верификацию</x-dc-button>
                </form>
            </x-dc-card>

        </div>
    </div>
</x-app-layout>
