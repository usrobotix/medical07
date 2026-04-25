<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Проверки партнёров
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">

            {{-- Filters --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-4">
                <form method="GET" action="{{ route('kb.partner-verifications.index') }}" class="flex flex-wrap gap-3 items-end">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Статус</label>
                        <select name="status" class="text-sm rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Все статусы</option>
                            <option value="not_started" @selected(request('status') === 'not_started')>Не начата</option>
                            <option value="in_progress" @selected(request('status') === 'in_progress')>В процессе</option>
                            <option value="passed" @selected(request('status') === 'passed')>Пройдена</option>
                            <option value="failed" @selected(request('status') === 'failed')>Провалена</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Чек-лист</label>
                        <select name="checklist_id" class="text-sm rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Все чек-листы</option>
                            @foreach($checklists as $checklist)
                                <option value="{{ $checklist->id }}" @selected(request('checklist_id') == $checklist->id)>{{ $checklist->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Тип партнёра</label>
                        <select name="partner_type" class="text-sm rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Все типы</option>
                            <option value="clinic" @selected(request('partner_type') === 'clinic')>Клиника</option>
                            <option value="translator" @selected(request('partner_type') === 'translator')>Переводчик</option>
                            <option value="curator" @selected(request('partner_type') === 'curator')>Куратор</option>
                        </select>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="px-4 py-2 text-sm bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">Применить</button>
                        <a href="{{ route('kb.partner-verifications.index') }}" class="px-4 py-2 text-sm bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600 transition">Сбросить</a>
                    </div>
                </form>
            </div>

            {{-- Table --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg overflow-hidden">
                @if($verifications->isEmpty())
                    <div class="p-8 text-center text-gray-500 dark:text-gray-400">
                        Проверки не найдены.
                    </div>
                @else
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300 uppercase text-xs">
                            <tr>
                                <th class="px-4 py-3">Партнёр</th>
                                <th class="px-4 py-3">Чек-лист</th>
                                <th class="px-4 py-3">Статус</th>
                                <th class="px-4 py-3">Проверен</th>
                                <th class="px-4 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @foreach($verifications as $verification)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-750">
                                    <td class="px-4 py-3 font-medium text-gray-900 dark:text-gray-100">{{ $verification->partner?->name ?? '—' }}</td>
                                    <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ $verification->checklist?->name ?? '—' }}</td>
                                    <td class="px-4 py-3">
                                        @php
                                            $vColors = [
                                                'not_started' => 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300',
                                                'in_progress' => 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-200',
                                                'passed'      => 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-200',
                                                'failed'      => 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-200',
                                            ];
                                            $vLabels = ['not_started' => 'Не начата', 'in_progress' => 'В процессе', 'passed' => 'Пройдена', 'failed' => 'Провалена'];
                                        @endphp
                                        <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $vColors[$verification->status] ?? '' }}">
                                            {{ $vLabels[$verification->status] ?? $verification->status }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-gray-600 dark:text-gray-400">
                                        {{ $verification->verified_at?->format('d.m.Y') ?? '—' }}
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <a href="{{ route('kb.partner-verifications.show', $verification) }}" class="text-indigo-600 dark:text-indigo-400 hover:underline text-xs">Подробнее</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @if($verifications->hasPages())
                        <div class="px-4 py-3 border-t border-gray-100 dark:border-gray-700">
                            {{ $verifications->links() }}
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
