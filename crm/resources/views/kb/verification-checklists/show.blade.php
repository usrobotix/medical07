<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('kb.verification-checklists.index') }}" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 text-sm">← Чек-листы</a>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $verificationChecklist->name }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Main info --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">Информация о чек-листе</h3>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-3 text-sm">
                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">Код</dt>
                        <dd class="text-gray-900 dark:text-gray-100 font-mono">{{ $verificationChecklist->code }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">Тип партнёра</dt>
                        <dd class="text-gray-900 dark:text-gray-100">
                            @match($verificationChecklist->partner_type)
                                'clinic' => 'Клиника',
                                'translator' => 'Переводчик',
                                'curator' => 'Куратор',
                                default => $verificationChecklist->partner_type ?? '—',
                            @endmatch
                        </dd>
                    </div>
                    @if($verificationChecklist->description)
                        <div class="sm:col-span-2">
                            <dt class="text-gray-500 dark:text-gray-400">Описание</dt>
                            <dd class="text-gray-900 dark:text-gray-100">{{ $verificationChecklist->description }}</dd>
                        </div>
                    @endif
                </dl>
            </div>

            {{-- Items --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <h3 class="text-base font-semibold text-gray-800 dark:text-gray-100 mb-4">Пункты чек-листа ({{ $verificationChecklist->items->count() }})</h3>
                @if($verificationChecklist->items->isEmpty())
                    <p class="text-sm text-gray-500 dark:text-gray-400">Пункты не добавлены.</p>
                @else
                    <ol class="space-y-3">
                        @foreach($verificationChecklist->items as $item)
                            <li class="flex items-start gap-3">
                                <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300 rounded-full flex items-center justify-center text-xs font-medium">
                                    {{ $loop->iteration }}
                                </span>
                                <div class="flex-1">
                                    <p class="text-sm text-gray-800 dark:text-gray-100">{{ $item->text }}</p>
                                    @if($item->code)
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 font-mono">{{ $item->code }}</p>
                                    @endif
                                </div>
                            </li>
                        @endforeach
                    </ol>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
