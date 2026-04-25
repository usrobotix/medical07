<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Шаблоны сообщений</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-4">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left border-b border-gray-200 dark:border-gray-700">
                            <th class="py-2 text-gray-600 dark:text-gray-400 font-semibold">ID</th>
                            <th class="py-2 text-gray-600 dark:text-gray-400 font-semibold">Код</th>
                            <th class="py-2 text-gray-600 dark:text-gray-400 font-semibold">Заголовок</th>
                            <th class="py-2 text-gray-600 dark:text-gray-400 font-semibold">Канал</th>
                            <th class="py-2 text-gray-600 dark:text-gray-400 font-semibold">Язык</th>
                            <th class="py-2 text-gray-600 dark:text-gray-400 font-semibold">Тип партнёра</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($templates as $t)
                            <tr class="border-b border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                <td class="py-2 text-gray-800 dark:text-gray-200">{{ $t->id }}</td>
                                <td class="py-2 text-gray-600 dark:text-gray-400 font-mono">{{ $t->code }}</td>
                                <td class="py-2 text-gray-800 dark:text-gray-200">{{ $t->title }}</td>
                                <td class="py-2 text-gray-600 dark:text-gray-400">{{ $t->channel }}</td>
                                <td class="py-2 text-gray-600 dark:text-gray-400">{{ $t->language }}</td>
                                <td class="py-2 text-gray-600 dark:text-gray-400">{{ $t->target_partner_type }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-gray-400 dark:text-gray-500 italic">
                                    Шаблоны не найдены
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $templates->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
