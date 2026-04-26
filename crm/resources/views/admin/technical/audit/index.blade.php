<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-dc leading-tight">
            Технический раздел / Журнал событий
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex gap-6">
            {{-- Sidebar --}}
            <div class="w-48 shrink-0">
                <x-dc.card class="p-3">
                    <nav class="space-y-1">
                        <a href="{{ route('admin.technical.backups.index') }}"
                           class="flex items-center gap-2 px-3 py-2 rounded text-sm font-medium text-dc hover:bg-surface-hover dc-transition">
                            💾 Резервные копии
                        </a>
                        <a href="{{ route('admin.technical.schedule.index') }}"
                           class="flex items-center gap-2 px-3 py-2 rounded text-sm font-medium text-dc hover:bg-surface-hover dc-transition">
                            🕐 Расписание
                        </a>
                        <a href="{{ route('admin.technical.audit.index') }}"
                           class="flex items-center gap-2 px-3 py-2 rounded text-sm font-medium bg-surface-hover text-dc-primary">
                            📋 Журнал событий
                        </a>
                        <a href="{{ route('admin.technical.logs.index') }}"
                           class="flex items-center gap-2 px-3 py-2 rounded text-sm font-medium text-dc hover:bg-surface-hover dc-transition">
                            📄 Логи
                        </a>
                    </nav>
                </x-dc.card>
            </div>

            {{-- Main --}}
            <div class="flex-1 space-y-4">
                {{-- Filters --}}
                <x-dc.card>
                    <div class="p-4">
                        <form method="GET" class="flex flex-wrap gap-3 items-end">
                            <div>
                                <label class="block text-xs text-dc-secondary mb-1">От</label>
                                <input type="date" name="date_from" value="{{ request('date_from') }}"
                                       class="bg-surface border border-dc rounded text-dc text-sm px-2 py-1">
                            </div>
                            <div>
                                <label class="block text-xs text-dc-secondary mb-1">До</label>
                                <input type="date" name="date_to" value="{{ request('date_to') }}"
                                       class="bg-surface border border-dc rounded text-dc text-sm px-2 py-1">
                            </div>
                            <div>
                                <label class="block text-xs text-dc-secondary mb-1">Пользователь</label>
                                <select name="user_id"
                                        class="bg-surface border border-dc rounded text-dc text-sm px-2 py-1">
                                    <option value="">Все</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs text-dc-secondary mb-1">Действие</label>
                                <select name="action"
                                        class="bg-surface border border-dc rounded text-dc text-sm px-2 py-1">
                                    <option value="">Все</option>
                                    @foreach($actions as $action)
                                        <option value="{{ $action }}" {{ request('action') === $action ? 'selected' : '' }}>
                                            {{ $action }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs text-dc-secondary mb-1">Тип сущности</label>
                                <select name="entity_type"
                                        class="bg-surface border border-dc rounded text-dc text-sm px-2 py-1">
                                    <option value="">Все</option>
                                    @foreach($entityTypes as $et)
                                        <option value="{{ $et }}" {{ request('entity_type') === $et ? 'selected' : '' }}>
                                            {{ $et }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <x-dc.button type="submit" variant="normal" size="s">Применить</x-dc.button>
                            </div>
                            @if(request()->anyFilled(['date_from','date_to','user_id','action','entity_type']))
                                <div>
                                    <a href="{{ route('admin.technical.audit.index') }}"
                                       class="text-sm text-dc-secondary hover:text-dc dc-transition">Сбросить</a>
                                </div>
                            @endif
                        </form>
                    </div>
                </x-dc.card>

                {{-- Table --}}
                <x-dc.card>
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-dc mb-4">
                            События ({{ $events->total() }})
                        </h3>
                        @if($events->isEmpty())
                            <p class="text-dc-secondary text-sm">Событий не найдено.</p>
                        @else
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm">
                                    <thead>
                                        <tr class="border-b border-dc text-left">
                                            <th class="pb-2 font-medium text-dc-secondary">Дата/время</th>
                                            <th class="pb-2 font-medium text-dc-secondary">Пользователь</th>
                                            <th class="pb-2 font-medium text-dc-secondary">Действие</th>
                                            <th class="pb-2 font-medium text-dc-secondary">Сущность</th>
                                            <th class="pb-2 font-medium text-dc-secondary">IP</th>
                                            <th class="pb-2 font-medium text-dc-secondary">Данные</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-dc">
                                        @foreach($events as $event)
                                            <tr class="hover:bg-surface-hover dc-transition">
                                                <td class="py-2 text-dc whitespace-nowrap">
                                                    {{ $event->created_at->format('d.m.Y H:i:s') }}
                                                </td>
                                                <td class="py-2 text-dc">
                                                    {{ $event->user?->name ?? '—' }}
                                                </td>
                                                <td class="py-2">
                                                    <x-dc.badge color="gray">{{ $event->action }}</x-dc.badge>
                                                </td>
                                                <td class="py-2 text-dc-secondary text-xs">
                                                    @if($event->entity_type)
                                                        {{ $event->entity_type }}
                                                        @if($event->entity_id)
                                                            #{{ $event->entity_id }}
                                                        @endif
                                                    @else
                                                        —
                                                    @endif
                                                </td>
                                                <td class="py-2 text-dc-secondary text-xs">{{ $event->ip ?? '—' }}</td>
                                                <td class="py-2">
                                                    @if($event->payload)
                                                        <details>
                                                            <summary class="cursor-pointer text-dc-primary text-xs hover:underline">
                                                                Показать
                                                            </summary>
                                                            <pre class="mt-1 text-xs bg-surface-hover p-2 rounded overflow-auto max-w-xs">{{ json_encode($event->payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                                        </details>
                                                    @else
                                                        —
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-4">
                                {{ $events->links() }}
                            </div>
                        @endif
                    </div>
                </x-dc.card>
            </div>
        </div>
    </div>
</x-app-layout>
