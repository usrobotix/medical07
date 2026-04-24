<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Новый кейс</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-4">
                <form method="POST" action="{{ route('cases.store') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium">Пациент *</label>
                        <select name="patient_id" class="mt-1 w-full border rounded px-3 py-2" required>
                            <option value="">— выберите —</option>
                            @foreach($patients as $p)
                                <option value="{{ $p->id }}" @selected(old('patient_id') == $p->id)>
                                    #{{ $p->id }} — {{ $p->full_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('patient_id')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Статус *</label>
                        <select name="case_status_id" class="mt-1 w-full border rounded px-3 py-2" required>
                            @foreach($statuses as $s)
                                <option value="{{ $s->id }}" @selected(old('case_status_id') == $s->id)>
                                    {{ $s->sort_order }} — {{ $s->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('case_status_id')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Заголовок</label>
                        <input name="title" value="{{ old('title') }}" class="mt-1 w-full border rounded px-3 py-2">
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Описание/запрос</label>
                        <textarea name="problem" class="mt-1 w-full border rounded px-3 py-2" rows="4">{{ old('problem') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Приоритет (1..5) *</label>
                        <input type="number" name="priority" value="{{ old('priority', 3) }}" min="1" max="5" class="mt-1 w-full border rounded px-3 py-2" required>
                        @error('priority')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
                    </div>

                    <div class="flex gap-2">
                        <button class="px-3 py-2 bg-blue-600 text-white rounded">Сохранить</button>
                        <a href="{{ route('cases.index') }}" class="px-3 py-2 border rounded">Отмена</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>