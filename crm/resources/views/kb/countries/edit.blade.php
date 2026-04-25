<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('kb.countries.show', $country) }}" class="text-dc-secondary hover:text-dc text-ys-s dc-transition">← {{ $country->name_ru }}</a>
            <h2 class="text-ys-l font-semibold text-dc leading-tight">Редактировать страну</h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('kb.countries.update', $country) }}">
                @csrf
                @method('PATCH')
                <x-dc.card padding="lg" shadow="card">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-ys-xs font-medium text-dc-secondary mb-1">ISO2 код <span class="text-dc-red-100">*</span></label>
                            <input type="text" name="iso2" value="{{ old('iso2', $country->iso2) }}" required maxlength="2" class="block w-full h-9 px-4 text-ys-s rounded-2xs border border-dc-gray-30 bg-surface text-dc dc-transition focus:outline-none focus-visible:ring-2 focus-visible:ring-dc-yellow-100 uppercase">
                            @error('iso2')<p class="text-dc-red-100 text-ys-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-ys-xs font-medium text-dc-secondary mb-1">Название (рус) <span class="text-dc-red-100">*</span></label>
                            <input type="text" name="name_ru" value="{{ old('name_ru', $country->name_ru) }}" required class="block w-full h-9 px-4 text-ys-s rounded-2xs border border-dc-gray-30 bg-surface text-dc dc-transition focus:outline-none focus-visible:ring-2 focus-visible:ring-dc-yellow-100">
                            @error('name_ru')<p class="text-dc-red-100 text-ys-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-ys-xs font-medium text-dc-secondary mb-1">Название (eng) <span class="text-dc-red-100">*</span></label>
                            <input type="text" name="name_en" value="{{ old('name_en', $country->name_en) }}" required class="block w-full h-9 px-4 text-ys-s rounded-2xs border border-dc-gray-30 bg-surface text-dc dc-transition focus:outline-none focus-visible:ring-2 focus-visible:ring-dc-yellow-100">
                            @error('name_en')<p class="text-dc-red-100 text-ys-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-ys-xs font-medium text-dc-secondary mb-1">Порядок сортировки</label>
                            <input type="number" name="sort_order" value="{{ old('sort_order', $country->sort_order) }}" class="block w-full h-9 px-4 text-ys-s rounded-2xs border border-dc-gray-30 bg-surface text-dc dc-transition focus:outline-none focus-visible:ring-2 focus-visible:ring-dc-yellow-100">
                        </div>
                        <div class="flex justify-between items-center pt-2">
                            <form method="POST" action="{{ route('kb.countries.destroy', $country) }}" onsubmit="return confirm('Удалить страну?')">
                                @csrf
                                @method('DELETE')
                                <x-dc.button type="submit" variant="danger" size="s">Удалить</x-dc.button>
                            </form>
                            <div class="flex gap-3">
                                <x-dc.button variant="contour" size="s" href="{{ route('kb.countries.show', $country) }}">Отмена</x-dc.button>
                                <x-dc.button type="submit" variant="action" size="s">Сохранить</x-dc.button>
                            </div>
                        </div>
                    </div>
                </x-dc.card>
            </form>
        </div>
    </div>
</x-app-layout>
