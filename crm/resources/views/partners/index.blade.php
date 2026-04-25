<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Партнёры</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-4">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left border-b border-gray-200 dark:border-gray-700">
                            <th class="py-2 text-gray-600 dark:text-gray-400 font-semibold">ID</th>
                            <th class="py-2 text-gray-600 dark:text-gray-400 font-semibold">Название</th>
                            <th class="py-2 text-gray-600 dark:text-gray-400 font-semibold">Слой</th>
                            <th class="py-2 text-gray-600 dark:text-gray-400 font-semibold">Тип</th>
                            <th class="py-2 text-gray-600 dark:text-gray-400 font-semibold">Страна</th>
                            <th class="py-2 text-gray-600 dark:text-gray-400 font-semibold">Email</th>
                            <th class="py-2 text-gray-600 dark:text-gray-400 font-semibold">Статус</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($partners as $p)
                            <tr class="border-b border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                <td class="py-2 text-gray-800 dark:text-gray-200">{{ $p->id }}</td>
                                <td class="py-2 text-gray-800 dark:text-gray-200">{{ $p->name }}</td>
                                <td class="py-2 text-gray-600 dark:text-gray-400">{{ optional($p->layer)->name }}</td>
                                <td class="py-2 text-gray-600 dark:text-gray-400">{{ $p->type }}</td>
                                <td class="py-2 text-gray-600 dark:text-gray-400">{{ optional($p->country)->name_ru }}</td>
                                <td class="py-2 text-gray-600 dark:text-gray-400">{{ $p->contact_email }}</td>
                                <td class="py-2 text-gray-600 dark:text-gray-400">{{ $p->status }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-8 text-center text-gray-400 dark:text-gray-500 italic">
                                    Партнёры не найдены
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $partners->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
