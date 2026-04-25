<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Ниши
            </h2>
            @auth
                @if(auth()->user()->hasAnyRole(['admin', 'manager']))
                    <a href="{{ route('kb.niches.create') }}" class="px-4 py-2 text-sm bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition font-medium">+ Добавить</a>
                @endif
            @endauth
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">

            {{-- Filters --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-4">
                <form method="GET" action="{{ route('kb.niches.index') }}" class="flex flex-wrap gap-3 items-end">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Поиск по названию</label>
                        <input type="text" name="name" value="{{ request('name') }}"
                            class="text-sm rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="Название ниши...">
                    </div>
                    <div class="flex gap-2">
                        <x-kb-apply-button />
                        <x-kb-reset-link :href="route('kb.niches.index')" />
                    </div>
                </form>
            </div>

            {{-- Table --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg overflow-hidden">
                @if($niches->isEmpty())
                    <div class="p-8 text-center text-gray-500 dark:text-gray-400">
                        Ниши не найдены.
                    </div>
                @else
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300 uppercase text-xs">
                            <tr>
                                <th class="px-4 py-3">Код</th>
                                <th class="px-4 py-3">Название</th>
                                <th class="px-4 py-3">Описание</th>
                                <th class="px-4 py-3">Партнёров</th>
                                <th class="px-4 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @foreach($niches as $niche)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-4 py-3 font-mono text-gray-700 dark:text-gray-300 text-xs">{{ $niche->code }}</td>
                                    <td class="px-4 py-3 font-medium text-gray-900 dark:text-gray-100">{{ $niche->name }}</td>
                                    <td class="px-4 py-3 text-gray-600 dark:text-gray-400 max-w-xs truncate">{{ $niche->description ?? '—' }}</td>
                                    <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ $niche->partners_count }}</td>
                                    <td class="px-4 py-3 text-right">
                                        <a href="{{ route('kb.niches.show', $niche) }}" class="text-indigo-600 dark:text-indigo-400 hover:underline text-xs">Подробнее</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @if($niches->hasPages())
                        <div class="px-4 py-3 border-t border-gray-100 dark:border-gray-700">
                            {{ $niches->links() }}
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
