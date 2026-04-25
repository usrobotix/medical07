<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('verification-checklists.index') }}" class="text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300 text-sm">← Чек-листы</a>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">{{ $verificationChecklist->name }}</h2>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-3 text-sm">
                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">Код</dt>
                        <dd class="text-gray-800 dark:text-gray-200 mt-0.5 font-mono">{{ $verificationChecklist->code }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">Тип партнёра</dt>
                        <dd class="text-gray-800 dark:text-gray-200 mt-0.5">{{ $verificationChecklist->partner_type ?? 'любой' }}</dd>
                    </div>
                </dl>
                @if($verificationChecklist->description)
                    <div class="mt-4 border-t border-gray-100 dark:border-gray-700 pt-3">
                        <dt class="text-gray-500 dark:text-gray-400 text-sm">Описание</dt>
                        <dd class="text-gray-800 dark:text-gray-200 text-sm mt-1">{{ $verificationChecklist->description }}</dd>
                    </div>
                @endif
            </div>

            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <h3 class="font-semibold text-gray-700 dark:text-gray-300 mb-4">Пункты чек-листа</h3>
                @if($verificationChecklist->items->count())
                    <ol class="space-y-2">
                        @foreach($verificationChecklist->items as $item)
                            <li class="flex items-start gap-3 text-sm">
                                <span class="shrink-0 w-6 h-6 flex items-center justify-center rounded-full bg-indigo-100 dark:bg-indigo-900/40 text-indigo-700 dark:text-indigo-300 font-semibold text-xs">
                                    {{ $loop->iteration }}
                                </span>
                                <span class="text-gray-800 dark:text-gray-200 pt-0.5">{{ $item->text }}</span>
                            </li>
                        @endforeach
                    </ol>
                @else
                    <p class="text-sm text-gray-400 dark:text-gray-500 italic">Пункты ещё не добавлены</p>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
