<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Партнёры</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <p class="mb-4 text-sm text-gray-500 dark:text-gray-400">Список всех партнёров: клиники, переводчики и кураторы.</p>
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-4">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left border-b border-gray-200 dark:border-gray-700">
                            <th class="py-2 pr-3 text-gray-600 dark:text-gray-400 font-semibold">Название</th>
                            <th class="py-2 pr-3 text-gray-600 dark:text-gray-400 font-semibold">Тип</th>
                            <th class="py-2 pr-3 text-gray-600 dark:text-gray-400 font-semibold">Страна</th>
                            <th class="py-2 pr-3 text-gray-600 dark:text-gray-400 font-semibold">Статус</th>
                            <th class="py-2 text-gray-600 dark:text-gray-400 font-semibold">Ниши</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($partners as $partner)
                            <tr class="border-b border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                <td class="py-2 pr-3">
                                    <a href="{{ route('partners.show', $partner) }}" class="text-indigo-600 dark:text-indigo-400 hover:underline font-medium">
                                        {{ $partner->name }}
                                    </a>
                                </td>
                                <td class="py-2 pr-3 text-gray-600 dark:text-gray-400">{{ $partner->type }}</td>
                                <td class="py-2 pr-3 text-gray-600 dark:text-gray-400">{{ $partner->country?->name_ru ?? '—' }}</td>
                                <td class="py-2 pr-3">
                                    <span class="inline-block px-2 py-0.5 rounded text-xs font-medium
                                        {{ $partner->status === 'active' ? 'bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-300' : '' }}
                                        {{ $partner->status === 'verified' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300' : '' }}
                                        {{ $partner->status === 'new' ? 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/40 dark:text-yellow-300' : '' }}
                                        {{ $partner->status === 'frozen' ? 'bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400' : '' }}
                                    ">{{ $partner->status }}</span>
                                </td>
                                <td class="py-2 text-gray-600 dark:text-gray-400 text-xs">
                                    {{ $partner->niches->pluck('name')->join(', ') ?: '—' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-gray-400 dark:text-gray-500 italic">
                                    Партнёры ещё не добавлены
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-4">{{ $partners->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
