<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Кейсы</h2>
            <div class="flex gap-2">
                <a href="{{ route('cases.board') }}"
                   class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 transition ease-in-out duration-150">
                    Канбан
                </a>
                <a href="{{ route('cases.create') }}"
                   class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white transition ease-in-out duration-150">
                    + Кейс
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-4">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left border-b border-gray-200 dark:border-gray-700">
                            <th class="py-2 text-gray-600 dark:text-gray-400 font-semibold">ID</th>
                            <th class="py-2 text-gray-600 dark:text-gray-400 font-semibold">Пациент</th>
                            <th class="py-2 text-gray-600 dark:text-gray-400 font-semibold">Статус</th>
                            <th class="py-2 text-gray-600 dark:text-gray-400 font-semibold">Приоритет</th>
                            <th class="py-2 text-gray-600 dark:text-gray-400 font-semibold">Ответственный</th>
                            <th class="py-2 text-gray-600 dark:text-gray-400 font-semibold">Обновлено</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($cases as $c)
                            <tr class="border-b border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                <td class="py-2 text-gray-800 dark:text-gray-200">{{ $c->id }}</td>
                                <td class="py-2 text-gray-800 dark:text-gray-200">{{ $c->patient?->full_name }}</td>
                                <td class="py-2">
                                    <span class="text-gray-700 dark:text-gray-300">{{ $c->pipelineStatus?->name }}</span>
                                    @if($c->serviceStatus)
                                        <span class="ml-1 inline-block px-1.5 py-0.5 text-xs rounded-full bg-yellow-100 dark:bg-yellow-900/50 text-yellow-800 dark:text-yellow-300">
                                            ⏸ {{ $c->serviceStatus->name }}
                                        </span>
                                    @endif
                                </td>
                                <td class="py-2 text-gray-600 dark:text-gray-400">{{ $c->priority }}</td>
                                <td class="py-2 text-gray-600 dark:text-gray-400">{{ $c->assignedTo?->name }}</td>
                                <td class="py-2 text-gray-500 dark:text-gray-500">{{ $c->updated_at?->format('Y-m-d H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-gray-400 dark:text-gray-500 italic">
                                    Нет кейсов
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $cases->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>