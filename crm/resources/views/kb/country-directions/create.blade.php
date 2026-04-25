<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('kb.country-directions.index') }}" class="text-dc-secondary hover:text-dc text-ys-s dc-transition">← Направления</a>
            <h2 class="text-ys-l font-semibold text-dc leading-tight">Новое направление</h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('kb.country-directions.store') }}">
                @csrf
                <x-dc.card padding="lg" shadow="card">
                    <div class="space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-ys-xs font-medium text-dc-secondary mb-1">Страна <span class="text-dc-red-100">*</span></label>
                                <select name="country_id" required class="block w-full h-9 px-3 text-ys-s rounded-2xs border border-dc-gray-30 bg-surface text-dc dc-transition focus:outline-none focus-visible:ring-2 focus-visible:ring-dc-yellow-100">
                                    <option value="">— выберите —</option>
                                    @foreach($countries as $country)
                                        <option value="{{ $country->id }}" @selected(old('country_id') == $country->id)>{{ $country->name_ru }}</option>
                                    @endforeach
                                </select>
                                @error('country_id')<p class="text-dc-red-100 text-ys-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-ys-xs font-medium text-dc-secondary mb-1">Ниша <span class="text-dc-red-100">*</span></label>
                                <select name="niche_id" required class="block w-full h-9 px-3 text-ys-s rounded-2xs border border-dc-gray-30 bg-surface text-dc dc-transition focus:outline-none focus-visible:ring-2 focus-visible:ring-dc-yellow-100">
                                    <option value="">— выберите —</option>
                                    @foreach($niches as $niche)
                                        <option value="{{ $niche->id }}" @selected(old('niche_id') == $niche->id)>{{ $niche->name }}</option>
                                    @endforeach
                                </select>
                                @error('niche_id')<p class="text-dc-red-100 text-ys-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>
                        <div>
                            <label class="block text-ys-xs font-medium text-dc-secondary mb-1">Заголовок <span class="text-dc-red-100">*</span></label>
                            <input type="text" name="title" value="{{ old('title') }}" required class="block w-full h-9 px-4 text-ys-s rounded-2xs border border-dc-gray-30 bg-surface text-dc dc-transition focus:outline-none focus-visible:ring-2 focus-visible:ring-dc-yellow-100">
                            @error('title')<p class="text-dc-red-100 text-ys-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-ys-xs font-medium text-dc-secondary mb-1">На что обращать внимание</label>
                            <textarea name="what_to_look_for" rows="4" class="block w-full p-3 text-ys-s rounded-2xs border border-dc-gray-30 bg-surface text-dc dc-transition focus:outline-none focus-visible:ring-2 focus-visible:ring-dc-yellow-100">{{ old('what_to_look_for') }}</textarea>
                        </div>
                        <div>
                            <label class="block text-ys-xs font-medium text-dc-secondary mb-1">Поисковые запросы</label>
                            <textarea name="search_queries" rows="3" class="block w-full p-3 text-ys-s rounded-2xs border border-dc-gray-30 bg-surface text-dc dc-transition focus:outline-none focus-visible:ring-2 focus-visible:ring-dc-yellow-100 font-mono">{{ old('search_queries') }}</textarea>
                        </div>
                        <div>
                            <label class="block text-ys-xs font-medium text-dc-secondary mb-1">Заметки</label>
                            <textarea name="notes" rows="2" class="block w-full p-3 text-ys-s rounded-2xs border border-dc-gray-30 bg-surface text-dc dc-transition focus:outline-none focus-visible:ring-2 focus-visible:ring-dc-yellow-100">{{ old('notes') }}</textarea>
                        </div>
                        <div>
                            <label class="block text-ys-xs font-medium text-dc-secondary mb-1">Порядок сортировки</label>
                            <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" class="block w-full h-9 px-4 text-ys-s rounded-2xs border border-dc-gray-30 bg-surface text-dc dc-transition focus:outline-none focus-visible:ring-2 focus-visible:ring-dc-yellow-100">
                        </div>
                        <div class="flex justify-end gap-3 pt-2">
                            <x-dc.button variant="contour" size="s" href="{{ route('kb.country-directions.index') }}">Отмена</x-dc.button>
                            <x-dc.button type="submit" variant="action" size="s">Сохранить</x-dc.button>
                        </div>
                    </div>
                </x-dc.card>
            </form>
        </div>
    </div>
</x-app-layout>
