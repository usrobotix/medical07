<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('kb.niches.index') }}" class="text-dc-secondary hover:text-dc text-ys-s dc-transition">← Ниши</a>
            <h2 class="text-ys-l font-semibold text-dc leading-tight">Новая ниша</h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('kb.niches.store') }}">
                @csrf
                <x-dc-card padding="lg" shadow="card">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-ys-xs font-medium text-dc-secondary mb-1">Название <span class="text-dc-red-100">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}" required class="block w-full h-9 px-4 text-ys-s rounded-2xs border border-dc-gray-30 bg-surface text-dc dc-transition focus:outline-none focus-visible:ring-2 focus-visible:ring-dc-yellow-100">
                            @error('name')<p class="text-dc-red-100 text-ys-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-ys-xs font-medium text-dc-secondary mb-1">Описание</label>
                            <textarea name="description" rows="3" class="block w-full p-3 text-ys-s rounded-2xs border border-dc-gray-30 bg-surface text-dc dc-transition focus:outline-none focus-visible:ring-2 focus-visible:ring-dc-yellow-100">{{ old('description') }}</textarea>
                        </div>
                        <div>
                            <label class="block text-ys-xs font-medium text-dc-secondary mb-1">Порядок сортировки</label>
                            <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" class="block w-full h-9 px-4 text-ys-s rounded-2xs border border-dc-gray-30 bg-surface text-dc dc-transition focus:outline-none focus-visible:ring-2 focus-visible:ring-dc-yellow-100">
                        </div>
                        <div class="flex justify-end gap-3 pt-2">
                            <x-dc-button variant="contour" size="s" href="{{ route('kb.niches.index') }}">Отмена</x-dc-button>
                            <x-dc-button type="submit" variant="action" size="s">Сохранить</x-dc-button>
                        </div>
                    </div>
                </x-dc-card>
            </form>
        </div>
    </div>
</x-app-layout>
