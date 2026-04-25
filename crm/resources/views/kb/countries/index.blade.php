<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Страны</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <p class="mb-4 text-sm text-gray-500 dark:text-gray-400">Справочник стран, в которых работают партнёры.</p>
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-4">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left border-b border-gray-200 dark:border-gray-700">
                            <th class="py-2 pr-3 text-gray-600 dark:text-gray-400 font-semibold">Код</th>
                            <th class="py-2 pr-3 text-gray-600 dark:text-gray-400 font-semibold">Название (RU)</th>
                            <th class="py-2 text-gray-600 dark:text-gray-400 font-semibold">Название (EN)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($countries as $country)
                            <tr class="border-b border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                <td class="py-2 pr-3 font-mono text-xs text-gray-500 dark:text-gray-400">{{ $country->iso2 }}</td>
                                <td class="py-2 pr-3">
                                    <a href="{{ route('countries.show', $country) }}" class="text-indigo-600 dark:text-indigo-400 hover:underline font-medium">
                                        {{ $country->name_ru }}
                                    </a>
                                </td>
                                <td class="py-2 text-gray-600 dark:text-gray-400">{{ $country->name_en }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="py-8 text-center text-gray-400 dark:text-gray-500 italic">
                                    Страны ещё не добавлены
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-4">{{ $countries->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
