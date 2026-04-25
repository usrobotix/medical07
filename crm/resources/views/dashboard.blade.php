<x-app-layout>
    <x-slot name="header">
        <h2 class="text-ys-l font-semibold text-dc leading-tight">Главная</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <p class="text-dc-secondary mb-6 text-ys-s">Добро пожаловать в систему управления кейсами! Выберите раздел для работы.</p>

            {{-- Main CRM sections --}}
            <h3 class="text-ys-xs font-semibold text-dc-secondary mb-3 uppercase tracking-wider">CRM</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 mb-8">

                <a href="{{ route('patients.index') }}" class="group dc-transition rounded-md p-5 flex flex-col gap-3 border hover:shadow-card-lg min-h-[150px] justify-between" style="background-color:var(--color-surface);border-color:var(--color-border)">
                    <div class="w-10 h-10 bg-dc-blue-20 rounded-sm flex items-center justify-center text-dc-blue-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <div>
                        <p class="font-semibold text-dc text-ys-m-s group-hover:text-dc-blue-100 dc-transition">Пациенты</p>
                        <p class="text-ys-xs text-dc-secondary mt-0.5">Управление карточками пациентов</p>
                    </div>
                    <span class="text-ys-xs text-dc-primary font-medium mt-auto">Перейти →</span>
                </a>

                <a href="{{ route('cases.index') }}" class="group dc-transition rounded-md p-5 flex flex-col gap-3 border hover:shadow-card-lg min-h-[150px] justify-between" style="background-color:var(--color-surface);border-color:var(--color-border)">
                    <div class="w-10 h-10 bg-dc-green-10 rounded-sm flex items-center justify-center text-dc-green-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    </div>
                    <div>
                        <p class="font-semibold text-dc text-ys-m-s group-hover:text-dc-green-100 dc-transition">Кейсы</p>
                        <p class="text-ys-xs text-dc-secondary mt-0.5">Список медицинских кейсов</p>
                    </div>
                    <span class="text-ys-xs text-dc-primary font-medium mt-auto">Перейти →</span>
                </a>

                <a href="{{ route('cases.board') }}" class="group dc-transition rounded-md p-5 flex flex-col gap-3 border hover:shadow-card-lg min-h-[150px] justify-between" style="background-color:var(--color-surface);border-color:var(--color-border)">
                    <div class="w-10 h-10 bg-dc-violet-10 rounded-sm flex items-center justify-center text-dc-violet-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/></svg>
                    </div>
                    <div>
                        <p class="font-semibold text-dc text-ys-m-s group-hover:text-dc-violet-100 dc-transition">Канбан</p>
                        <p class="text-ys-xs text-dc-secondary mt-0.5">Доска управления кейсами</p>
                    </div>
                    <span class="text-ys-xs text-dc-primary font-medium mt-auto">Перейти →</span>
                </a>

                <a href="{{ route('kb.partners.index') }}" class="group dc-transition rounded-md p-5 flex flex-col gap-3 border hover:shadow-card-lg min-h-[150px] justify-between" style="background-color:var(--color-surface);border-color:var(--color-border)">
                    <div class="w-10 h-10 bg-dc-orange-30 rounded-sm flex items-center justify-center text-dc-orange-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                    <div>
                        <p class="font-semibold text-dc text-ys-m-s group-hover:text-dc-orange-100 dc-transition">Партнёры</p>
                        <p class="text-ys-xs text-dc-secondary mt-0.5">Клиники, переводчики, кураторы</p>
                    </div>
                    <span class="text-ys-xs text-dc-primary font-medium mt-auto">Перейти →</span>
                </a>

            </div>

            {{-- Knowledge Base sections --}}
            <h3 class="text-ys-xs font-semibold text-dc-secondary mb-3 uppercase tracking-wider">Справочники</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">

                <a href="{{ route('kb.countries.index') }}" class="group dc-transition rounded-md p-5 flex flex-col gap-3 border hover:shadow-card-lg min-h-[120px] justify-between" style="background-color:var(--color-surface);border-color:var(--color-border)">
                    <div class="w-9 h-9 bg-dc-blue-10 rounded-sm flex items-center justify-center text-dc-blue-100">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <p class="font-semibold text-dc text-ys-s group-hover:text-dc-blue-100 dc-transition">Страны</p>
                        <p class="text-ys-xs text-dc-secondary mt-0.5">Справочник стран операций</p>
                    </div>
                </a>

                <a href="{{ route('kb.niches.index') }}" class="group dc-transition rounded-md p-5 flex flex-col gap-3 border hover:shadow-card-lg min-h-[120px] justify-between" style="background-color:var(--color-surface);border-color:var(--color-border)">
                    <div class="w-9 h-9 bg-dc-blue-10 rounded-sm flex items-center justify-center text-dc-blue-100">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                    </div>
                    <div>
                        <p class="font-semibold text-dc text-ys-s group-hover:text-dc-blue-100 dc-transition">Ниши</p>
                        <p class="text-ys-xs text-dc-secondary mt-0.5">Медицинские специализации</p>
                    </div>
                </a>

                <a href="{{ route('kb.country-directions.index') }}" class="group dc-transition rounded-md p-5 flex flex-col gap-3 border hover:shadow-card-lg min-h-[120px] justify-between" style="background-color:var(--color-surface);border-color:var(--color-border)">
                    <div class="w-9 h-9 bg-dc-blue-10 rounded-sm flex items-center justify-center text-dc-blue-100">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                    </div>
                    <div>
                        <p class="font-semibold text-dc text-ys-s group-hover:text-dc-blue-100 dc-transition">Направления по странам</p>
                        <p class="text-ys-xs text-dc-secondary mt-0.5">Что и где искать по стране</p>
                    </div>
                </a>

                <a href="{{ route('kb.verification-checklists.index') }}" class="group dc-transition rounded-md p-5 flex flex-col gap-3 border hover:shadow-card-lg min-h-[120px] justify-between" style="background-color:var(--color-surface);border-color:var(--color-border)">
                    <div class="w-9 h-9 bg-dc-blue-10 rounded-sm flex items-center justify-center text-dc-blue-100">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                    </div>
                    <div>
                        <p class="font-semibold text-dc text-ys-s group-hover:text-dc-blue-100 dc-transition">Чек-листы верификации</p>
                        <p class="text-ys-xs text-dc-secondary mt-0.5">Шаблоны проверок партнёров</p>
                    </div>
                </a>

                <a href="{{ route('kb.message-templates.index') }}" class="group dc-transition rounded-md p-5 flex flex-col gap-3 border hover:shadow-card-lg min-h-[120px] justify-between" style="background-color:var(--color-surface);border-color:var(--color-border)">
                    <div class="w-9 h-9 bg-dc-blue-10 rounded-sm flex items-center justify-center text-dc-blue-100">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                    </div>
                    <div>
                        <p class="font-semibold text-dc text-ys-s group-hover:text-dc-blue-100 dc-transition">Шаблоны сообщений</p>
                        <p class="text-ys-xs text-dc-secondary mt-0.5">Email, WhatsApp, Telegram</p>
                    </div>
                </a>

                <a href="{{ route('kb.partner-verifications.index') }}" class="group dc-transition rounded-md p-5 flex flex-col gap-3 border hover:shadow-card-lg min-h-[120px] justify-between" style="background-color:var(--color-surface);border-color:var(--color-border)">
                    <div class="w-9 h-9 bg-dc-blue-10 rounded-sm flex items-center justify-center text-dc-blue-100">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                    <div>
                        <p class="font-semibold text-dc text-ys-s group-hover:text-dc-blue-100 dc-transition">Проверки партнёров</p>
                        <p class="text-ys-xs text-dc-secondary mt-0.5">Журнал верификационных проверок</p>
                    </div>
                </a>

            </div>
        </div>
    </div>
</x-app-layout>
