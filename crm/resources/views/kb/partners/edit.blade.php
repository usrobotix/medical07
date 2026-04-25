<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('kb.partners.show', $partner) }}" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 text-sm">← {{ $partner->name }}</a>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Редактировать партнёра
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('kb.partners.update', $partner) }}" class="space-y-6">
                @csrf
                @method('PATCH')

                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6 space-y-5">
                    <h3 class="text-base font-semibold text-gray-800 dark:text-gray-100">Основная информация</h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Название <span class="text-red-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name', $partner->name) }}" required
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Тип <span class="text-red-500">*</span></label>
                            <select name="type" required class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                <option value="clinic" @selected(old('type', $partner->type) === 'clinic')>Клиника</option>
                                <option value="translator" @selected(old('type', $partner->type) === 'translator')>Переводчик</option>
                                <option value="curator" @selected(old('type', $partner->type) === 'curator')>Куратор</option>
                            </select>
                            @error('type')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Статус <span class="text-red-500">*</span></label>
                            <select name="status" required class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                <option value="new" @selected(old('status', $partner->status) === 'new')>Новый</option>
                                <option value="verified" @selected(old('status', $partner->status) === 'verified')>Верифицирован</option>
                                <option value="active" @selected(old('status', $partner->status) === 'active')>Активен</option>
                                <option value="frozen" @selected(old('status', $partner->status) === 'frozen')>Заморожен</option>
                            </select>
                            @error('status')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Слой</label>
                            <select name="partner_layer_id" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                <option value="">— не выбрано —</option>
                                @foreach($layers as $layer)
                                    <option value="{{ $layer->id }}" @selected(old('partner_layer_id', $partner->partner_layer_id) == $layer->id)>{{ $layer->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Основная страна</label>
                            <select name="country_id" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                <option value="">— не выбрано —</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country->id }}" @selected(old('country_id', $partner->country_id) == $country->id)>{{ $country->name_ru }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Город</label>
                            <input type="text" name="city" value="{{ old('city', $partner->city) }}"
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Языки</label>
                            <input type="text" name="languages" value="{{ old('languages', $partner->languages) }}" placeholder="ru, en"
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Ниши</label>
                        <div class="flex flex-wrap gap-2 mt-1">
                            @php $selectedNiches = old('niche_ids', $partner->niches->pluck('id')->toArray()); @endphp
                            @foreach($niches as $niche)
                                <label class="inline-flex items-center gap-1.5 text-sm text-gray-700 dark:text-gray-300">
                                    <input type="checkbox" name="niche_ids[]" value="{{ $niche->id }}"
                                        @checked(in_array($niche->id, $selectedNiches))
                                        class="rounded border-gray-300 dark:border-gray-600 text-indigo-600">
                                    {{ $niche->name }}
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6 space-y-4">
                    <h3 class="text-base font-semibold text-gray-800 dark:text-gray-100">Контакты</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Контактное лицо</label>
                            <input type="text" name="contact_name" value="{{ old('contact_name', $partner->contact_name) }}"
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                            <input type="email" name="contact_email" value="{{ old('contact_email', $partner->contact_email) }}"
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Телефон</label>
                            <input type="text" name="contact_phone" value="{{ old('contact_phone', $partner->contact_phone) }}"
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">WhatsApp</label>
                            <input type="text" name="contact_whatsapp" value="{{ old('contact_whatsapp', $partner->contact_whatsapp) }}"
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Telegram</label>
                            <input type="text" name="contact_telegram" value="{{ old('contact_telegram', $partner->contact_telegram) }}"
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Сайт</label>
                            <input type="url" name="website_url" value="{{ old('website_url', $partner->website_url) }}"
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6 space-y-4">
                    <h3 class="text-base font-semibold text-gray-800 dark:text-gray-100">SLA и финансы</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Время ответа (ч)</label>
                            <input type="number" name="sla_response_hours" value="{{ old('sla_response_hours', $partner->sla_response_hours) }}" min="0"
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Срок результата (дн)</label>
                            <input type="number" name="sla_result_days" value="{{ old('sla_result_days', $partner->sla_result_days) }}" min="0"
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Рейтинг верификации (0–100)</label>
                            <input type="number" name="verification_score" value="{{ old('verification_score', $partner->verification_score) }}" min="0" max="100" step="0.1"
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        </div>
                        <div class="flex items-center gap-2 pt-6">
                            <input type="hidden" name="invoice_required" value="0">
                            <input type="checkbox" name="invoice_required" value="1" id="invoice_required"
                                @checked(old('invoice_required', $partner->invoice_required))
                                class="rounded border-gray-300 dark:border-gray-600 text-indigo-600">
                            <label for="invoice_required" class="text-sm text-gray-700 dark:text-gray-300">Инвойс обязателен</label>
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Условия оплаты</label>
                            <textarea name="pricing_notes" rows="2"
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">{{ old('pricing_notes', $partner->pricing_notes) }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Заметки</label>
                    <textarea name="notes" rows="4"
                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">{{ old('notes', $partner->notes) }}</textarea>
                </div>

                <div class="flex justify-between items-center">
                    <form method="POST" action="{{ route('kb.partners.destroy', $partner) }}" onsubmit="return confirm('Удалить партнёра?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 text-sm bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-200 rounded-md hover:bg-red-200 dark:hover:bg-red-800 transition">Удалить</button>
                    </form>
                    <div class="flex gap-3">
                        <a href="{{ route('kb.partners.show', $partner) }}" class="px-4 py-2 text-sm bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600 transition">Отмена</a>
                        <button type="submit" class="px-6 py-2 text-sm bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition font-medium">Сохранить</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
