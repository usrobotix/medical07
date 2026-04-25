<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Главная
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <p class="px-4 sm:px-0 mb-6 text-gray-600 dark:text-gray-400 text-sm">
                Добро пожаловать в систему управления кейсами! Выберите раздел:
            </p>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 px-4 sm:px-0">

                {{-- Patients --}}
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-5 flex flex-col gap-3 border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center gap-3">
                        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <h3 class="text-base font-semibold text-gray-800 dark:text-gray-100">Пациенты</h3>
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Реестр пациентов: просмотр и добавление.</p>
                    <a href="{{ route('patients.index') }}"
                        class="mt-auto inline-flex items-center text-sm font-medium text-blue-600 dark:text-blue-400 hover:underline">
                        Открыть →
                    </a>
                </div>

                {{-- Cases --}}
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-5 flex flex-col gap-3 border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center gap-3">
                        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-purple-100 dark:bg-purple-900 flex items-center justify-center">
                            <svg class="w-5 h-5 text-purple-600 dark:text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <h3 class="text-base font-semibold text-gray-800 dark:text-gray-100">Кейсы (список)</h3>
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Все медицинские кейсы в табличном виде.</p>
                    <a href="{{ route('cases.index') }}"
                        class="mt-auto inline-flex items-center text-sm font-medium text-purple-600 dark:text-purple-400 hover:underline">
                        Открыть →
                    </a>
                </div>

                {{-- Kanban --}}
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-5 flex flex-col gap-3 border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center gap-3">
                        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-green-100 dark:bg-green-900 flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600 dark:text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/>
                            </svg>
                        </div>
                        <h3 class="text-base font-semibold text-gray-800 dark:text-gray-100">Канбан</h3>
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Доска кейсов по статусам пайплайна.</p>
                    <a href="{{ route('cases.board') }}"
                        class="mt-auto inline-flex items-center text-sm font-medium text-green-600 dark:text-green-400 hover:underline">
                        Открыть →
                    </a>
                </div>

                {{-- Partners --}}
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-5 flex flex-col gap-3 border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center gap-3">
                        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-yellow-100 dark:bg-yellow-900 flex items-center justify-center">
                            <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-base font-semibold text-gray-800 dark:text-gray-100">Партнёры</h3>
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">База партнёров: клиники, врачи, переводчики.</p>
                    <a href="{{ route('partners.index') }}"
                        class="mt-auto inline-flex items-center text-sm font-medium text-yellow-600 dark:text-yellow-400 hover:underline">
                        Открыть →
                    </a>
                </div>

                {{-- Countries --}}
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-5 flex flex-col gap-3 border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center gap-3">
                        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-sky-100 dark:bg-sky-900 flex items-center justify-center">
                            <svg class="w-5 h-5 text-sky-600 dark:text-sky-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-base font-semibold text-gray-800 dark:text-gray-100">Страны</h3>
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Справочник стран для партнёров и направлений.</p>
                    <a href="{{ route('countries.index') }}"
                        class="mt-auto inline-flex items-center text-sm font-medium text-sky-600 dark:text-sky-400 hover:underline">
                        Открыть →
                    </a>
                </div>

                {{-- Niches --}}
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-5 flex flex-col gap-3 border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center gap-3">
                        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-rose-100 dark:bg-rose-900 flex items-center justify-center">
                            <svg class="w-5 h-5 text-rose-600 dark:text-rose-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                            </svg>
                        </div>
                        <h3 class="text-base font-semibold text-gray-800 dark:text-gray-100">Ниши</h3>
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Медицинские ниши: онкология, кардиология и др.</p>
                    <a href="{{ route('niches.index') }}"
                        class="mt-auto inline-flex items-center text-sm font-medium text-rose-600 dark:text-rose-400 hover:underline">
                        Открыть →
                    </a>
                </div>

                {{-- Country Directions --}}
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-5 flex flex-col gap-3 border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center gap-3">
                        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center">
                            <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-1.447-.894L15 9m0 8V9m0 0L9 7"/>
                            </svg>
                        </div>
                        <h3 class="text-base font-semibold text-gray-800 dark:text-gray-100">Направления по странам</h3>
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Что искать и у кого по каждой стране и нише.</p>
                    <a href="{{ route('country-directions.index') }}"
                        class="mt-auto inline-flex items-center text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:underline">
                        Открыть →
                    </a>
                </div>

                {{-- Verification Checklists --}}
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-5 flex flex-col gap-3 border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center gap-3">
                        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-teal-100 dark:bg-teal-900 flex items-center justify-center">
                            <svg class="w-5 h-5 text-teal-600 dark:text-teal-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                            </svg>
                        </div>
                        <h3 class="text-base font-semibold text-gray-800 dark:text-gray-100">Чек-листы верификации</h3>
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Шаблоны проверочных пунктов для партнёров.</p>
                    <a href="{{ route('verification-checklists.index') }}"
                        class="mt-auto inline-flex items-center text-sm font-medium text-teal-600 dark:text-teal-400 hover:underline">
                        Открыть →
                    </a>
                </div>

                {{-- Message Templates --}}
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-5 flex flex-col gap-3 border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center gap-3">
                        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-orange-100 dark:bg-orange-900 flex items-center justify-center">
                            <svg class="w-5 h-5 text-orange-600 dark:text-orange-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <h3 class="text-base font-semibold text-gray-800 dark:text-gray-100">Шаблоны сообщений</h3>
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Готовые шаблоны первого контакта с партнёрами.</p>
                    <a href="{{ route('message-templates.index') }}"
                        class="mt-auto inline-flex items-center text-sm font-medium text-orange-600 dark:text-orange-400 hover:underline">
                        Открыть →
                    </a>
                </div>

                {{-- Partner Verifications --}}
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-5 flex flex-col gap-3 border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center gap-3">
                        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                            <svg class="w-5 h-5 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <h3 class="text-base font-semibold text-gray-800 dark:text-gray-100">Проверки партнёров</h3>
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">История верификации партнёров по чек-листам.</p>
                    <a href="{{ route('partner-verifications.index') }}"
                        class="mt-auto inline-flex items-center text-sm font-medium text-gray-600 dark:text-gray-400 hover:underline">
                        Открыть →
                    </a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
