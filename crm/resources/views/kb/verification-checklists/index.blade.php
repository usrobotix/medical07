<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-ys-l font-semibold text-dc leading-tight">Чек-листы верификации</h2>
            @auth
                @if(auth()->user()->hasAnyRole(['admin', 'manager']))
                    <x-dc-button variant="action" size="s" href="{{ route('kb.verification-checklists.create') }}">+ Добавить</x-dc-button>
                @endif
            @endauth
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">

            {{-- Filters --}}
            <x-dc-card padding="md" shadow="card">
                <form method="GET" action="{{ route('kb.verification-checklists.index') }}" class="flex flex-wrap gap-3 items-end">
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
                        <x-dc-button variant="contour" size="s" href="{{ route('kb.verification-checklists.index') }}">Сбросить</x-dc-button>
                    </div>
                </form>
            </x-dc-card>

            {{-- Table --}}
            <x-dc-card padding="none" shadow="card">
                @if($checklists->isEmpty())
                    <div class="p-8 text-center text-dc-secondary text-ys-s">
                        Чек-листы не найдены.
                    </div>
                @else
                    <x-dc-table :headers="['Название', 'Тип партнёра', 'Пунктов', '']">
                        @foreach($checklists as $checklist)
                            <x-dc-table-row href="{{ route('kb.verification-checklists.show', $checklist) }}">
                                <x-dc-table-cell class="font-medium text-dc">{{ $checklist->name }}</x-dc-table-cell>
                                <x-dc-table-cell class="text-dc-secondary">
                                    @php $tl = ['clinic' => 'Клиника', 'translator' => 'Переводчик', 'curator' => 'Куратор']; @endphp
                                    {{ $tl[$checklist->partner_type] ?? ($checklist->partner_type ?? '—') }}
                                </x-dc-table-cell>
                                <x-dc-table-cell class="text-dc-secondary">{{ $checklist->items_count }}</x-dc-table-cell>
                                <x-dc-table-cell class="text-right">
                                    <span class="text-dc-primary text-ys-xs hover:underline">Подробнее</span>
                                </x-dc-table-cell>
                            </x-dc-table-row>
                        @endforeach
                    </x-dc-table>
                    @if($checklists->hasPages())
                        <div class="px-4 py-3" style="border-top:1px solid var(--color-border)">
                            {{ $checklists->links() }}
                        </div>
                    @endif
                @endif
            </x-dc-card>
        </div>
    </div>
</x-app-layout>
