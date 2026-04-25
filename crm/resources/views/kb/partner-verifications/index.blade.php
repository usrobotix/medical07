<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-ys-l font-semibold text-dc leading-tight">Проверки партнёров</h2>
            @auth
                @if(auth()->user()->hasAnyRole(['admin', 'manager']))
                    <x-dc-button variant="action" size="s" href="{{ route('kb.partner-verifications.create') }}">+ Новая</x-dc-button>
                @endif
            @endauth
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">

            {{-- Filters --}}
            <x-dc-card padding="md" shadow="card">
                <form method="GET" action="{{ route('kb.partner-verifications.index') }}" class="flex flex-wrap gap-3 items-end">
                    <div>
                        <label class="block text-ys-xs font-medium text-dc-secondary mb-1">Статус</label>
                        <select name="status" class="text-ys-s h-9 px-3 rounded-2xs border dc-transition bg-surface focus:outline-none focus-visible:ring-2 focus-visible:ring-dc-yellow-100" style="border-color:var(--color-border);color:var(--color-text)">
                            <option value="">Все статусы</option>
                            <option value="not_started" @selected(request('status') === 'not_started')>Не начата</option>
                            <option value="in_progress" @selected(request('status') === 'in_progress')>В процессе</option>
                            <option value="passed" @selected(request('status') === 'passed')>Пройдена</option>
                            <option value="failed" @selected(request('status') === 'failed')>Провалена</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-ys-xs font-medium text-dc-secondary mb-1">Чек-лист</label>
                        <select name="checklist_id" class="text-ys-s h-9 px-3 rounded-2xs border dc-transition bg-surface focus:outline-none focus-visible:ring-2 focus-visible:ring-dc-yellow-100" style="border-color:var(--color-border);color:var(--color-text)">
                            <option value="">Все чек-листы</option>
                            @foreach($checklists as $checklist)
                                <option value="{{ $checklist->id }}" @selected(request('checklist_id') == $checklist->id)>{{ $checklist->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-ys-xs font-medium text-dc-secondary mb-1">Тип партнёра</label>
                        <select name="partner_type" class="text-ys-s h-9 px-3 rounded-2xs border dc-transition bg-surface focus:outline-none focus-visible:ring-2 focus-visible:ring-dc-yellow-100" style="border-color:var(--color-border);color:var(--color-text)">
                            <option value="">Все типы</option>
                            <option value="clinic" @selected(request('partner_type') === 'clinic')>Клиника</option>
                            <option value="translator" @selected(request('partner_type') === 'translator')>Переводчик</option>
                            <option value="curator" @selected(request('partner_type') === 'curator')>Куратор</option>
                        </select>
                    </div>
                    <div class="flex gap-2">
                        <x-dc-button type="submit" variant="action" size="s">Применить</x-dc-button>
                        <x-dc-button variant="contour" size="s" href="{{ route('kb.partner-verifications.index') }}">Сбросить</x-dc-button>
                    </div>
                </form>
            </x-dc-card>

            {{-- Table --}}
            <x-dc-card padding="none" shadow="card">
                @if($verifications->isEmpty())
                    <div class="p-8 text-center text-dc-secondary text-ys-s">
                        Проверки не найдены.
                    </div>
                @else
                    <x-dc-table :headers="['Партнёр', 'Чек-лист', 'Статус', 'Проверен', '']">
                        @foreach($verifications as $verification)
                            <x-dc-table-row>
                                <x-dc-table-cell class="font-medium text-dc">{{ $verification->partner?->name ?? '—' }}</x-dc-table-cell>
                                <x-dc-table-cell class="text-dc-secondary">{{ $verification->checklist?->name ?? '—' }}</x-dc-table-cell>
                                <x-dc-table-cell>
                                    @php
                                        $vColors = ['not_started' => 'gray', 'in_progress' => 'info', 'passed' => 'success', 'failed' => 'error'];
                                        $vLabels = ['not_started' => 'Не начата', 'in_progress' => 'В процессе', 'passed' => 'Пройдена', 'failed' => 'Провалена'];
                                    @endphp
                                    <x-dc-badge :color="$vColors[$verification->status] ?? 'gray'" size="20">
                                        {{ $vLabels[$verification->status] ?? $verification->status }}
                                    </x-dc-badge>
                                </x-dc-table-cell>
                                <x-dc-table-cell class="text-dc-secondary">
                                    {{ $verification->verified_at?->format('d.m.Y') ?? '—' }}
                                </x-dc-table-cell>
                                <x-dc-table-cell class="text-right space-x-2">
                                    <a href="{{ route('kb.partner-verifications.show', $verification) }}" class="text-dc-primary text-ys-xs hover:underline">Просмотр</a>
                                    @auth
                                        @if(auth()->user()->hasAnyRole(['admin', 'manager']))
                                            <a href="{{ route('kb.partner-verifications.edit', $verification) }}" class="text-dc-secondary text-ys-xs hover:underline">Выполнить</a>
                                        @endif
                                    @endauth
                                </x-dc-table-cell>
                            </x-dc-table-row>
                        @endforeach
                    </x-dc-table>
                    @if($verifications->hasPages())
                        <div class="px-4 py-3" style="border-top:1px solid var(--color-border)">
                            {{ $verifications->links() }}
                        </div>
                    @endif
                @endif
            </x-dc-card>
        </div>
    </div>
</x-app-layout>
