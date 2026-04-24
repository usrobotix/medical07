<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Новый пациент</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('patients.store') }}" class="space-y-4">
                    @csrf

                    <div>
                        <x-input-label for="full_name" value="ФИО *" />
                        <x-text-input id="full_name" name="full_name" value="{{ old('full_name') }}" class="mt-1 w-full" required autofocus />
                        <x-input-error :messages="$errors->get('full_name')" class="mt-1" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="dob" value="Дата рождения" />
                            <x-text-input id="dob" type="date" name="dob" value="{{ old('dob') }}" class="mt-1 w-full" />
                        </div>
                        <div>
                            <x-input-label for="phone" value="Телефон" />
                            <x-text-input id="phone" name="phone" value="{{ old('phone') }}" class="mt-1 w-full" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="email" value="Email" />
                            <x-text-input id="email" type="email" name="email" value="{{ old('email') }}" class="mt-1 w-full" />
                        </div>
                        <div>
                            <x-input-label for="country" value="Страна" />
                            <x-text-input id="country" name="country" value="{{ old('country') }}" class="mt-1 w-full" />
                        </div>
                    </div>

                    <div>
                        <x-input-label for="city" value="Город" />
                        <x-text-input id="city" name="city" value="{{ old('city') }}" class="mt-1 w-full" />
                    </div>

                    <div>
                        <x-input-label for="notes" value="Заметки" />
                        <textarea id="notes" name="notes" rows="4"
                            class="mt-1 w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('notes') }}</textarea>
                    </div>

                    <div class="flex gap-2 pt-2">
                        <x-primary-button>Сохранить</x-primary-button>
                        <x-secondary-button type="button" onclick="window.location='{{ route('patients.index') }}'">
                            Отмена
                        </x-secondary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>