<x-app-layout>
    <x-slot name="header">
        <h2 class="text-ys-l font-semibold text-dc leading-tight">Новый пациент</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <x-dc-card padding="lg" shadow="card">
                <form method="POST" action="{{ route('patients.store') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label for="full_name" class="block text-ys-xs font-medium text-dc-secondary mb-1">ФИО *</label>
                        <x-dc-input id="full_name" name="full_name" value="{{ old('full_name') }}" class="w-full" required autofocus :error="$errors->first('full_name')" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="dob" class="block text-ys-xs font-medium text-dc-secondary mb-1">Дата рождения</label>
                            <x-dc-input id="dob" type="date" name="dob" value="{{ old('dob') }}" class="w-full" />
                        </div>
                        <div>
                            <label for="phone" class="block text-ys-xs font-medium text-dc-secondary mb-1">Телефон</label>
                            <x-dc-input id="phone" name="phone" value="{{ old('phone') }}" class="w-full" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="email" class="block text-ys-xs font-medium text-dc-secondary mb-1">Email</label>
                            <x-dc-input id="email" type="email" name="email" value="{{ old('email') }}" class="w-full" />
                        </div>
                        <div>
                            <label for="country" class="block text-ys-xs font-medium text-dc-secondary mb-1">Страна</label>
                            <x-dc-input id="country" name="country" value="{{ old('country') }}" class="w-full" />
                        </div>
                    </div>

                    <div>
                        <label for="city" class="block text-ys-xs font-medium text-dc-secondary mb-1">Город</label>
                        <x-dc-input id="city" name="city" value="{{ old('city') }}" class="w-full" />
                    </div>

                    <div>
                        <label for="notes" class="block text-ys-xs font-medium text-dc-secondary mb-1">Заметки</label>
                        <textarea id="notes" name="notes" rows="4"
                            class="w-full text-ys-s rounded-2xs border dc-transition bg-surface focus:outline-none focus-visible:ring-2 focus-visible:ring-dc-yellow-100 p-3"
                            style="border-color:var(--color-border);color:var(--color-text)">{{ old('notes') }}</textarea>
                    </div>

                    <div class="flex gap-2 pt-2">
                        <x-dc-button type="submit" variant="action" size="m">Сохранить</x-dc-button>
                        <x-dc-button variant="contour" size="m" href="{{ route('patients.index') }}">Отмена</x-dc-button>
                    </div>
                </form>
            </x-dc-card>
        </div>
    </div>
</x-app-layout>
