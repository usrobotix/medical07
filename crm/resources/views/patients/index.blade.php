<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-ys-l font-semibold text-dc leading-tight">Пациенты</h2>
            <x-dc-button variant="action" size="s" href="{{ route('patients.create') }}">+ Пациент</x-dc-button>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-dc-card padding="none" shadow="card">
                <x-dc-table :headers="['ID', 'ФИО', 'Email', 'Телефон', 'Город']">
                    @forelse($patients as $p)
                        <x-dc-table-row>
                            <x-dc-table-cell class="text-dc-secondary">{{ $p->id }}</x-dc-table-cell>
                            <x-dc-table-cell class="font-medium text-dc">{{ $p->full_name }}</x-dc-table-cell>
                            <x-dc-table-cell class="text-dc-secondary">{{ $p->email }}</x-dc-table-cell>
                            <x-dc-table-cell class="text-dc-secondary">{{ $p->phone }}</x-dc-table-cell>
                            <x-dc-table-cell class="text-dc-secondary">{{ $p->city }}</x-dc-table-cell>
                        </x-dc-table-row>
                    @empty
                        <tr>
                            <td colspan="5" class="py-10 text-center text-dc-secondary italic text-ys-s">
                                Нет пациентов
                            </td>
                        </tr>
                    @endforelse
                </x-dc-table>

                <div class="px-4 py-3" style="border-top:1px solid var(--color-border)">
                    {{ $patients->links() }}
                </div>
            </x-dc-card>
        </div>
    </div>
</x-app-layout>
