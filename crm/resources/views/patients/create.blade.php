<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Новый пациент</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-4">
                <form method="POST" action="{{ route('patients.store') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium">ФИО *</label>
                        <input name="full_name" value="{{ old('full_name') }}" class="mt-1 w-full border rounded px-3 py-2" required>
                        @error('full_name')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium">Дата рождения</label>
                            <input type="date" name="dob" value="{{ old('dob') }}" class="mt-1 w-full border rounded px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Телефон</label>
                            <input name="phone" value="{{ old('phone') }}" class="mt-1 w-full border rounded px-3 py-2">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" class="mt-1 w-full border rounded px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Страна</label>
                            <input name="country" value="{{ old('country') }}" class="mt-1 w-full border rounded px-3 py-2">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Город</label>
                        <input name="city" value="{{ old('city') }}" class="mt-1 w-full border rounded px-3 py-2">
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Заметки</label>
                        <textarea name="notes" class="mt-1 w-full border rounded px-3 py-2" rows="4">{{ old('notes') }}</textarea>
                    </div>

                    <div class="flex gap-2">
                        <button class="px-3 py-2 bg-blue-600 text-white rounded">Сохранить</button>
                        <a href="{{ route('patients.index') }}" class="px-3 py-2 border rounded">Отмена</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>