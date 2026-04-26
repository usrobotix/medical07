<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('kb.partners.show', $partner) }}" class="text-dc-secondary hover:text-dc text-ys-s dc-transition">← {{ $partner->name }}</a>
            <h2 class="text-ys-l font-semibold text-dc leading-tight">Редактировать research</h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <form id="form-update-research" method="POST" action="{{ route('kb.partners.research.update', $partner) }}" class="space-y-6">
                @csrf
                @method('PATCH')

                {{-- Basic fields --}}
                <x-dc.card padding="lg" shadow="card">
                    <h3 class="text-ys-s font-semibold text-dc mb-5">Основные данные</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="sm:col-span-2">
                            <label class="block text-ys-xs font-medium text-dc-secondary mb-1">Направление</label>
                            <input type="text" name="direction" value="{{ old('direction', $rp->direction) }}" class="block w-full h-9 px-4 text-ys-s rounded-2xs border border-dc-gray-30 bg-surface text-dc dc-transition focus:outline-none focus-visible:ring-2 focus-visible:ring-dc-yellow-100">
                            @error('direction')<p class="text-dc-red-100 text-ys-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-ys-xs font-medium text-dc-secondary mb-1">Адрес</label>
                            <input type="text" name="address" value="{{ old('address', $rp->address) }}" class="block w-full h-9 px-4 text-ys-s rounded-2xs border border-dc-gray-30 bg-surface text-dc dc-transition focus:outline-none focus-visible:ring-2 focus-visible:ring-dc-yellow-100">
                            @error('address')<p class="text-dc-red-100 text-ys-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-ys-xs font-medium text-dc-secondary mb-1">Страница для иностранных пациентов (URL)</label>
                            <input type="url" name="international_page_url" value="{{ old('international_page_url', $rp->international_page_url) }}" placeholder="https://..." class="block w-full h-9 px-4 text-ys-s rounded-2xs border border-dc-gray-30 bg-surface text-dc dc-transition focus:outline-none focus-visible:ring-2 focus-visible:ring-dc-yellow-100">
                            @error('international_page_url')<p class="text-dc-red-100 text-ys-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-ys-xs font-medium text-dc-secondary mb-1">Приём иностранцев — статус</label>
                            <input type="text" name="accepts_foreigners_status" value="{{ old('accepts_foreigners_status', $rp->accepts_foreigners_status) }}" class="block w-full h-9 px-4 text-ys-s rounded-2xs border border-dc-gray-30 bg-surface text-dc dc-transition focus:outline-none focus-visible:ring-2 focus-visible:ring-dc-yellow-100">
                            @error('accepts_foreigners_status')<p class="text-dc-red-100 text-ys-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-ys-xs font-medium text-dc-secondary mb-1">Приём иностранцев — источник (URL)</label>
                            <input type="url" name="accepts_foreigners_source_url" value="{{ old('accepts_foreigners_source_url', $rp->accepts_foreigners_source_url) }}" placeholder="https://..." class="block w-full h-9 px-4 text-ys-s rounded-2xs border border-dc-gray-30 bg-surface text-dc dc-transition focus:outline-none focus-visible:ring-2 focus-visible:ring-dc-yellow-100">
                            @error('accepts_foreigners_source_url')<p class="text-dc-red-100 text-ys-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-ys-xs font-medium text-dc-secondary mb-1">Приём пациентов из РФ — статус</label>
                            <input type="text" name="accepts_ru_status" value="{{ old('accepts_ru_status', $rp->accepts_ru_status) }}" class="block w-full h-9 px-4 text-ys-s rounded-2xs border border-dc-gray-30 bg-surface text-dc dc-transition focus:outline-none focus-visible:ring-2 focus-visible:ring-dc-yellow-100">
                            @error('accepts_ru_status')<p class="text-dc-red-100 text-ys-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-ys-xs font-medium text-dc-secondary mb-1">Приём пациентов из РФ — источник (URL)</label>
                            <input type="url" name="accepts_ru_source_url" value="{{ old('accepts_ru_source_url', $rp->accepts_ru_source_url) }}" placeholder="https://..." class="block w-full h-9 px-4 text-ys-s rounded-2xs border border-dc-gray-30 bg-surface text-dc dc-transition focus:outline-none focus-visible:ring-2 focus-visible:ring-dc-yellow-100">
                            @error('accepts_ru_source_url')<p class="text-dc-red-100 text-ys-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-ys-xs font-medium text-dc-secondary mb-1">Режим работы</label>
                            <input type="text" name="working_hours" value="{{ old('working_hours', $rp->working_hours) }}" class="block w-full h-9 px-4 text-ys-s rounded-2xs border border-dc-gray-30 bg-surface text-dc dc-transition focus:outline-none focus-visible:ring-2 focus-visible:ring-dc-yellow-100">
                            @error('working_hours')<p class="text-dc-red-100 text-ys-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-ys-xs font-medium text-dc-secondary mb-1">Дата последней проверки</label>
                            <input type="date" name="last_checked_at" value="{{ old('last_checked_at', $rp->last_checked_at?->format('Y-m-d')) }}" class="block w-full h-9 px-4 text-ys-s rounded-2xs border border-dc-gray-30 bg-surface text-dc dc-transition focus:outline-none focus-visible:ring-2 focus-visible:ring-dc-yellow-100">
                            @error('last_checked_at')<p class="text-dc-red-100 text-ys-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-ys-xs font-medium text-dc-secondary mb-1">Путь к источнику (source_path)</label>
                            <input type="text" name="source_path" value="{{ old('source_path', $rp->source_path) }}" class="block w-full h-9 px-4 text-ys-s rounded-2xs border border-dc-gray-30 bg-surface text-dc dc-transition focus:outline-none focus-visible:ring-2 focus-visible:ring-dc-yellow-100">
                            @error('source_path')<p class="text-dc-red-100 text-ys-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        @if($rp->imported_at)
                            <div>
                                <label class="block text-ys-xs font-medium text-dc-secondary mb-1">Дата импорта (read-only)</label>
                                <input type="text" value="{{ $rp->imported_at->format('d.m.Y H:i') }}" disabled class="block w-full h-9 px-4 text-ys-s rounded-2xs border border-dc-gray-30 bg-dc-subtle text-dc-secondary cursor-not-allowed">
                            </div>
                        @endif
                    </div>
                </x-dc.card>

                {{-- JSON: key_services --}}
                <x-dc.card padding="lg" shadow="card">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-ys-s font-semibold text-dc">Ключевые услуги</h3>
                        <button type="button" onclick="addRow('key_services')" class="text-ys-xs text-dc-primary hover:underline dc-transition">+ добавить</button>
                    </div>
                    <div id="rows-key_services" class="space-y-2">
                        @php $services = old('key_services', $rp->key_services ?? []); @endphp
                        @foreach($services as $i => $item)
                            <div class="flex gap-2 items-center">
                                <input type="text" name="key_services[]" value="{{ $item }}" class="block flex-1 h-9 px-4 text-ys-s rounded-2xs border border-dc-gray-30 bg-surface text-dc dc-transition focus:outline-none focus-visible:ring-2 focus-visible:ring-dc-yellow-100">
                                <button type="button" onclick="removeRow(this)" class="text-dc-secondary hover:text-dc-red-100 dc-transition text-ys-xs">✕</button>
                            </div>
                        @endforeach
                        @if(empty($services))
                            <div class="flex gap-2 items-center">
                                <input type="text" name="key_services[]" value="" class="block flex-1 h-9 px-4 text-ys-s rounded-2xs border border-dc-gray-30 bg-surface text-dc dc-transition focus:outline-none focus-visible:ring-2 focus-visible:ring-dc-yellow-100">
                                <button type="button" onclick="removeRow(this)" class="text-dc-secondary hover:text-dc-red-100 dc-transition text-ys-xs">✕</button>
                            </div>
                        @endif
                    </div>
                    @error('key_services.*')<p class="text-dc-red-100 text-ys-xs mt-1">{{ $message }}</p>@enderror
                </x-dc.card>

                {{-- JSON: doctors --}}
                <x-dc.card padding="lg" shadow="card">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-ys-s font-semibold text-dc">Врачи</h3>
                        <button type="button" onclick="addRow('doctors')" class="text-ys-xs text-dc-primary hover:underline dc-transition">+ добавить</button>
                    </div>
                    <div id="rows-doctors" class="space-y-2">
                        @php $doctors = old('doctors', $rp->doctors ?? []); @endphp
                        @foreach($doctors as $item)
                            <div class="flex gap-2 items-center">
                                <input type="text" name="doctors[]" value="{{ is_array($item) ? json_encode($item, JSON_UNESCAPED_UNICODE) : $item }}" class="block flex-1 h-9 px-4 text-ys-s rounded-2xs border border-dc-gray-30 bg-surface text-dc dc-transition focus:outline-none focus-visible:ring-2 focus-visible:ring-dc-yellow-100">
                                <button type="button" onclick="removeRow(this)" class="text-dc-secondary hover:text-dc-red-100 dc-transition text-ys-xs">✕</button>
                            </div>
                        @endforeach
                        @if(empty($doctors))
                            <div class="flex gap-2 items-center">
                                <input type="text" name="doctors[]" value="" class="block flex-1 h-9 px-4 text-ys-s rounded-2xs border border-dc-gray-30 bg-surface text-dc dc-transition focus:outline-none focus-visible:ring-2 focus-visible:ring-dc-yellow-100">
                                <button type="button" onclick="removeRow(this)" class="text-dc-secondary hover:text-dc-red-100 dc-transition text-ys-xs">✕</button>
                            </div>
                        @endif
                    </div>
                </x-dc.card>

                {{-- JSON: prices --}}
                <x-dc.card padding="lg" shadow="card">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-ys-s font-semibold text-dc">Цены</h3>
                        <button type="button" onclick="addRow('prices')" class="text-ys-xs text-dc-primary hover:underline dc-transition">+ добавить</button>
                    </div>
                    <div id="rows-prices" class="space-y-2">
                        @php $prices = old('prices', $rp->prices ?? []); @endphp
                        @foreach($prices as $item)
                            <div class="flex gap-2 items-center">
                                <input type="text" name="prices[]" value="{{ is_array($item) ? json_encode($item, JSON_UNESCAPED_UNICODE) : $item }}" class="block flex-1 h-9 px-4 text-ys-s rounded-2xs border border-dc-gray-30 bg-surface text-dc dc-transition focus:outline-none focus-visible:ring-2 focus-visible:ring-dc-yellow-100">
                                <button type="button" onclick="removeRow(this)" class="text-dc-secondary hover:text-dc-red-100 dc-transition text-ys-xs">✕</button>
                            </div>
                        @endforeach
                        @if(empty($prices))
                            <div class="flex gap-2 items-center">
                                <input type="text" name="prices[]" value="" class="block flex-1 h-9 px-4 text-ys-s rounded-2xs border border-dc-gray-30 bg-surface text-dc dc-transition focus:outline-none focus-visible:ring-2 focus-visible:ring-dc-yellow-100">
                                <button type="button" onclick="removeRow(this)" class="text-dc-secondary hover:text-dc-red-100 dc-transition text-ys-xs">✕</button>
                            </div>
                        @endif
                    </div>
                </x-dc.card>

                {{-- JSON: reviews --}}
                <x-dc.card padding="lg" shadow="card">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-ys-s font-semibold text-dc">Отзывы</h3>
                        <button type="button" onclick="addRow('reviews')" class="text-ys-xs text-dc-primary hover:underline dc-transition">+ добавить</button>
                    </div>
                    <div id="rows-reviews" class="space-y-2">
                        @php $reviews = old('reviews', $rp->reviews ?? []); @endphp
                        @foreach($reviews as $item)
                            <div class="flex gap-2 items-center">
                                <input type="text" name="reviews[]" value="{{ is_array($item) ? json_encode($item, JSON_UNESCAPED_UNICODE) : $item }}" class="block flex-1 h-9 px-4 text-ys-s rounded-2xs border border-dc-gray-30 bg-surface text-dc dc-transition focus:outline-none focus-visible:ring-2 focus-visible:ring-dc-yellow-100">
                                <button type="button" onclick="removeRow(this)" class="text-dc-secondary hover:text-dc-red-100 dc-transition text-ys-xs">✕</button>
                            </div>
                        @endforeach
                        @if(empty($reviews))
                            <div class="flex gap-2 items-center">
                                <input type="text" name="reviews[]" value="" class="block flex-1 h-9 px-4 text-ys-s rounded-2xs border border-dc-gray-30 bg-surface text-dc dc-transition focus:outline-none focus-visible:ring-2 focus-visible:ring-dc-yellow-100">
                                <button type="button" onclick="removeRow(this)" class="text-dc-secondary hover:text-dc-red-100 dc-transition text-ys-xs">✕</button>
                            </div>
                        @endif
                    </div>
                </x-dc.card>

                {{-- JSON: sources --}}
                <x-dc.card padding="lg" shadow="card">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-ys-s font-semibold text-dc">Источники</h3>
                        <button type="button" onclick="addRow('sources')" class="text-ys-xs text-dc-primary hover:underline dc-transition">+ добавить</button>
                    </div>
                    <div id="rows-sources" class="space-y-2">
                        @php $sources = old('sources', $rp->sources ?? []); @endphp
                        @foreach($sources as $item)
                            <div class="flex gap-2 items-center">
                                <input type="text" name="sources[]" value="{{ is_array($item) ? json_encode($item, JSON_UNESCAPED_UNICODE) : $item }}" class="block flex-1 h-9 px-4 text-ys-s rounded-2xs border border-dc-gray-30 bg-surface text-dc dc-transition focus:outline-none focus-visible:ring-2 focus-visible:ring-dc-yellow-100">
                                <button type="button" onclick="removeRow(this)" class="text-dc-secondary hover:text-dc-red-100 dc-transition text-ys-xs">✕</button>
                            </div>
                        @endforeach
                        @if(empty($sources))
                            <div class="flex gap-2 items-center">
                                <input type="text" name="sources[]" value="" class="block flex-1 h-9 px-4 text-ys-s rounded-2xs border border-dc-gray-30 bg-surface text-dc dc-transition focus:outline-none focus-visible:ring-2 focus-visible:ring-dc-yellow-100">
                                <button type="button" onclick="removeRow(this)" class="text-dc-secondary hover:text-dc-red-100 dc-transition text-ys-xs">✕</button>
                            </div>
                        @endif
                    </div>
                </x-dc.card>

                {{-- Longtext: raw_clinic_yaml --}}
                <x-dc.card padding="lg" shadow="card">
                    <h3 class="text-ys-s font-semibold text-dc mb-3">Исходный YAML (raw_clinic_yaml)</h3>
                    <textarea name="raw_clinic_yaml" rows="14" class="block w-full p-3 text-ys-s font-mono rounded-2xs border border-dc-gray-30 bg-surface text-dc dc-transition focus:outline-none focus-visible:ring-2 focus-visible:ring-dc-yellow-100">{{ old('raw_clinic_yaml', $rp->raw_clinic_yaml) }}</textarea>
                    @error('raw_clinic_yaml')<p class="text-dc-red-100 text-ys-xs mt-1">{{ $message }}</p>@enderror
                </x-dc.card>

                {{-- Longtext: review_markdown --}}
                <x-dc.card padding="lg" shadow="card">
                    <h3 class="text-ys-s font-semibold text-dc mb-3">Обзор клиники (review_markdown)</h3>
                    <p class="text-ys-xs text-dc-secondary mb-2">Markdown-разметка. Отображается на странице партнёра как отформатированный текст.</p>
                    <textarea name="review_markdown" rows="18" class="block w-full p-3 text-ys-s font-mono rounded-2xs border border-dc-gray-30 bg-surface text-dc dc-transition focus:outline-none focus-visible:ring-2 focus-visible:ring-dc-yellow-100">{{ old('review_markdown', $rp->review_markdown) }}</textarea>
                    @error('review_markdown')<p class="text-dc-red-100 text-ys-xs mt-1">{{ $message }}</p>@enderror
                </x-dc.card>

            </form>

            <div class="flex justify-end gap-3 mt-2">
                <x-dc.button variant="contour" size="s" href="{{ route('kb.partners.show', $partner) }}">Отмена</x-dc.button>
                <x-dc.button form="form-update-research" type="submit" variant="action" size="s">Сохранить</x-dc.button>
            </div>
        </div>
    </div>

    <script>
        function addRow(field) {
            const container = document.getElementById('rows-' + field);
            const div = document.createElement('div');
            div.className = 'flex gap-2 items-center';
            div.innerHTML = '<input type="text" name="' + field + '[]" value="" class="block flex-1 h-9 px-4 text-ys-s rounded-2xs border border-dc-gray-30 bg-surface text-dc dc-transition focus:outline-none focus-visible:ring-2 focus-visible:ring-dc-yellow-100"><button type="button" onclick="removeRow(this)" class="text-dc-secondary hover:text-dc-red-100 dc-transition text-ys-xs">✕</button>';
            container.appendChild(div);
            div.querySelector('input').focus();
        }

        function removeRow(btn) {
            btn.closest('.flex').remove();
        }
    </script>
</x-app-layout>
