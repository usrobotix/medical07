<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Проверки партнёров</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <p class="mb-4 text-sm text-gray-500 dark:text-gray-400">Экземпляры чек-листов верификации по конкретным партнёрам.</p>
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-4">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left border-b border-gray-200 dark:border-gray-700">
                            <th class="py-2 pr-3 text-gray-600 dark:text-gray-400 font-semibold">Партнёр</th>
                            <th class="py-2 pr-3 text-gray-600 dark:text-gray-400 font-semibold">Чек-лист</th>
                            <th class="py-2 pr-3 text-gray-600 dark:text-gray-400 font-semibold">Статус</th>
                            <th class="py-2 text-gray-600 dark:text-gray-400 font-semibold">Дата</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($verifications as $v)
                            <tr class="border-b border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                <td class="py-2 pr-3">
                                    <a href="{{ route('partner-verifications.show', $v) }}" class="text-indigo-600 dark:text-indigo-400 hover:underline font-medium">
                                        {{ $v->partner?->name ?? '#'.$v->partner_id }}
                                    </a>
                                </td>
                                <td class="py-2 pr-3 text-gray-600 dark:text-gray-400">{{ $v->checklist?->name ?? '—' }}</td>
                                <td class="py-2 pr-3">
                                    <span class="inline-block px-2 py-0.5 rounded text-xs font-medium
                                        {{ $v->status === 'passed' ? 'bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-300' : '' }}
                                        {{ $v->status === 'failed' ? 'bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-300' : '' }}
                                        {{ $v->status === 'in_progress' ? 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/40 dark:text-yellow-300' : '' }}
                                        {{ $v->status === 'not_started' ? 'bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400' : '' }}
                                    ">{{ $v->status }}</span>
                                </td>
                                <td class="py-2 text-gray-500 dark:text-gray-500">{{ $v->verified_at?->format('d.m.Y') ?? '—' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-8 text-center text-gray-400 dark:text-gray-500 italic">
                                    Проверки ещё не начаты
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-4">{{ $verifications->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
