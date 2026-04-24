<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Кейсы</h2>
            <div class="flex gap-2">
                <a href="{{ route('cases.board') }}" class="px-3 py-2 border rounded">Канбан</a>
                <a href="{{ route('cases.create') }}" class="px-3 py-2 bg-blue-600 text-white rounded">+ Кейс</a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left border-b">
                            <th class="py-2">ID</th>
                            <th>Пациент</th>
                            <th>Статус</th>
                            <th>Приоритет</th>
                            <th>Ответственный</th>
                            <th>Обновлено</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cases as $c)
                            <tr class="border-b">
                                <td class="py-2">{{ $c->id }}</td>
                                <td>{{ $c->patient?->full_name }}</td>
                                <td>{{ $c->status?->name }}</td>
                                <td>{{ $c->priority }}</td>
                                <td>{{ $c->assignedTo?->name }}</td>
                                <td>{{ $c->updated_at?->format('Y-m-d H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $cases->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>