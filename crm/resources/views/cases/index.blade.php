<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-ys-l font-semibold text-dc leading-tight">Кейсы</h2>
            <div class="flex gap-2">
                <x-dc.button variant="contour" size="s" href="{{ route('cases.board') }}">Канбан</x-dc.button>
                <x-dc.button variant="action" size="s" href="{{ route('cases.create') }}">+ Кейс</x-dc.button>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-dc.card padding="none" shadow="card">
                <x-dc.table :headers="['ID', 'Пациент', 'Статус', 'Приоритет', 'Ответственный', 'Обновлено']">
                    @forelse($cases as $c)
                        <x-dc.table-row>
                            <x-dc.table-cell class="text-dc-secondary">{{ $c->id }}</x-dc.table-cell>
                            <x-dc.table-cell class="font-medium text-dc">{{ $c->patient?->full_name }}</x-dc.table-cell>
                            <x-dc.table-cell>
                                <span class="text-dc text-ys-s">{{ $c->pipelineStatus?->name }}</span>
                                @if($c->serviceStatus)
                                    <x-dc.badge color="warning" size="20" class="ml-1">⏸ {{ $c->serviceStatus->name }}</x-dc.badge>
                                @endif
                            </x-dc.table-cell>
                            <x-dc.table-cell class="text-dc-secondary">{{ $c->priority }}</x-dc.table-cell>
                            <x-dc.table-cell class="text-dc-secondary">{{ $c->assignedTo?->name }}</x-dc.table-cell>
                            <x-dc.table-cell class="text-dc-secondary">{{ $c->updated_at?->format('Y-m-d H:i') }}</x-dc.table-cell>
                        </x-dc.table-row>
                    @empty
                        <tr>
                            <td colspan="6" class="py-10 text-center text-dc-secondary italic text-ys-s">
                                Нет кейсов
                            </td>
                        </tr>
                    @endforelse
                </x-dc.table>

                <div class="px-4 py-3" style="border-top:1px solid var(--color-border)">
                    {{ $cases->links() }}
                </div>
            </x-dc.card>
        </div>
    </div>
</x-app-layout>
