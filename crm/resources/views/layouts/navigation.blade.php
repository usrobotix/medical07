<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        Главная
                    </x-nav-link>

                    <x-nav-link :href="route('patients.index')" :active="request()->routeIs('patients.*')">
                        Пациенты
                    </x-nav-link>

                    <x-nav-link :href="route('cases.index')" :active="request()->routeIs('cases.*') && !request()->routeIs('cases.board')">
                        Кейсы
                    </x-nav-link>

                    <x-nav-link :href="route('cases.board')" :active="request()->routeIs('cases.board')">
                        Канбан
                    </x-nav-link>

                    <x-nav-link :href="route('partners.index')" :active="request()->routeIs('partners.*')">
                        Партнёры
                    </x-nav-link>

                    <!-- Knowledge Base Dropdown -->
                    <div class="relative flex items-center" x-data="{ kbOpen: false }" @click.outside="kbOpen = false">
                        <button @click="kbOpen = !kbOpen"
                            class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 transition duration-150 ease-in-out focus:outline-none
                                {{ request()->routeIs('countries.*') || request()->routeIs('niches.*') || request()->routeIs('country-directions.*') || request()->routeIs('verification-checklists.*') || request()->routeIs('message-templates.*') || request()->routeIs('partner-verifications.*')
                                    ? 'border-indigo-400 text-gray-900 dark:text-gray-100'
                                    : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-700' }}">
                            Справочники
                            <svg class="ms-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <div x-show="kbOpen" x-cloak
                            class="absolute left-0 top-full mt-1 w-52 rounded-md shadow-lg bg-white dark:bg-gray-700 ring-1 ring-black ring-opacity-5 z-50">
                            <div class="py-1">
                                <a href="{{ route('countries.index') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600 {{ request()->routeIs('countries.*') ? 'font-semibold' : '' }}">
                                    Страны
                                </a>
                                <a href="{{ route('niches.index') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600 {{ request()->routeIs('niches.*') ? 'font-semibold' : '' }}">
                                    Ниши
                                </a>
                                <a href="{{ route('country-directions.index') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600 {{ request()->routeIs('country-directions.*') ? 'font-semibold' : '' }}">
                                    Направления
                                </a>
                                <a href="{{ route('verification-checklists.index') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600 {{ request()->routeIs('verification-checklists.*') ? 'font-semibold' : '' }}">
                                    Чек-листы
                                </a>
                                <a href="{{ route('message-templates.index') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600 {{ request()->routeIs('message-templates.*') ? 'font-semibold' : '' }}">
                                    Шаблоны сообщений
                                </a>
                                <a href="{{ route('partner-verifications.index') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600 {{ request()->routeIs('partner-verifications.*') ? 'font-semibold' : '' }}">
                                    Проверки партнёров
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Settings Dropdown + Theme Toggle -->
            <div class="hidden sm:flex sm:items-center sm:ms-6 gap-2">

                <!-- Theme Toggle -->
                <button
                    onclick="toggleTheme()"
                    class="p-2 rounded-md text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none transition"
                    title="Сменить тему"
                    aria-label="Сменить тему"
                >
                    <svg id="theme-icon-sun" class="h-5 w-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M17.657 17.657l-.707-.707M6.343 6.343l-.707-.707M12 5a7 7 0 100 14 7 7 0 000-14z" />
                    </svg>
                    <svg id="theme-icon-moon" class="h-5 w-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 12.79A9 9 0 1111.21 3a7 7 0 109.79 9.79z" />
                    </svg>
                </button>

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-200 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            Профиль
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                Выйти
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden gap-1">
                <!-- Mobile Theme Toggle -->
                <button
                    onclick="toggleTheme()"
                    class="p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none transition"
                    aria-label="Сменить тему"
                >
                    <svg id="theme-icon-sun-mobile" class="h-5 w-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M17.657 17.657l-.707-.707M6.343 6.343l-.707-.707M12 5a7 7 0 100 14 7 7 0 000-14z" />
                    </svg>
                    <svg id="theme-icon-moon-mobile" class="h-5 w-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 12.79A9 9 0 1111.21 3a7 7 0 109.79 9.79z" />
                    </svg>
                </button>

                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-700 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                Главная
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('patients.index')" :active="request()->routeIs('patients.*')">
                Пациенты
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('cases.index')" :active="request()->routeIs('cases.*') && !request()->routeIs('cases.board')">
                Кейсы
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('cases.board')" :active="request()->routeIs('cases.board')">
                Канбан
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('partners.index')" :active="request()->routeIs('partners.*')">
                Партнёры
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('countries.index')" :active="request()->routeIs('countries.*')">
                Страны
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('niches.index')" :active="request()->routeIs('niches.*')">
                Ниши
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('country-directions.index')" :active="request()->routeIs('country-directions.*')">
                Направления
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('verification-checklists.index')" :active="request()->routeIs('verification-checklists.*')">
                Чек-листы
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('message-templates.index')" :active="request()->routeIs('message-templates.*')">
                Шаблоны сообщений
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('partner-verifications.index')" :active="request()->routeIs('partner-verifications.*')">
                Проверки партнёров
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500 dark:text-gray-400">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    Профиль
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        Выйти
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>

<script>
function toggleTheme() {
    const html = document.documentElement;
    const isDark = html.classList.toggle('dark');
    localStorage.setItem('theme', isDark ? 'dark' : 'light');
    updateThemeIcons(isDark);
}

function updateThemeIcons(isDark) {
    const sunIcons = ['theme-icon-sun', 'theme-icon-sun-mobile'];
    const moonIcons = ['theme-icon-moon', 'theme-icon-moon-mobile'];
    sunIcons.forEach(id => {
        const el = document.getElementById(id);
        if (el) el.classList.toggle('hidden', isDark);
    });
    moonIcons.forEach(id => {
        const el = document.getElementById(id);
        if (el) el.classList.toggle('hidden', !isDark);
    });
}

// Initialize icons on load
(function () {
    updateThemeIcons(document.documentElement.classList.contains('dark'));
})();
</script>
