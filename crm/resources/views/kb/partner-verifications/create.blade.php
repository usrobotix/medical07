<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('kb.partner-verifications.index') }}" class="text-dc-secondary hover:text-dc text-ys-s dc-transition">← Проверки партнёров</a>
            <h2 class="text-ys-l font-semibold text-dc leading-tight">Новая верификация</h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('kb.partner-verifications.store') }}">
                @csrf
                <x-dc-card padding="lg" shadow="card">
                    <div class="space-y-4">
                        <p class="text-ys-s text-dc-secondary">Выберите партнёра и чек-лист. Пункты проверки будут автоматически созданы из чек-листа.</p>
                        <div>
                            <label class="block text-ys-xs font-medium text-dc-secondary mb-1">Партнёр <span class="text-dc-red-100">*</span></label>
                            <select name="partner_id" required class="block w-full h-9 px-3 text-ys-s rounded-2xs border border-dc-gray-30 bg-surface text-dc dc-transition focus:outline-none focus-visible:ring-2 focus-visible:ring-dc-yellow-100">
                                <option value="">— выберите партнёра —</option>
                                @foreach($partners as $partner)
                                    <option value="{{ $partner->id }}" @selected(old('partner_id', $selectedPartnerId) == $partner->id)>{{ $partner->name }}</option>
                                @endforeach
                            </select>
                            @error('partner_id')<p class="text-dc-red-100 text-ys-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-ys-xs font-medium text-dc-secondary mb-1">Чек-лист <span class="text-dc-red-100">*</span></label>
                            <select name="checklist_id" required class="block w-full h-9 px-3 text-ys-s rounded-2xs border border-dc-gray-30 bg-surface text-dc dc-transition focus:outline-none focus-visible:ring-2 focus-visible:ring-dc-yellow-100">
                                <option value="">— выберите чек-лист —</option>
                                @foreach($checklists as $checklist)
                                    @php $tl = ['clinic' => 'Клиника', 'translator' => 'Переводчик', 'curator' => 'Куратор']; @endphp
                                    <option value="{{ $checklist->id }}" @selected(old('checklist_id', $selectedChecklistId) == $checklist->id)>
                                        {{ $checklist->name }} ({{ $tl[$checklist->partner_type] ?? $checklist->partner_type }})
                                    </option>
                                @endforeach
                            </select>
                            @error('checklist_id')<p class="text-dc-red-100 text-ys-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div class="flex justify-end gap-3 pt-2">
                            <x-dc-button variant="contour" size="s" href="{{ route('kb.partner-verifications.index') }}">Отмена</x-dc-button>
                            <x-dc-button type="submit" variant="action" size="s">Начать верификацию</x-dc-button>
                        </div>
                    </div>
                </x-dc-card>
            </form>
        </div>
    </div>
</x-app-layout>
