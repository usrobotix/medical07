<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('kb.niches.show', $niche) }}" class="text-dc-secondary hover:text-dc text-ys-s dc-transition">← {{ $niche->name }}</a>
            <h2 class="text-ys-l font-semibold text-dc leading-tight">Редактировать нишу</h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <form id="form-update-niche" method="POST" action="{{ route('kb.niches.update', $niche) }}">
                @csrf
                @method('PATCH')
                <x-dc.card padding="lg" shadow="card">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-ys-xs font-medium text-dc-secondary mb-1">Название <span class="text-dc-red-100">*</span></label>
                            <input type="text" name="name" value="{{ old('name', $niche->name) }}" required class="block w-full h-9 px-4 text-ys-s rounded-2xs border border-dc-gray-30 bg-surface text-dc dc-transition focus:outline-none focus-visible:ring-2 focus-visible:ring-dc-yellow-100">
                            @error('name')<p class="text-dc-red-100 text-ys-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-ys-xs font-medium text-dc-secondary mb-1">Описание</label>
                            <textarea name="description" rows="3" class="block w-full p-3 text-ys-s rounded-2xs border border-dc-gray-30 bg-surface text-dc dc-transition focus:outline-none focus-visible:ring-2 focus-visible:ring-dc-yellow-100">{{ old('description', $niche->description) }}</textarea>
                        </div>
                        <div>
                            <label class="block text-ys-xs font-medium text-dc-secondary mb-1">Порядок сортировки</label>
                            <input type="number" name="sort_order" value="{{ old('sort_order', $niche->sort_order) }}" class="block w-full h-9 px-4 text-ys-s rounded-2xs border border-dc-gray-30 bg-surface text-dc dc-transition focus:outline-none focus-visible:ring-2 focus-visible:ring-dc-yellow-100">
                        </div>
                    </div>
                </x-dc.card>
            </form>
            <div class="flex justify-between items-center pt-4">
                <form method="POST" action="{{ route('kb.niches.destroy', $niche) }}" onsubmit="return confirm('Удалить нишу?')">
                    @csrf
                    @method('DELETE')
                    <x-dc.button type="submit" variant="danger" size="s">Удалить</x-dc.button>
                </form>
                <div class="flex gap-3">
                    <x-dc.button variant="contour" size="s" href="{{ route('kb.niches.show', $niche) }}">Отмена</x-dc.button>
                    <x-dc.button form="form-update-niche" type="submit" variant="action" size="s">Сохранить</x-dc.button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
