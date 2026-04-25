<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Проверки партнёров</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-4">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left border-b border-gray-200 dark:border-gray-700">
                            <th class="py-2 text-gray-600 dark:text-gray-400 font-semibold">ID</th>
                            <th class="py-2 text-gray-600 dark:text-gray-400 font-semibold">Партнёр</th>
                            <th class="py-2 text-gray-600 dark:text-gray-400 font-semibold">Чек-лист</th>
                            <th class="py-2 text-gray-600 dark:text-gray-400 font-semibold">Статус</th>
                            <th class="py-2 text-gray-600 dark:text-gray-400 font-semibold">Дата верификации</th>
                            <th class="py-2 text-gray-600 dark:text-gray-400 font-semibold">Верифицировал</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($verifications as $v)
                            <tr class="border-b border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                <td class="py-2 text-gray-800 dark:text-gray-200">{{ $v->id }}</td>
                                <td class="py-2 text-gray-800 dark:text-gray-200">{{ optional($v->partner)->name }}</td>
                                <td class="py-2 text-gray-600 dark:text-gray-400">{{ optional($v->checklist)->name }}</td>
                                <td class="py-2 text-gray-600 dark:text-gray-400">{{ $v->status }}</td>
                                <td class="py-2 text-gray-600 dark:text-gray-400">{{ $v->verified_at?->format('d.m.Y') }}</td>
                                <td class="py-2 text-gray-600 dark:text-gray-400">{{ optional($v->verifiedBy)->name }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-gray-400 dark:text-gray-500 italic">
                                    Проверки не найдены
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $verifications->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
