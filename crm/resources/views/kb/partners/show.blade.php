<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('partners.index') }}" class="text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300 text-sm">← Партнёры</a>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">{{ $partner->name }}</h2>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Main info --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <h3 class="font-semibold text-gray-700 dark:text-gray-300 mb-4">Основная информация</h3>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-3 text-sm">
                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">Тип</dt>
                        <dd class="text-gray-800 dark:text-gray-200 mt-0.5">{{ $partner->type }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">Статус</dt>
                        <dd class="mt-0.5">
                            <span class="inline-block px-2 py-0.5 rounded text-xs font-medium
                                {{ $partner->status === 'active' ? 'bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-300' : '' }}
                                {{ $partner->status === 'verified' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300' : '' }}
                                {{ $partner->status === 'new' ? 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/40 dark:text-yellow-300' : '' }}
                                {{ $partner->status === 'frozen' ? 'bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400' : '' }}
                            ">{{ $partner->status }}</span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">Слой</dt>
                        <dd class="text-gray-800 dark:text-gray-200 mt-0.5">{{ $partner->layer?->name ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">Основная страна</dt>
                        <dd class="text-gray-800 dark:text-gray-200 mt-0.5">{{ $partner->country?->name_ru ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">Город</dt>
                        <dd class="text-gray-800 dark:text-gray-200 mt-0.5">{{ $partner->city ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">Языки</dt>
                        <dd class="text-gray-800 dark:text-gray-200 mt-0.5">{{ $partner->languages ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">Рейтинг верификации</dt>
                        <dd class="text-gray-800 dark:text-gray-200 mt-0.5">{{ $partner->verification_score ?? '—' }}</dd>
                    </div>
                </dl>
                @if($partner->notes)
                    <div class="mt-4 border-t border-gray-100 dark:border-gray-700 pt-3">
                        <dt class="text-gray-500 dark:text-gray-400 text-sm">Заметки</dt>
                        <dd class="text-gray-800 dark:text-gray-200 text-sm mt-1">{{ $partner->notes }}</dd>
                    </div>
                @endif
            </div>

            {{-- Contacts --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <h3 class="font-semibold text-gray-700 dark:text-gray-300 mb-4">Контакты</h3>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-3 text-sm">
                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">Контактное лицо</dt>
                        <dd class="text-gray-800 dark:text-gray-200 mt-0.5">{{ $partner->contact_name ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">Email</dt>
                        <dd class="text-gray-800 dark:text-gray-200 mt-0.5">{{ $partner->contact_email ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">Телефон</dt>
                        <dd class="text-gray-800 dark:text-gray-200 mt-0.5">{{ $partner->contact_phone ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">WhatsApp</dt>
                        <dd class="text-gray-800 dark:text-gray-200 mt-0.5">{{ $partner->contact_whatsapp ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">Telegram</dt>
                        <dd class="text-gray-800 dark:text-gray-200 mt-0.5">{{ $partner->contact_telegram ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 dark:text-gray-400">Сайт</dt>
                        <dd class="text-gray-800 dark:text-gray-200 mt-0.5">
                            @if($partner->website_url)
                                <a href="{{ $partner->website_url }}" target="_blank" class="text-indigo-600 dark:text-indigo-400 hover:underline">{{ $partner->website_url }}</a>
                            @else
                                —
                            @endif
                        </dd>
                    </div>
                </dl>
            </div>

            {{-- Niches & Countries --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                    <h3 class="font-semibold text-gray-700 dark:text-gray-300 mb-3">Ниши</h3>
                    @if($partner->niches->count())
                        <ul class="space-y-1 text-sm text-gray-700 dark:text-gray-300">
                            @foreach($partner->niches as $niche)
                                <li>• <a href="{{ route('niches.show', $niche) }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">{{ $niche->name }}</a></li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-sm text-gray-400 dark:text-gray-500 italic">Ниши не указаны</p>
                    @endif
                </div>
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                    <h3 class="font-semibold text-gray-700 dark:text-gray-300 mb-3">Страны работы</h3>
                    @if($partner->countries->count())
                        <ul class="space-y-1 text-sm text-gray-700 dark:text-gray-300">
                            @foreach($partner->countries as $country)
                                <li>• <a href="{{ route('countries.show', $country) }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">{{ $country->name_ru }}</a></li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-sm text-gray-400 dark:text-gray-500 italic">Страны не указаны</p>
                    @endif
                </div>
            </div>

            {{-- Verifications --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <h3 class="font-semibold text-gray-700 dark:text-gray-300 mb-3">Проверки партнёра</h3>
                @if($partner->verifications->count())
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-left border-b border-gray-200 dark:border-gray-700">
                                <th class="py-2 pr-3 text-gray-500 dark:text-gray-400 font-semibold">Чек-лист</th>
                                <th class="py-2 pr-3 text-gray-500 dark:text-gray-400 font-semibold">Статус</th>
                                <th class="py-2 text-gray-500 dark:text-gray-400 font-semibold">Дата</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($partner->verifications as $v)
                                <tr class="border-b border-gray-100 dark:border-gray-700">
                                    <td class="py-2 pr-3">
                                        <a href="{{ route('partner-verifications.show', $v) }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">
                                            {{ $v->checklist?->name ?? '#'.$v->id }}
                                        </a>
                                    </td>
                                    <td class="py-2 pr-3 text-gray-600 dark:text-gray-400">{{ $v->status }}</td>
                                    <td class="py-2 text-gray-500 dark:text-gray-500">{{ $v->verified_at?->format('d.m.Y') ?? '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-sm text-gray-400 dark:text-gray-500 italic">Проверок ещё нет</p>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
