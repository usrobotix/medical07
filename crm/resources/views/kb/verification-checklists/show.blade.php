<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <a href="{{ route('kb.verification-checklists.index') }}" class="text-dc-secondary hover:text-dc text-ys-s dc-transition">← Чек-листы</a>
                <h2 class="text-ys-l font-semibold text-dc leading-tight">{{ $verificationChecklist->name }}</h2>
            </div>
            @auth
                @if(auth()->user()->hasAnyRole(['admin', 'manager']))
                    <x-dc-button variant="contour" size="s" href="{{ route('kb.verification-checklists.edit', $verificationChecklist) }}">Редактировать</x-dc-button>
                @endif
            @endauth
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <x-dc-card padding="lg" shadow="card">
                <h3 class="text-ys-m-s font-semibold text-dc mb-4">Информация о чек-листе</h3>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-3">
                    <div>
                        <dt class="text-ys-xs text-dc-secondary">Тип партнёра</dt>
                        <dd class="text-ys-s text-dc mt-0.5">
                            @php $typeLabels = ['clinic' => 'Клиника', 'translator' => 'Переводчик', 'curator' => 'Куратор']; @endphp
                            {{ $typeLabels[$verificationChecklist->partner_type] ?? ($verificationChecklist->partner_type ?? '—') }}
                        </dd>
                    </div>
                    @if($verificationChecklist->description)
                        <div class="sm:col-span-2">
                            <dt class="text-ys-xs text-dc-secondary">Описание</dt>
                            <dd class="text-ys-s text-dc mt-0.5">{{ $verificationChecklist->description }}</dd>
                        </div>
                    @endif
                </dl>
            </x-dc-card>

            <x-dc-card padding="lg" shadow="card">
                <h3 class="text-ys-s font-semibold text-dc mb-4">Пункты чек-листа ({{ $verificationChecklist->items->count() }})</h3>
                @if($verificationChecklist->items->isEmpty())
                    <p class="text-ys-s text-dc-secondary">Пункты не добавлены.</p>
                @else
                    <ol class="space-y-3">
                        @foreach($verificationChecklist->items as $item)
                            <li class="flex items-start gap-3">
                                <span class="flex-shrink-0 w-6 h-6 bg-dc-blue-20 text-dc-primary rounded-full flex items-center justify-center text-ys-xs font-medium">
                                    {{ $loop->iteration }}
                                </span>
                                <p class="text-ys-s text-dc flex-1">{{ $item->text }}</p>
                            </li>
                        @endforeach
                    </ol>
                @endif
            </x-dc-card>

        </div>
    </div>
</x-app-layout>
