<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-ys-l font-semibold text-dc leading-tight">Ниши</h2>
            @auth
                @if(auth()->user()->hasAnyRole(['admin', 'manager']))
                    <x-dc.button variant="action" size="s" href="{{ route('kb.niches.create') }}">+ Добавить</x-dc.button>
                @endif
            @endauth
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">

            {{-- Filters --}}
            <x-dc.card padding="md" shadow="card">
                <form method="GET" action="{{ route('kb.niches.index') }}" class="flex flex-wrap gap-3 items-end">
                    <div>
                        <label class="block text-ys-xs font-medium text-dc-secondary mb-1">Поиск по названию</label>
                        <input type="text" name="name" value="{{ request('name') }}"
                            class="text-ys-s h-9 px-3 rounded-2xs border dc-transition bg-surface focus:outline-none focus-visible:ring-2 focus-visible:ring-dc-yellow-100"
                            style="border-color:var(--color-border);color:var(--color-text)"
                            placeholder="Название ниши...">
                    </div>
                    <div class="flex gap-2">
                        <x-dc.button type="submit" variant="action" size="s">Применить</x-dc.button>
                        <x-dc.button variant="contour" size="s" href="{{ route('kb.niches.index') }}">Сбросить</x-dc.button>
                    </div>
                </form>
            </x-dc.card>

            {{-- Table --}}
            <x-dc.card padding="none" shadow="card">
                @if($niches->isEmpty())
                    <div class="p-8 text-center text-dc-secondary text-ys-s">
                        Ниши не найдены.
                    </div>
                @else
                    <x-dc.table :headers="['Код', 'Название', 'Описание', 'Партнёров', '']">
                        @foreach($niches as $niche)
                            <x-dc.table-row href="{{ route('kb.niches.show', $niche) }}">
                                <x-dc.table-cell class="font-mono text-dc-secondary text-ys-xs">{{ $niche->code }}</x-dc.table-cell>
                                <x-dc.table-cell class="font-medium text-dc">{{ $niche->name }}</x-dc.table-cell>
                                <x-dc.table-cell class="text-dc-secondary max-w-xs truncate">{{ $niche->description ?? '—' }}</x-dc.table-cell>
                                <x-dc.table-cell class="text-dc-secondary">{{ $niche->partners_count }}</x-dc.table-cell>
                                <x-dc.table-cell class="text-right">
                                    <span class="text-dc-primary text-ys-xs hover:underline">Подробнее</span>
                                </x-dc.table-cell>
                            </x-dc.table-row>
                        @endforeach
                    </x-dc.table>
                    @if($niches->hasPages())
                        <div class="px-4 py-3" style="border-top:1px solid var(--color-border)">
                            {{ $niches->links() }}
                        </div>
                    @endif
                @endif
            </x-dc.card>
        </div>
    </div>
</x-app-layout>
