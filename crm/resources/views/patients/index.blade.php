<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Пациенты</h2>
            <a href="{{ route('patients.create') }}" class="px-3 py-2 bg-blue-600 text-white rounded">+ Пациент</a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left border-b">
                            <th class="py-2">ID</th>
                            <th>ФИО</th>
                            <th>Email</th>
                            <th>Телефон</th>
                            <th>Город</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($patients as $p)
                            <tr class="border-b">
                                <td class="py-2">{{ $p->id }}</td>
                                <td>{{ $p->full_name }}</td>
                                <td>{{ $p->email }}</td>
                                <td>{{ $p->phone }}</td>
                                <td>{{ $p->city }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $patients->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>