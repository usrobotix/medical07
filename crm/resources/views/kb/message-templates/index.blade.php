<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Шаблоны сообщений</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <p class="mb-4 text-sm text-gray-500 dark:text-gray-400">Шаблоны писем и сообщений для разных каналов коммуникации.</p>
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-4">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left border-b border-gray-200 dark:border-gray-700">
                            <th class="py-2 pr-3 text-gray-600 dark:text-gray-400 font-semibold">Название</th>
                            <th class="py-2 pr-3 text-gray-600 dark:text-gray-400 font-semibold">Канал</th>
                            <th class="py-2 pr-3 text-gray-600 dark:text-gray-400 font-semibold">Язык</th>
                            <th class="py-2 text-gray-600 dark:text-gray-400 font-semibold">Тип партнёра</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($templates as $template)
                            <tr class="border-b border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                <td class="py-2 pr-3">
                                    <a href="{{ route('message-templates.show', $template) }}" class="text-indigo-600 dark:text-indigo-400 hover:underline font-medium">
                                        {{ $template->title }}
                                    </a>
                                </td>
                                <td class="py-2 pr-3">
                                    <span class="inline-block px-2 py-0.5 rounded text-xs font-medium
                                        {{ $template->channel === 'email' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300' : '' }}
                                        {{ $template->channel === 'whatsapp' ? 'bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-300' : '' }}
                                        {{ $template->channel === 'telegram' ? 'bg-sky-100 text-sky-700 dark:bg-sky-900/40 dark:text-sky-300' : '' }}
                                    ">{{ $template->channel }}</span>
                                </td>
                                <td class="py-2 pr-3 text-gray-600 dark:text-gray-400 uppercase text-xs">{{ $template->language }}</td>
                                <td class="py-2 text-gray-600 dark:text-gray-400">{{ $template->target_partner_type ?? 'любой' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-8 text-center text-gray-400 dark:text-gray-500 italic">
                                    Шаблоны ещё не добавлены
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-4">{{ $templates->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
