<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('kb.verification-checklists.show', $verificationChecklist) }}" class="text-dc-secondary hover:text-dc text-ys-s dc-transition">← {{ $verificationChecklist->name }}</a>
            <h2 class="text-ys-l font-semibold text-dc leading-tight">Редактировать чек-лист</h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <form id="form-update-checklist" method="POST" action="{{ route('kb.verification-checklists.update', $verificationChecklist) }}">
                @csrf
                @method('PATCH')
                <x-dc.card padding="lg" shadow="card">
                    <h3 class="text-ys-s font-semibold text-dc mb-4">Основные параметры</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-ys-xs font-medium text-dc-secondary mb-1">Название <span class="text-dc-red-100">*</span></label>
                            <input type="text" name="name" value="{{ old('name', $verificationChecklist->name) }}" required class="block w-full h-9 px-4 text-ys-s rounded-2xs border border-dc-gray-30 bg-surface text-dc dc-transition focus:outline-none focus-visible:ring-2 focus-visible:ring-dc-yellow-100">
                            @error('name')<p class="text-dc-red-100 text-ys-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-ys-xs font-medium text-dc-secondary mb-1">Тип партнёра <span class="text-dc-red-100">*</span></label>
                            <select name="partner_type" required class="block w-full h-9 px-3 text-ys-s rounded-2xs border border-dc-gray-30 bg-surface text-dc dc-transition focus:outline-none focus-visible:ring-2 focus-visible:ring-dc-yellow-100">
                                <option value="clinic" @selected(old('partner_type', $verificationChecklist->partner_type) === 'clinic')>Клиника</option>
                                <option value="translator" @selected(old('partner_type', $verificationChecklist->partner_type) === 'translator')>Переводчик</option>
                                <option value="curator" @selected(old('partner_type', $verificationChecklist->partner_type) === 'curator')>Куратор</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-ys-xs font-medium text-dc-secondary mb-1">Описание</label>
                            <textarea name="description" rows="3" class="block w-full p-3 text-ys-s rounded-2xs border border-dc-gray-30 bg-surface text-dc dc-transition focus:outline-none focus-visible:ring-2 focus-visible:ring-dc-yellow-100">{{ old('description', $verificationChecklist->description) }}</textarea>
                        </div>
                        <div>
                            <label class="block text-ys-xs font-medium text-dc-secondary mb-1">Порядок сортировки</label>
                            <input type="number" name="sort_order" value="{{ old('sort_order', $verificationChecklist->sort_order) }}" class="block w-full h-9 px-4 text-ys-s rounded-2xs border border-dc-gray-30 bg-surface text-dc dc-transition focus:outline-none focus-visible:ring-2 focus-visible:ring-dc-yellow-100">
                        </div>
                    </div>
                </x-dc.card>
            </form>
            <div class="flex justify-between items-center pt-4">
                <form method="POST" action="{{ route('kb.verification-checklists.destroy', $verificationChecklist) }}" onsubmit="return confirm('Удалить чек-лист? Все пункты будут удалены.')">
                    @csrf
                    @method('DELETE')
                    <x-dc.button type="submit" variant="danger" size="s">Удалить чек-лист</x-dc.button>
                </form>
                <x-dc.button form="form-update-checklist" type="submit" variant="action" size="s">Сохранить</x-dc.button>
            </div>

            <x-dc.card padding="lg" shadow="card">
                <h3 class="text-ys-s font-semibold text-dc mb-4">Пункты чек-листа ({{ $verificationChecklist->items->count() }})</h3>

                @if($verificationChecklist->items->isNotEmpty())
                    <ol class="space-y-3 mb-6">
                        @foreach($verificationChecklist->items as $item)
                            <li class="flex items-start gap-3 group">
                                <span class="flex-shrink-0 w-6 h-6 bg-dc-blue-20 text-dc-primary rounded-full flex items-center justify-center text-ys-xs font-medium mt-1">
                                    {{ $loop->iteration }}
                                </span>
                                <div class="flex-1">
                                    <form method="POST" action="{{ route('kb.verification-checklists.items.update', [$verificationChecklist, $item]) }}" class="flex gap-2 items-center">
                                        @csrf
                                        @method('PATCH')
                                        <input type="text" name="text" value="{{ $item->text }}" required class="block w-full h-9 px-4 text-ys-s rounded-2xs border border-dc-gray-30 bg-surface text-dc dc-transition focus:outline-none focus-visible:ring-2 focus-visible:ring-dc-yellow-100 flex-1">
                                        <x-dc.button type="submit" variant="normal" size="xs">Обновить</x-dc.button>
                                    </form>
                                </div>
                                <form method="POST" action="{{ route('kb.verification-checklists.items.destroy', [$verificationChecklist, $item]) }}" onsubmit="return confirm('Удалить пункт?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-dc-red-60 hover:text-dc-red-100 mt-1 dc-transition" title="Удалить">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </form>
                            </li>
                        @endforeach
                    </ol>
                @endif

                <form method="POST" action="{{ route('kb.verification-checklists.items.store', $verificationChecklist) }}" class="flex gap-2 items-center border-t pt-4" style="border-color:var(--color-border)">
                    @csrf
                    <input type="text" name="text" placeholder="Текст нового пункта..." required class="block w-full h-9 px-4 text-ys-s rounded-2xs border border-dc-gray-30 bg-surface text-dc dc-transition focus:outline-none focus-visible:ring-2 focus-visible:ring-dc-yellow-100 flex-1">
                    <x-dc.button type="submit" variant="action" size="s">+ Добавить</x-dc.button>
                </form>
            </x-dc.card>

        </div>
    </div>
</x-app-layout>
