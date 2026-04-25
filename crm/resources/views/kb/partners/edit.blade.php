<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('kb.partners.show', $partner) }}" class="text-dc-secondary hover:text-dc text-ys-s dc-transition">← {{ $partner->name }}</a>
            <h2 class="text-ys-l font-semibold text-dc leading-tight">Редактировать партнёра</h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('kb.partners.update', $partner) }}" class="space-y-6">
                @csrf
                @method('PATCH')

                <x-dc.card padding="lg" shadow="card">
                    <h3 class="text-ys-s font-semibold text-dc mb-5">Основная информация</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="sm:col-span-2">
                            <label class="block text-ys-xs font-medium text-dc-secondary mb-1">Название <span class="text-dc-red-100">*</span></label>
                            <input type="text" name="name" value="{{ old('name', $partner->name) }}" required class="block w-full h-9 px-4 text-ys-s rounded-2xs border border-dc-gray-30 bg-surface text-dc dc-transition focus:outline-none focus-visible:ring-2 focus-visible:ring-dc-yellow-100">
                            @error('name')<p class="text-dc-red-100 text-ys-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-ys-xs font-medium text-dc-secondary mb-1">Тип <span class="text-dc-red-100">*</span></label>
                            <select name="type" required class="block w-full h-9 px-3 text-ys-s rounded-2xs border border-dc-gray-30 bg-surface text-dc dc-transition focus:outline-none focus-visible:ring-2 focus-visible:ring-dc-yellow-100">
                                <option value="clinic" @selected(old('type', $partner->type) === 'clinic')>Клиника</option>
                                <option value="translator" @selected(old('type', $partner->type) === 'translator')>Переводчик</option>
                                <option value="curator" @selected(old('type', $partner->type) === 'curator')>Куратор</option>
                            </select>
                            @error('type')<p class="text-dc-red-100 text-ys-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-ys-xs font-medium text-dc-secondary mb-1">Статус <span class="text-dc-red-100">*</span></label>
                            <select name="status" required class="block w-full h-9 px-3 text-ys-s rounded-2xs border border-dc-gray-30 bg-surface text-dc dc-transition focus:outline-none focus-visible:ring-2 focus-visible:ring-dc-yellow-100">
                                <option value="new" @selected(old('status', $partner->status) === 'new')>Новый</option>
                                <option value="verified" @selected(old('status', $partner->status) === 'verified')>Верифицирован</option>
                                <option value="active" @selected(old('status', $partner->status) === 'active')>Активен</option>
                                <option value="frozen" @selected(old('status', $partner->status) === 'frozen')>Заморожен</option>
                            </select>
                            @error('status')<p class="text-dc-red-100 text-ys-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-ys-xs font-medium text-dc-secondary mb-1">Слой</label>
                            <select name="partner_layer_id" class="block w-full h-9 px-3 text-ys-s rounded-2xs border border-dc-gray-30 bg-surface text-dc dc-transition focus:outline-none focus-visible:ring-2 focus-visible:ring-dc-yellow-100">
                                <option value="">— не выбрано —</option>
                                @foreach($layers as $layer)
                                    <option value="{{ $layer->id }}" @selected(old('partner_layer_id', $partner->partner_layer_id) == $layer->id)>{{ $layer->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-ys-xs font-medium text-dc-secondary mb-1">Основная страна</label>
                            <select name="country_id" class="block w-full h-9 px-3 text-ys-s rounded-2xs border border-dc-gray-30 bg-surface text-dc dc-transition focus:outline-none focus-visible:ring-2 focus-visible:ring-dc-yellow-100">
                                <option value="">— не выбрано —</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country->id }}" @selected(old('country_id', $partner->country_id) == $country->id)>{{ $country->name_ru }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-ys-xs font-medium text-dc-secondary mb-1">Город</label>
                            <input type="text" name="city" value="{{ old('city', $partner->city) }}" class="block w-full h-9 px-4 text-ys-s rounded-2xs border border-dc-gray-30 bg-surface text-dc dc-transition focus:outline-none focus-visible:ring-2 focus-visible:ring-dc-yellow-100">
                        </div>
                        <div>
                            <label class="block text-ys-xs font-medium text-dc-secondary mb-1">Языки</label>
                            <input type="text" name="languages" value="{{ old('languages', $partner->languages) }}" placeholder="ru, en" class="block w-full h-9 px-4 text-ys-s rounded-2xs border border-dc-gray-30 bg-surface text-dc dc-transition focus:outline-none focus-visible:ring-2 focus-visible:ring-dc-yellow-100">
                        </div>
                    </div>
                    <div class="mt-4">
                        <label class="block text-ys-xs font-medium text-dc-secondary mb-1">Ниши</label>
                        <div class="flex flex-wrap gap-2 mt-1">
                            @php $selectedNiches = old('niche_ids', $partner->niches->pluck('id')->toArray()); @endphp
                            @foreach($niches as $niche)
                                <label class="inline-flex items-center gap-1.5 text-ys-s text-dc cursor-pointer">
                                    <input type="checkbox" name="niche_ids[]" value="{{ $niche->id }}"
                                        @checked(in_array($niche->id, $selectedNiches))
                                        class="rounded border-dc-gray-30 text-dc-blue-100 focus:ring-dc-yellow-100">
                                    {{ $niche->name }}
                                </label>
                            @endforeach
                        </div>
                    </div>
                </x-dc.card>

                <x-dc.card padding="lg" shadow="card">
                    <h3 class="text-ys-s font-semibold text-dc mb-4">Контакты</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-ys-xs font-medium text-dc-secondary mb-1">Контактное лицо</label>
                            <input type="text" name="contact_name" value="{{ old('contact_name', $partner->contact_name) }}" class="block w-full h-9 px-4 text-ys-s rounded-2xs border border-dc-gray-30 bg-surface text-dc dc-transition focus:outline-none focus-visible:ring-2 focus-visible:ring-dc-yellow-100">
                        </div>
                        <div>
                            <label class="block text-ys-xs font-medium text-dc-secondary mb-1">Email</label>
                            <input type="email" name="contact_email" value="{{ old('contact_email', $partner->contact_email) }}" class="block w-full h-9 px-4 text-ys-s rounded-2xs border border-dc-gray-30 bg-surface text-dc dc-transition focus:outline-none focus-visible:ring-2 focus-visible:ring-dc-yellow-100">
                        </div>
                        <div>
                            <label class="block text-ys-xs font-medium text-dc-secondary mb-1">Телефон</label>
                            <input type="text" name="contact_phone" value="{{ old('contact_phone', $partner->contact_phone) }}" class="block w-full h-9 px-4 text-ys-s rounded-2xs border border-dc-gray-30 bg-surface text-dc dc-transition focus:outline-none focus-visible:ring-2 focus-visible:ring-dc-yellow-100">
                        </div>
                        <div>
                            <label class="block text-ys-xs font-medium text-dc-secondary mb-1">WhatsApp</label>
                            <input type="text" name="contact_whatsapp" value="{{ old('contact_whatsapp', $partner->contact_whatsapp) }}" class="block w-full h-9 px-4 text-ys-s rounded-2xs border border-dc-gray-30 bg-surface text-dc dc-transition focus:outline-none focus-visible:ring-2 focus-visible:ring-dc-yellow-100">
                        </div>
                        <div>
                            <label class="block text-ys-xs font-medium text-dc-secondary mb-1">Telegram</label>
                            <input type="text" name="contact_telegram" value="{{ old('contact_telegram', $partner->contact_telegram) }}" class="block w-full h-9 px-4 text-ys-s rounded-2xs border border-dc-gray-30 bg-surface text-dc dc-transition focus:outline-none focus-visible:ring-2 focus-visible:ring-dc-yellow-100">
                        </div>
                        <div>
                            <label class="block text-ys-xs font-medium text-dc-secondary mb-1">Сайт</label>
                            <input type="url" name="website_url" value="{{ old('website_url', $partner->website_url) }}" class="block w-full h-9 px-4 text-ys-s rounded-2xs border border-dc-gray-30 bg-surface text-dc dc-transition focus:outline-none focus-visible:ring-2 focus-visible:ring-dc-yellow-100">
                        </div>
                    </div>
                </x-dc.card>

                <x-dc.card padding="lg" shadow="card">
                    <h3 class="text-ys-s font-semibold text-dc mb-4">SLA и финансы</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-ys-xs font-medium text-dc-secondary mb-1">Время ответа (ч)</label>
                            <input type="number" name="sla_response_hours" value="{{ old('sla_response_hours', $partner->sla_response_hours) }}" min="0" class="block w-full h-9 px-4 text-ys-s rounded-2xs border border-dc-gray-30 bg-surface text-dc dc-transition focus:outline-none focus-visible:ring-2 focus-visible:ring-dc-yellow-100">
                        </div>
                        <div>
                            <label class="block text-ys-xs font-medium text-dc-secondary mb-1">Срок результата (дн)</label>
                            <input type="number" name="sla_result_days" value="{{ old('sla_result_days', $partner->sla_result_days) }}" min="0" class="block w-full h-9 px-4 text-ys-s rounded-2xs border border-dc-gray-30 bg-surface text-dc dc-transition focus:outline-none focus-visible:ring-2 focus-visible:ring-dc-yellow-100">
                        </div>
                        <div>
                            <label class="block text-ys-xs font-medium text-dc-secondary mb-1">Рейтинг верификации (0–100)</label>
                            <input type="number" name="verification_score" value="{{ old('verification_score', $partner->verification_score) }}" min="0" max="100" step="0.1" class="block w-full h-9 px-4 text-ys-s rounded-2xs border border-dc-gray-30 bg-surface text-dc dc-transition focus:outline-none focus-visible:ring-2 focus-visible:ring-dc-yellow-100">
                        </div>
                        <div class="flex items-center gap-2 pt-5">
                            <input type="hidden" name="invoice_required" value="0">
                            <input type="checkbox" name="invoice_required" value="1" id="invoice_required"
                                @checked(old('invoice_required', $partner->invoice_required))
                                class="rounded border-dc-gray-30 text-dc-blue-100 focus:ring-dc-yellow-100">
                            <label for="invoice_required" class="text-ys-s text-dc">Инвойс обязателен</label>
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-ys-xs font-medium text-dc-secondary mb-1">Условия оплаты</label>
                            <textarea name="pricing_notes" rows="2" class="block w-full p-3 text-ys-s rounded-2xs border border-dc-gray-30 bg-surface text-dc dc-transition focus:outline-none focus-visible:ring-2 focus-visible:ring-dc-yellow-100">{{ old('pricing_notes', $partner->pricing_notes) }}</textarea>
                        </div>
                    </div>
                </x-dc.card>

                <x-dc.card padding="lg" shadow="card">
                    <label class="block text-ys-xs font-medium text-dc-secondary mb-1">Заметки</label>
                    <textarea name="notes" rows="4" class="block w-full p-3 text-ys-s rounded-2xs border border-dc-gray-30 bg-surface text-dc dc-transition focus:outline-none focus-visible:ring-2 focus-visible:ring-dc-yellow-100">{{ old('notes', $partner->notes) }}</textarea>
                </x-dc.card>

                <div class="flex justify-between items-center">
                    <form method="POST" action="{{ route('kb.partners.destroy', $partner) }}" onsubmit="return confirm('Удалить партнёра?')">
                        @csrf
                        @method('DELETE')
                        <x-dc.button type="submit" variant="danger" size="s">Удалить</x-dc.button>
                    </form>
                    <div class="flex gap-3">
                        <x-dc.button variant="contour" size="s" href="{{ route('kb.partners.show', $partner) }}">Отмена</x-dc.button>
                        <x-dc.button type="submit" variant="action" size="s">Сохранить</x-dc.button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
