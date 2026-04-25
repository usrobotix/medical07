<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-ys-l font-semibold text-dc leading-tight">Шаблоны сообщений</h2>
            @auth
                @if(auth()->user()->hasAnyRole(['admin', 'manager']))
                    <x-dc.button variant="action" size="s" href="{{ route('kb.message-templates.create') }}">+ Добавить</x-dc.button>
                @endif
            @endauth
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">

            {{-- Filters --}}
            <x-dc.card padding="md" shadow="card">
                <form method="GET" action="{{ route('kb.message-templates.index') }}" class="flex flex-wrap gap-3 items-end">
                    <div>
                        <label class="block text-ys-xs font-medium text-dc-secondary mb-1">Канал</label>
                        <select name="channel" class="text-ys-s h-9 px-3 rounded-2xs border dc-transition bg-surface focus:outline-none focus-visible:ring-2 focus-visible:ring-dc-yellow-100" style="border-color:var(--color-border);color:var(--color-text)">
                            <option value="">Все каналы</option>
                            <option value="email" @selected(request('channel') === 'email')>Email</option>
                            <option value="whatsapp" @selected(request('channel') === 'whatsapp')>WhatsApp</option>
                            <option value="telegram" @selected(request('channel') === 'telegram')>Telegram</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-ys-xs font-medium text-dc-secondary mb-1">Язык</label>
                        <select name="language" class="text-ys-s h-9 px-3 rounded-2xs border dc-transition bg-surface focus:outline-none focus-visible:ring-2 focus-visible:ring-dc-yellow-100" style="border-color:var(--color-border);color:var(--color-text)">
                            <option value="">Все языки</option>
                            <option value="ru" @selected(request('language') === 'ru')>RU</option>
                            <option value="en" @selected(request('language') === 'en')>EN</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-ys-xs font-medium text-dc-secondary mb-1">Тип партнёра</label>
                        <select name="target_partner_type" class="text-ys-s h-9 px-3 rounded-2xs border dc-transition bg-surface focus:outline-none focus-visible:ring-2 focus-visible:ring-dc-yellow-100" style="border-color:var(--color-border);color:var(--color-text)">
                            <option value="">Все типы</option>
                            <option value="clinic" @selected(request('target_partner_type') === 'clinic')>Клиника</option>
                            <option value="translator" @selected(request('target_partner_type') === 'translator')>Переводчик</option>
                            <option value="curator" @selected(request('target_partner_type') === 'curator')>Куратор</option>
                        </select>
                    </div>
                    <div class="flex gap-2">
                        <x-dc.button type="submit" variant="action" size="s">Применить</x-dc.button>
                        <x-dc.button variant="contour" size="s" href="{{ route('kb.message-templates.index') }}">Сбросить</x-dc.button>
                    </div>
                </form>
            </x-dc.card>

            {{-- Table --}}
            <x-dc.card padding="none" shadow="card">
                @if($templates->isEmpty())
                    <div class="p-8 text-center text-dc-secondary text-ys-s">
                        Шаблоны не найдены.
                    </div>
                @else
                    <x-dc.table :headers="['Название', 'Канал', 'Язык', 'Для партнёра', '']">
                        @foreach($templates as $template)
                            <x-dc.table-row href="{{ route('kb.message-templates.show', $template) }}">
                                <x-dc.table-cell class="font-medium text-dc">{{ $template->title }}</x-dc.table-cell>
                                <x-dc.table-cell class="text-dc-secondary">
                                    @php $channelIcons = ['email' => '✉️', 'whatsapp' => '💬', 'telegram' => '📨']; @endphp
                                    {{ ($channelIcons[$template->channel] ?? '') . ' ' . strtoupper($template->channel) }}
                                </x-dc.table-cell>
                                <x-dc.table-cell class="text-dc-secondary">{{ strtoupper($template->language) }}</x-dc.table-cell>
                                <x-dc.table-cell class="text-dc-secondary">
                                    @php $tl = ['clinic' => 'Клиника', 'translator' => 'Переводчик', 'curator' => 'Куратор']; @endphp
                                    {{ $tl[$template->target_partner_type] ?? '—' }}
                                </x-dc.table-cell>
                                <x-dc.table-cell class="text-right">
                                    <span class="text-dc-primary text-ys-xs hover:underline">Подробнее</span>
                                </x-dc.table-cell>
                            </x-dc.table-row>
                        @endforeach
                    </x-dc.table>
                    @if($templates->hasPages())
                        <div class="px-4 py-3" style="border-top:1px solid var(--color-border)">
                            {{ $templates->links() }}
                        </div>
                    @endif
                @endif
            </x-dc.card>
        </div>
    </div>
</x-app-layout>
