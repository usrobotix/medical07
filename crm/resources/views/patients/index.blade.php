<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Пациенты</h2>
            <a href="{{ route('patients.create') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white transition ease-in-out duration-150">
                + Пациент
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-4">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left border-b border-gray-200 dark:border-gray-700">
                            <th class="py-2 text-gray-600 dark:text-gray-400 font-semibold">ID</th>
                            <th class="py-2 text-gray-600 dark:text-gray-400 font-semibold">ФИО</th>
                            <th class="py-2 text-gray-600 dark:text-gray-400 font-semibold">Email</th>
                            <th class="py-2 text-gray-600 dark:text-gray-400 font-semibold">Телефон</th>
                            <th class="py-2 text-gray-600 dark:text-gray-400 font-semibold">Город</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($patients as $p)
                            <tr class="border-b border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                <td class="py-2 text-gray-800 dark:text-gray-200">{{ $p->id }}</td>
                                <td class="py-2 text-gray-800 dark:text-gray-200">{{ $p->full_name }}</td>
                                <td class="py-2 text-gray-600 dark:text-gray-400">{{ $p->email }}</td>
                                <td class="py-2 text-gray-600 dark:text-gray-400">{{ $p->phone }}</td>
                                <td class="py-2 text-gray-600 dark:text-gray-400">{{ $p->city }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-gray-400 dark:text-gray-500 italic">
                                    Нет пациентов
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $patients->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>