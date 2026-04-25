<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Главная
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <p class="mb-6 text-sm text-gray-500 dark:text-gray-400 sm:px-0 px-4">Добро пожаловать в систему управления кейсами!</p>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6">

                {{-- Пациенты --}}
                <a href="{{ route('patients.index') }}" class="group block bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 hover:shadow-md hover:border-indigo-200 dark:hover:border-indigo-700 transition">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 rounded-lg bg-blue-100 dark:bg-blue-900/40 flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-800 dark:text-gray-200 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition">Пациенты</h3>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Картотека пациентов и их контактные данные</p>
                </a>

                {{-- Кейсы --}}
                <a href="{{ route('cases.index') }}" class="group block bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 hover:shadow-md hover:border-indigo-200 dark:hover:border-indigo-700 transition">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 rounded-lg bg-purple-100 dark:bg-purple-900/40 flex items-center justify-center">
                            <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-800 dark:text-gray-200 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition">Кейсы</h3>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Медицинские кейсы и их статусы</p>
                </a>

                {{-- Канбан --}}
                <a href="{{ route('cases.board') }}" class="group block bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 hover:shadow-md hover:border-indigo-200 dark:hover:border-indigo-700 transition">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 rounded-lg bg-orange-100 dark:bg-orange-900/40 flex items-center justify-center">
                            <svg class="w-5 h-5 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-800 dark:text-gray-200 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition">Канбан</h3>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Доска задач с пайплайном кейсов</p>
                </a>

                {{-- Партнёры --}}
                <a href="{{ route('partners.index') }}" class="group block bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 hover:shadow-md hover:border-indigo-200 dark:hover:border-indigo-700 transition">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 rounded-lg bg-emerald-100 dark:bg-emerald-900/40 flex items-center justify-center">
                            <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-800 dark:text-gray-200 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition">Партнёры</h3>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Клиники, переводчики и кураторы</p>
                </a>

                {{-- Страны --}}
                <a href="{{ route('countries.index') }}" class="group block bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 hover:shadow-md hover:border-indigo-200 dark:hover:border-indigo-700 transition">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 rounded-lg bg-sky-100 dark:bg-sky-900/40 flex items-center justify-center">
                            <svg class="w-5 h-5 text-sky-600 dark:text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-800 dark:text-gray-200 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition">Страны</h3>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Справочник стран партнёрской сети</p>
                </a>

                {{-- Ниши --}}
                <a href="{{ route('niches.index') }}" class="group block bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 hover:shadow-md hover:border-indigo-200 dark:hover:border-indigo-700 transition">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 rounded-lg bg-rose-100 dark:bg-rose-900/40 flex items-center justify-center">
                            <svg class="w-5 h-5 text-rose-600 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-800 dark:text-gray-200 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition">Ниши</h3>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Медицинские направления: онко, кардио, ЭКО…</p>
                </a>

                {{-- Направления по странам --}}
                <a href="{{ route('country-directions.index') }}" class="group block bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 hover:shadow-md hover:border-indigo-200 dark:hover:border-indigo-700 transition">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 rounded-lg bg-teal-100 dark:bg-teal-900/40 flex items-center justify-center">
                            <svg class="w-5 h-5 text-teal-600 dark:text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-800 dark:text-gray-200 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition">Направления по странам</h3>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Что искать при работе с каждой страной</p>
                </a>

                {{-- Чек-листы верификации --}}
                <a href="{{ route('verification-checklists.index') }}" class="group block bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 hover:shadow-md hover:border-indigo-200 dark:hover:border-indigo-700 transition">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 rounded-lg bg-yellow-100 dark:bg-yellow-900/40 flex items-center justify-center">
                            <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-800 dark:text-gray-200 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition">Чек-листы верификации</h3>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Шаблоны проверки партнёров по типам</p>
                </a>

                {{-- Шаблоны сообщений --}}
                <a href="{{ route('message-templates.index') }}" class="group block bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 hover:shadow-md hover:border-indigo-200 dark:hover:border-indigo-700 transition">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 rounded-lg bg-indigo-100 dark:bg-indigo-900/40 flex items-center justify-center">
                            <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-800 dark:text-gray-200 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition">Шаблоны сообщений</h3>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Email, WhatsApp, Telegram — готовые тексты</p>
                </a>

                {{-- Проверки партнёров --}}
                <a href="{{ route('partner-verifications.index') }}" class="group block bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 hover:shadow-md hover:border-indigo-200 dark:hover:border-indigo-700 transition">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                            <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-800 dark:text-gray-200 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition">Проверки партнёров</h3>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Экземпляры чек-листов верификации</p>
                </a>

            </div>
        </div>
    </div>
</x-app-layout>

