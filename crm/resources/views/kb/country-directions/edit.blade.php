<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('kb.country-directions.show', $countryDirection) }}" class="text-dc-secondary hover:text-dc text-ys-s dc-transition">← {{ $countryDirection->title }}</a>
            <h2 class="text-ys-l font-semibold text-dc leading-tight">Редактировать направление</h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <form id="form-update-country-direction" method="POST" action="{{ route('kb.country-directions.update', $countryDirection) }}">
                @csrf
                @method('PATCH')
                <x-dc.card padding="lg" shadow="card">
                    <div class="space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-ys-xs font-medium text-dc-secondary mb-1">Страна <span class="text-dc-red-100">*</span></label>
                                <select name="country_id" required class="block w-full h-9 px-3 text-ys-s rounded-2xs border border-dc-gray-30 bg-surface text-dc dc-transition focus:outline-none focus-visible:ring-2 focus-visible:ring-dc-yellow-100">
                                    <option value="">— выберите —</option>
                                    @foreach($countries as $country)
                                        <option value="{{ $country->id }}" @selected(old('country_id', $countryDirection->country_id) == $country->id)>{{ $country->name_ru }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-ys-xs font-medium text-dc-secondary mb-1">Ниша <span class="text-dc-red-100">*</span></label>
                                <select name="niche_id" required class="block w-full h-9 px-3 text-ys-s rounded-2xs border border-dc-gray-30 bg-surface text-dc dc-transition focus:outline-none focus-visible:ring-2 focus-visible:ring-dc-yellow-100">
                                    <option value="">— выберите —</option>
                                    @foreach($niches as $niche)
                                        <option value="{{ $niche->id }}" @selected(old('niche_id', $countryDirection->niche_id) == $niche->id)>{{ $niche->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="block text-ys-xs font-medium text-dc-secondary mb-1">Заголовок <span class="text-dc-red-100">*</span></label>
                            <input type="text" name="title" value="{{ old('title', $countryDirection->title) }}" required class="block w-full h-9 px-4 text-ys-s rounded-2xs border border-dc-gray-30 bg-surface text-dc dc-transition focus:outline-none focus-visible:ring-2 focus-visible:ring-dc-yellow-100">
                        </div>
                        <div>
                            <label class="block text-ys-xs font-medium text-dc-secondary mb-1">На что обращать внимание</label>
                            <textarea name="what_to_look_for" rows="4" class="block w-full p-3 text-ys-s rounded-2xs border border-dc-gray-30 bg-surface text-dc dc-transition focus:outline-none focus-visible:ring-2 focus-visible:ring-dc-yellow-100">{{ old('what_to_look_for', $countryDirection->what_to_look_for) }}</textarea>
                        </div>
                        <div>
                            <label class="block text-ys-xs font-medium text-dc-secondary mb-1">Поисковые запросы</label>
                            <textarea name="search_queries" rows="3" class="block w-full p-3 text-ys-s rounded-2xs border border-dc-gray-30 bg-surface text-dc dc-transition focus:outline-none focus-visible:ring-2 focus-visible:ring-dc-yellow-100 font-mono">{{ old('search_queries', $countryDirection->search_queries) }}</textarea>
                        </div>
                        <div>
                            <label class="block text-ys-xs font-medium text-dc-secondary mb-1">Заметки</label>
                            <textarea name="notes" rows="2" class="block w-full p-3 text-ys-s rounded-2xs border border-dc-gray-30 bg-surface text-dc dc-transition focus:outline-none focus-visible:ring-2 focus-visible:ring-dc-yellow-100">{{ old('notes', $countryDirection->notes) }}</textarea>
                        </div>
                        <div>
                            <label class="block text-ys-xs font-medium text-dc-secondary mb-1">Порядок сортировки</label>
                            <input type="number" name="sort_order" value="{{ old('sort_order', $countryDirection->sort_order) }}" class="block w-full h-9 px-4 text-ys-s rounded-2xs border border-dc-gray-30 bg-surface text-dc dc-transition focus:outline-none focus-visible:ring-2 focus-visible:ring-dc-yellow-100">
                        </div>
                    </div>
                </x-dc.card>
            </form>
            <div class="flex justify-between items-center pt-4">
                <form method="POST" action="{{ route('kb.country-directions.destroy', $countryDirection) }}" onsubmit="return confirm('Удалить направление?')">
                    @csrf
                    @method('DELETE')
                    <x-dc.button type="submit" variant="danger" size="s">Удалить</x-dc.button>
                </form>
                <div class="flex gap-3">
                    <x-dc.button variant="contour" size="s" href="{{ route('kb.country-directions.show', $countryDirection) }}">Отмена</x-dc.button>
                    <x-dc.button form="form-update-country-direction" type="submit" variant="action" size="s">Сохранить</x-dc.button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
