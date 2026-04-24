<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Новый кейс</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('cases.store') }}" class="space-y-4">
                    @csrf

                    <div>
                        <x-input-label for="patient_id" value="Пациент *" />
                        <select id="patient_id" name="patient_id" required
                            class="mt-1 w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-indigo-500 rounded-md shadow-sm">
                            <option value="">— выберите —</option>
                            @foreach($patients as $p)
                                <option value="{{ $p->id }}" @selected(old('patient_id') == $p->id)>
                                    #{{ $p->id }} — {{ $p->full_name }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('patient_id')" class="mt-1" />
                    </div>

                    <div>
                        <x-input-label for="pipeline_status_id" value="Статус (этап) *" />
                        <select id="pipeline_status_id" name="pipeline_status_id" required
                            class="mt-1 w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-indigo-500 rounded-md shadow-sm">
                            @foreach($statuses as $s)
                                <option value="{{ $s->id }}" @selected(old('pipeline_status_id') == $s->id)>
                                    {{ $s->sort_order }} — {{ $s->name }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('pipeline_status_id')" class="mt-1" />
                    </div>

                    <div>
                        <x-input-label for="title" value="Заголовок" />
                        <x-text-input id="title" name="title" value="{{ old('title') }}" class="mt-1 w-full" />
                    </div>

                    <div>
                        <x-input-label for="problem" value="Описание / запрос" />
                        <textarea id="problem" name="problem" rows="4"
                            class="mt-1 w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('problem') }}</textarea>
                    </div>

                    <div>
                        <x-input-label for="priority" value="Приоритет (1..5) *" />
                        <x-text-input id="priority" type="number" name="priority" value="{{ old('priority', 3) }}" min="1" max="5" class="mt-1 w-full" required />
                        <x-input-error :messages="$errors->get('priority')" class="mt-1" />
                    </div>

                    <div class="flex gap-2 pt-2">
                        <x-primary-button>Сохранить</x-primary-button>
                        <x-secondary-button type="button" onclick="window.location='{{ route('cases.index') }}'">
                            Отмена
                        </x-secondary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>