<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Ниши</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-4">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left border-b border-gray-200 dark:border-gray-700">
                            <th class="py-2 text-gray-600 dark:text-gray-400 font-semibold">ID</th>
                            <th class="py-2 text-gray-600 dark:text-gray-400 font-semibold">Код</th>
                            <th class="py-2 text-gray-600 dark:text-gray-400 font-semibold">Название</th>
                            <th class="py-2 text-gray-600 dark:text-gray-400 font-semibold">Описание</th>
                            <th class="py-2 text-gray-600 dark:text-gray-400 font-semibold">Порядок</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($niches as $n)
                            <tr class="border-b border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                <td class="py-2 text-gray-800 dark:text-gray-200">{{ $n->id }}</td>
                                <td class="py-2 text-gray-600 dark:text-gray-400 font-mono">{{ $n->code }}</td>
                                <td class="py-2 text-gray-800 dark:text-gray-200">{{ $n->name }}</td>
                                <td class="py-2 text-gray-600 dark:text-gray-400">{{ Str::limit($n->description, 80) }}</td>
                                <td class="py-2 text-gray-600 dark:text-gray-400">{{ $n->sort_order }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-gray-400 dark:text-gray-500 italic">
                                    Ниши не найдены
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $niches->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
