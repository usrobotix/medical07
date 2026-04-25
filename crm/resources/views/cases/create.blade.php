<x-app-layout>
    <x-slot name="header">
        <h2 class="text-ys-l font-semibold text-dc leading-tight">Новый кейс</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <x-dc-card padding="lg" shadow="card">
                <form method="POST" action="{{ route('cases.store') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label for="patient_id" class="block text-ys-xs font-medium text-dc-secondary mb-1">Пациент *</label>
                        <select id="patient_id" name="patient_id" required
                            class="w-full text-ys-s h-9 px-3 rounded-2xs border dc-transition bg-surface focus:outline-none focus-visible:ring-2 focus-visible:ring-dc-yellow-100"
                            style="border-color:var(--color-border);color:var(--color-text)">
                            <option value="">— выберите —</option>
                            @foreach($patients as $p)
                                <option value="{{ $p->id }}" @selected(old('patient_id') == $p->id)>
                                    #{{ $p->id }} — {{ $p->full_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('patient_id')
                            <p class="mt-1 text-ys-xs text-dc-red-100">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="pipeline_status_id" class="block text-ys-xs font-medium text-dc-secondary mb-1">Статус (этап) *</label>
                        <select id="pipeline_status_id" name="pipeline_status_id" required
                            class="w-full text-ys-s h-9 px-3 rounded-2xs border dc-transition bg-surface focus:outline-none focus-visible:ring-2 focus-visible:ring-dc-yellow-100"
                            style="border-color:var(--color-border);color:var(--color-text)">
                            @foreach($statuses as $s)
                                <option value="{{ $s->id }}" @selected(old('pipeline_status_id') == $s->id)>
                                    {{ $s->sort_order }} — {{ $s->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('pipeline_status_id')
                            <p class="mt-1 text-ys-xs text-dc-red-100">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="title" class="block text-ys-xs font-medium text-dc-secondary mb-1">Заголовок</label>
                        <x-dc-input id="title" name="title" value="{{ old('title') }}" class="w-full" />
                    </div>

                    <div>
                        <label for="problem" class="block text-ys-xs font-medium text-dc-secondary mb-1">Описание / запрос</label>
                        <textarea id="problem" name="problem" rows="4"
                            class="w-full text-ys-s rounded-2xs border dc-transition bg-surface focus:outline-none focus-visible:ring-2 focus-visible:ring-dc-yellow-100 p-3"
                            style="border-color:var(--color-border);color:var(--color-text)">{{ old('problem') }}</textarea>
                    </div>

                    <div>
                        <label for="priority" class="block text-ys-xs font-medium text-dc-secondary mb-1">Приоритет (1..5) *</label>
                        <x-dc-input id="priority" type="number" name="priority" value="{{ old('priority', 3) }}" min="1" max="5" class="w-full" required :error="$errors->first('priority')" />
                    </div>

                    <div class="flex gap-2 pt-2">
                        <x-dc-button type="submit" variant="action" size="m">Сохранить</x-dc-button>
                        <x-dc-button variant="contour" size="m" href="{{ route('cases.index') }}">Отмена</x-dc-button>
                    </div>
                </form>
            </x-dc-card>
        </div>
    </div>
</x-app-layout>
