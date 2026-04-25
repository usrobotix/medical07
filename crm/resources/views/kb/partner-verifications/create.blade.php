<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('kb.partner-verifications.index') }}" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 text-sm">← Проверки партнёров</a>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Новая верификация
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('kb.partner-verifications.store') }}">
                @csrf
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6 space-y-4">
                    <p class="text-sm text-gray-600 dark:text-gray-400">Выберите партнёра и чек-лист. Пункты проверки будут автоматически созданы из чек-листа.</p>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Партнёр <span class="text-red-500">*</span></label>
                        <select name="partner_id" required class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            <option value="">— выберите партнёра —</option>
                            @foreach($partners as $partner)
                                <option value="{{ $partner->id }}" @selected(old('partner_id', $selectedPartnerId) == $partner->id)>{{ $partner->name }}</option>
                            @endforeach
                        </select>
                        @error('partner_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Чек-лист <span class="text-red-500">*</span></label>
                        <select name="checklist_id" required class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            <option value="">— выберите чек-лист —</option>
                            @foreach($checklists as $checklist)
                                @php $tl = ['clinic' => 'Клиника', 'translator' => 'Переводчик', 'curator' => 'Куратор']; @endphp
                                <option value="{{ $checklist->id }}" @selected(old('checklist_id', $selectedChecklistId) == $checklist->id)>
                                    {{ $checklist->name }} ({{ $tl[$checklist->partner_type] ?? $checklist->partner_type }})
                                </option>
                            @endforeach
                        </select>
                        @error('checklist_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="flex justify-end gap-3 pt-2">
                        <a href="{{ route('kb.partner-verifications.index') }}" class="px-4 py-2 text-sm bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600 transition">Отмена</a>
                        <button type="submit" class="px-6 py-2 text-sm bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition font-medium">Начать верификацию</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
