<x-app-layout>
    <x-slot name="header">
        <h2 class="text-ys-l font-semibold text-dc leading-tight">Техническая документация CRM</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Auto info panel --}}
            <x-dc.card padding="lg" shadow="card">
                <h3 class="text-ys-m-s font-semibold text-dc mb-4">📊 Информация о проекте</h3>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-3">
                    <div>
                        <dt class="text-ys-xs text-dc-secondary">Время сервера</dt>
                        <dd class="text-ys-s text-dc mt-0.5 font-mono">{{ $info['server_time'] }}</dd>
                    </div>
                    <div>
                        <dt class="text-ys-xs text-dc-secondary">Версия приложения</dt>
                        <dd class="text-ys-s text-dc mt-0.5 font-mono">{{ $info['app_version'] }}</dd>
                    </div>
                    <div>
                        <dt class="text-ys-xs text-dc-secondary">Git-коммит</dt>
                        <dd class="text-ys-s text-dc mt-0.5 font-mono">{{ $info['git_commit'] }}</dd>
                    </div>
                    <div>
                        <dt class="text-ys-xs text-dc-secondary">Дата коммита</dt>
                        <dd class="text-ys-s text-dc mt-0.5 font-mono">{{ $info['git_date'] }}</dd>
                    </div>
                    <div>
                        <dt class="text-ys-xs text-dc-secondary">PHP</dt>
                        <dd class="text-ys-s text-dc mt-0.5 font-mono">{{ $info['php_version'] }}</dd>
                    </div>
                    <div>
                        <dt class="text-ys-xs text-dc-secondary">Laravel</dt>
                        <dd class="text-ys-s text-dc mt-0.5 font-mono">{{ $info['laravel_version'] }}</dd>
                    </div>
                    <div>
                        <dt class="text-ys-xs text-dc-secondary">spatie/laravel-permission</dt>
                        <dd class="text-ys-s text-dc mt-0.5 font-mono">{{ $info['spatie_version'] }}</dd>
                    </div>
                    <div>
                        <dt class="text-ys-xs text-dc-secondary">Окружение</dt>
                        <dd class="text-ys-s text-dc mt-0.5 font-mono">{{ $info['app_env'] }}</dd>
                    </div>
                </dl>
            </x-dc.card>

            {{-- Styles --}}
            <x-dc.card padding="lg" shadow="card">
                <h3 class="text-ys-m-s font-semibold text-dc mb-3">🎨 Стили и дизайн-токены</h3>
                <ul class="space-y-2 text-ys-s text-dc">
                    <li>
                        <span class="font-medium">CSS-переменные и базовые стили:</span>
                        <code class="ml-1 font-mono text-dc-secondary">resources/css/app.css</code>
                    </li>
                    <li>
                        <span class="font-medium">Конфигурация Tailwind:</span>
                        <code class="ml-1 font-mono text-dc-secondary">tailwind.config.js</code>
                    </li>
                    <li>
                        <span class="font-medium">Vite-бандл:</span>
                        <code class="ml-1 font-mono text-dc-secondary">vite.config.js</code>
                        — пересборка через <code class="font-mono">npm run build</code>
                    </li>
                </ul>
            </x-dc.card>

            {{-- Layouts & Components --}}
            <x-dc.card padding="lg" shadow="card">
                <h3 class="text-ys-m-s font-semibold text-dc mb-3">🧩 Лэйауты и Blade-компоненты</h3>
                <ul class="space-y-2 text-ys-s text-dc">
                    <li>
                        <span class="font-medium">Основной лэйаут:</span>
                        <code class="ml-1 font-mono text-dc-secondary">resources/views/layouts/app.blade.php</code>
                        — используется через тег <code class="font-mono">&lt;x-app-layout&gt;</code>
                    </li>
                    <li>
                        <span class="font-medium">Компоненты DC (дизайн-система):</span>
                        <code class="ml-1 font-mono text-dc-secondary">resources/views/components/dc/</code>
                        — карточки (<code class="font-mono">x-dc.card</code>), кнопки (<code class="font-mono">x-dc.button</code>), таблицы (<code class="font-mono">x-dc.table</code>) и др.
                    </li>
                    <li>
                        <span class="font-medium">Прочие Blade-компоненты:</span>
                        <code class="ml-1 font-mono text-dc-secondary">resources/views/components/</code>
                        — навигация, дропдауны, модалки.
                    </li>
                </ul>
            </x-dc.card>

            {{-- KB Pages --}}
            <x-dc.card padding="lg" shadow="card">
                <h3 class="text-ys-m-s font-semibold text-dc mb-3">📚 Страницы KB (Knowledge Base)</h3>
                <ul class="space-y-2 text-ys-s text-dc">
                    <li>
                        <span class="font-medium">Все шаблоны KB:</span>
                        <code class="ml-1 font-mono text-dc-secondary">resources/views/kb/</code>
                    </li>
                    <li>Каждый раздел — отдельная папка: <code class="font-mono">partners/</code>, <code class="font-mono">countries/</code>, <code class="font-mono">niches/</code>, <code class="font-mono">partner-verifications/</code> и т.д.</li>
                    <li>
                        <span class="font-medium">Контроллеры KB:</span>
                        <code class="ml-1 font-mono text-dc-secondary">app/Http/Controllers/Kb/</code>
                    </li>
                </ul>
            </x-dc.card>

            {{-- Role middleware --}}
            <x-dc.card padding="lg" shadow="card">
                <h3 class="text-ys-m-s font-semibold text-dc mb-3">🔐 Middleware ролей (Spatie)</h3>
                <ul class="space-y-3 text-ys-s text-dc">
                    <li>
                        <span class="font-medium text-red-500">❌ Неверно:</span>
                        <code class="ml-1 font-mono bg-red-50 dark:bg-red-900/20 px-1 rounded">role:admin,manager</code>
                        — запятая воспринимается как разделитель «guard», что вызывает ошибку
                        <em>Auth guard [manager] is not defined</em>.
                    </li>
                    <li>
                        <span class="font-medium text-green-600">✅ Верно:</span>
                        <code class="ml-1 font-mono bg-green-50 dark:bg-green-900/20 px-1 rounded">role:admin|manager</code>
                        — труба <code class="font-mono">|</code> задаёт несколько допустимых ролей.
                    </li>
                    <li>
                        KB write-роуты защищены через
                        <code class="font-mono">middleware('role:admin|manager')</code>
                        в <code class="font-mono">routes/web.php</code>.
                    </li>
                </ul>
            </x-dc.card>

            {{-- Cache commands --}}
            <x-dc.card padding="lg" shadow="card">
                <h3 class="text-ys-m-s font-semibold text-dc mb-3">⚡ Команды очистки кэша</h3>
                <ul class="space-y-2 text-ys-s text-dc">
                    <li><code class="font-mono text-dc-secondary">php artisan optimize:clear</code> — очистить все кэши (config, route, view, cache)</li>
                    <li><code class="font-mono text-dc-secondary">php artisan config:clear</code> — только кэш конфигурации</li>
                    <li><code class="font-mono text-dc-secondary">php artisan route:clear</code> — только кэш роутов</li>
                    <li><code class="font-mono text-dc-secondary">php artisan view:clear</code> — только кэш шаблонов</li>
                    <li><code class="font-mono text-dc-secondary">php artisan cache:clear</code> — только кэш приложения</li>
                    <li><code class="font-mono text-dc-secondary">npm run build</code> — пересборка фронтенд-ассетов</li>
                </ul>
            </x-dc.card>

            {{-- ===== SECTIONS OF THE SITE ===== --}}
            <x-dc.card padding="lg" shadow="card">
                <h3 class="text-ys-m-s font-semibold text-dc mb-4">🗂 Разделы CRM — где что лежит</h3>

                {{-- Dashboard --}}
                <div class="mb-5">
                    <h4 class="font-semibold text-dc mb-1">📊 Dashboard (Главная)</h4>
                    <ul class="space-y-1 text-ys-s text-dc ml-4">
                        <li><span class="font-medium">Назначение:</span> стартовая страница после входа; сводная информация / виджеты.</li>
                        <li><span class="font-medium">Роут:</span> <code class="font-mono text-dc-secondary">GET /dashboard</code> → замыкание в <code class="font-mono">routes/web.php</code>, возвращает view <code class="font-mono">dashboard</code>.</li>
                        <li><span class="font-medium">View:</span> <code class="font-mono text-dc-secondary">resources/views/dashboard.blade.php</code></li>
                        <li><span class="font-medium">Middleware:</span> <code class="font-mono">auth</code>, <code class="font-mono">verified</code> — только для аутентифицированных с подтверждённым e-mail.</li>
                        <li><span class="font-medium">UI/стили:</span> правьте <code class="font-mono text-dc-secondary">dashboard.blade.php</code>; используйте компоненты <code class="font-mono">x-dc.*</code>.</li>
                    </ul>
                </div>

                {{-- Profile --}}
                <div class="mb-5">
                    <h4 class="font-semibold text-dc mb-1">👤 Profile (Профиль)</h4>
                    <ul class="space-y-1 text-ys-s text-dc ml-4">
                        <li><span class="font-medium">Назначение:</span> редактирование имени, e-mail, пароля; удаление аккаунта.</li>
                        <li><span class="font-medium">Роуты:</span> <code class="font-mono text-dc-secondary">GET/PATCH/DELETE /profile</code> → <code class="font-mono">ProfileController</code>.</li>
                        <li><span class="font-medium">Контроллер:</span> <code class="font-mono text-dc-secondary">app/Http/Controllers/ProfileController.php</code></li>
                        <li><span class="font-medium">Views:</span> <code class="font-mono text-dc-secondary">resources/views/profile/</code></li>
                        <li><span class="font-medium">Middleware:</span> <code class="font-mono">auth</code>.</li>
                        <li><span class="font-medium">UI/стили:</span> правьте файлы в <code class="font-mono text-dc-secondary">resources/views/profile/</code>.</li>
                    </ul>
                </div>

                {{-- Cases --}}
                <div class="mb-5">
                    <h4 class="font-semibold text-dc mb-1">📋 Cases (Кейсы)</h4>
                    <ul class="space-y-1 text-ys-s text-dc ml-4">
                        <li><span class="font-medium">Назначение:</span> медицинские кейсы пациентов — список, создание; канбан-доска; обновление статусов pipeline/service.</li>
                        <li><span class="font-medium">Роуты:</span>
                            <ul class="ml-4 space-y-0.5">
                                <li><code class="font-mono text-dc-secondary">GET /cases</code>, <code class="font-mono">GET /cases/create</code>, <code class="font-mono">POST /cases</code> → <code class="font-mono">MedicalCaseController</code></li>
                                <li><code class="font-mono text-dc-secondary">GET /cases/board</code> → <code class="font-mono">CaseBoardController</code></li>
                                <li><code class="font-mono text-dc-secondary">PATCH /cases/{case}/pipeline-status</code> → <code class="font-mono">CaseStatusController@updatePipeline</code></li>
                                <li><code class="font-mono text-dc-secondary">PATCH /cases/{case}/service-status</code> → <code class="font-mono">CaseStatusController@updateService</code></li>
                            </ul>
                        </li>
                        <li><span class="font-medium">Контроллеры:</span> <code class="font-mono text-dc-secondary">app/Http/Controllers/MedicalCaseController.php</code>, <code class="font-mono">CaseBoardController.php</code>, <code class="font-mono">CaseStatusController.php</code></li>
                        <li><span class="font-medium">Views:</span> <code class="font-mono text-dc-secondary">resources/views/cases/</code></li>
                        <li><span class="font-medium">Middleware:</span> <code class="font-mono">auth</code>.</li>
                        <li><span class="font-medium">Модели:</span> <code class="font-mono text-dc-secondary">app/Models/MedicalCase.php</code>, <code class="font-mono">CaseStatus.php</code>, <code class="font-mono">CaseStatusHistory.php</code></li>
                        <li><span class="font-medium">UI/стили:</span> правьте <code class="font-mono text-dc-secondary">resources/views/cases/</code>; канбан-логика — JS-код в шаблоне доски или <code class="font-mono">resources/js/</code>.</li>
                    </ul>
                </div>

                {{-- Patients --}}
                <div class="mb-5">
                    <h4 class="font-semibold text-dc mb-1">🏥 Patients (Пациенты)</h4>
                    <ul class="space-y-1 text-ys-s text-dc ml-4">
                        <li><span class="font-medium">Назначение:</span> список и добавление пациентов.</li>
                        <li><span class="font-medium">Роуты:</span> <code class="font-mono text-dc-secondary">GET /patients</code>, <code class="font-mono">GET /patients/create</code>, <code class="font-mono">POST /patients</code> → <code class="font-mono">PatientController</code></li>
                        <li><span class="font-medium">Контроллер:</span> <code class="font-mono text-dc-secondary">app/Http/Controllers/PatientController.php</code></li>
                        <li><span class="font-medium">Views:</span> <code class="font-mono text-dc-secondary">resources/views/patients/</code></li>
                        <li><span class="font-medium">Модель:</span> <code class="font-mono text-dc-secondary">app/Models/Patient.php</code></li>
                        <li><span class="font-medium">Middleware:</span> <code class="font-mono">auth</code>.</li>
                        <li><span class="font-medium">UI/стили:</span> правьте файлы в <code class="font-mono text-dc-secondary">resources/views/patients/</code>.</li>
                    </ul>
                </div>

                {{-- KB Read --}}
                <div class="mb-5">
                    <h4 class="font-semibold text-dc mb-1">📖 KB Read (Справочники — просмотр)</h4>
                    <ul class="space-y-1 text-ys-s text-dc ml-4">
                        <li><span class="font-medium">Назначение:</span> чтение базы знаний — партнёры, страны, ниши, направления по странам, чек-листы верификации, шаблоны сообщений, проверки партнёров.</li>
                        <li><span class="font-medium">Доступно:</span> всем аутентифицированным пользователям (<code class="font-mono">auth</code>).</li>
                        <li><span class="font-medium">Роуты (только index/show):</span> <code class="font-mono text-dc-secondary">GET /kb/partners</code>, <code class="font-mono">/kb/countries</code>, <code class="font-mono">/kb/niches</code>, <code class="font-mono">/kb/country-directions</code>, <code class="font-mono">/kb/verification-checklists</code>, <code class="font-mono">/kb/message-templates</code>, <code class="font-mono">/kb/partner-verifications</code></li>
                        <li><span class="font-medium">Контроллеры:</span> <code class="font-mono text-dc-secondary">app/Http/Controllers/Kb/</code> (PartnerController, CountryController, NicheController, CountryDirectionController, VerificationChecklistController, MessageTemplateController, PartnerVerificationController)</li>
                        <li><span class="font-medium">Views:</span> <code class="font-mono text-dc-secondary">resources/views/kb/{раздел}/index.blade.php</code> и <code class="font-mono">show.blade.php</code></li>
                        <li><span class="font-medium">UI/стили:</span> правьте соответствующий <code class="font-mono text-dc-secondary">resources/views/kb/{раздел}/</code>.</li>
                    </ul>
                </div>

                {{-- KB Write --}}
                <div class="mb-5">
                    <h4 class="font-semibold text-dc mb-1">✏️ KB Admin/Write (Справочники — редактирование)</h4>
                    <ul class="space-y-1 text-ys-s text-dc ml-4">
                        <li><span class="font-medium">Назначение:</span> создание/редактирование/удаление записей в KB; запуск верификации партнёров; управление чек-листами и их пунктами; шаблоны сообщений.</li>
                        <li><span class="font-medium">Middleware:</span> <code class="font-mono bg-green-50 dark:bg-green-900/20 px-1 rounded">role:admin|manager</code> — только для ролей <code class="font-mono">admin</code> или <code class="font-mono">manager</code>.</li>
                        <li><span class="font-medium">Роуты (create/edit/store/update/destroy + специфичные):</span>
                            <ul class="ml-4 space-y-0.5">
                                <li><code class="font-mono text-dc-secondary">POST /kb/partners/{partner}/start-verification</code></li>
                                <li><code class="font-mono text-dc-secondary">POST|PATCH|DELETE /kb/verification-checklists/{id}/items</code></li>
                                <li><code class="font-mono text-dc-secondary">POST /kb/partner-verifications/{id}/items/update-bulk</code></li>
                            </ul>
                        </li>
                        <li><span class="font-medium">Views (create/edit):</span> <code class="font-mono text-dc-secondary">resources/views/kb/{раздел}/create.blade.php</code>, <code class="font-mono">edit.blade.php</code></li>
                        <li><span class="font-medium">UI/стили:</span> правьте формы в <code class="font-mono text-dc-secondary">resources/views/kb/{раздел}/</code>; используйте <code class="font-mono">x-dc.input</code>, <code class="font-mono">x-dc.select</code>, <code class="font-mono">x-dc.button</code>.</li>
                    </ul>
                </div>

                {{-- Auth --}}
                <div>
                    <h4 class="font-semibold text-dc mb-1">🔑 Auth (Авторизация и регистрация)</h4>
                    <ul class="space-y-1 text-ys-s text-dc ml-4">
                        <li><span class="font-medium">Назначение:</span> вход, регистрация, сброс пароля, подтверждение e-mail, подтверждение пароля.</li>
                        <li><span class="font-medium">Роуты:</span> определены в <code class="font-mono text-dc-secondary">routes/auth.php</code> (подключается в конце <code class="font-mono">web.php</code>).</li>
                        <li><span class="font-medium">Контроллеры:</span> <code class="font-mono text-dc-secondary">app/Http/Controllers/Auth/</code> — AuthenticatedSessionController, RegisteredUserController, PasswordResetLinkController, NewPasswordController, EmailVerificationController, ConfirmablePasswordController.</li>
                        <li><span class="font-medium">Views:</span> <code class="font-mono text-dc-secondary">resources/views/auth/</code> — login, register, forgot-password, reset-password, verify-email, confirm-password.</li>
                        <li><span class="font-medium">Лэйаут:</span> использует <code class="font-mono">resources/views/layouts/guest.blade.php</code>.</li>
                        <li><span class="font-medium">Middleware:</span> гостевые страницы защищены <code class="font-mono">guest</code>; верификация e-mail — <code class="font-mono">verified</code>.</li>
                        <li><span class="font-medium">UI/стили:</span> правьте <code class="font-mono text-dc-secondary">resources/views/auth/</code> и <code class="font-mono">resources/views/layouts/guest.blade.php</code>.</li>
                    </ul>
                </div>
            </x-dc.card>

            {{-- ===== PROJECT STRUCTURE ===== --}}
            <x-dc.card padding="lg" shadow="card">
                <h3 class="text-ys-m-s font-semibold text-dc mb-4">🗃 Структура проекта</h3>
                <p class="text-ys-s text-dc-secondary mb-3">Всё приложение Laravel находится в папке <code class="font-mono">crm/</code> репозитория.</p>

                <div class="overflow-x-auto">
                    <table class="w-full text-ys-s text-dc">
                        <thead>
                            <tr class="border-b border-dc">
                                <th class="text-left py-2 pr-4 font-semibold w-1/3">Путь</th>
                                <th class="text-left py-2 font-semibold">Назначение</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[color:var(--color-border)]">
                            <tr><td class="py-2 pr-4 font-mono text-dc-secondary">crm/app/Http/Controllers/</td><td class="py-2">Контроллеры (основные + подпапка <code class="font-mono">Kb/</code>)</td></tr>
                            <tr><td class="py-2 pr-4 font-mono text-dc-secondary">crm/app/Http/Controllers/Auth/</td><td class="py-2">Breeze-контроллеры аутентификации</td></tr>
                            <tr><td class="py-2 pr-4 font-mono text-dc-secondary">crm/app/Models/</td><td class="py-2">Eloquent-модели (MedicalCase, Patient, Partner, Country, Niche…)</td></tr>
                            <tr><td class="py-2 pr-4 font-mono text-dc-secondary">crm/app/Policies/</td><td class="py-2">Политики авторизации (если есть)</td></tr>
                            <tr><td class="py-2 pr-4 font-mono text-dc-secondary">crm/app/Providers/</td><td class="py-2">Сервис-провайдеры (AppServiceProvider и др.)</td></tr>
                            <tr><td class="py-2 pr-4 font-mono text-dc-secondary">crm/config/</td><td class="py-2">Конфигурация Laravel — <code class="font-mono">app.php</code>, <code class="font-mono">auth.php</code>, <code class="font-mono">database.php</code> и т.д.</td></tr>
                            <tr><td class="py-2 pr-4 font-mono text-dc-secondary">crm/database/migrations/</td><td class="py-2">Миграции БД</td></tr>
                            <tr><td class="py-2 pr-4 font-mono text-dc-secondary">crm/database/factories/</td><td class="py-2">Фабрики моделей для тестов/seed</td></tr>
                            <tr><td class="py-2 pr-4 font-mono text-dc-secondary">crm/database/seeders/</td><td class="py-2">Seeders (первоначальные данные: PartnerLayerSeeder и др.)</td></tr>
                            <tr><td class="py-2 pr-4 font-mono text-dc-secondary">crm/resources/views/</td><td class="py-2">Blade-шаблоны (layouts/, auth/, kb/, cases/, patients/, profile…)</td></tr>
                            <tr><td class="py-2 pr-4 font-mono text-dc-secondary">crm/resources/views/components/dc/</td><td class="py-2">DC дизайн-система: card, button, input, select, table, tabs, badge, modal</td></tr>
                            <tr><td class="py-2 pr-4 font-mono text-dc-secondary">crm/resources/views/layouts/</td><td class="py-2"><code class="font-mono">app.blade.php</code> (главный лэйаут), <code class="font-mono">guest.blade.php</code>, <code class="font-mono">navigation.blade.php</code></td></tr>
                            <tr><td class="py-2 pr-4 font-mono text-dc-secondary">crm/resources/css/app.css</td><td class="py-2">CSS-переменные, базовые стили, Tailwind-директивы</td></tr>
                            <tr><td class="py-2 pr-4 font-mono text-dc-secondary">crm/resources/js/app.js</td><td class="py-2">Точка входа JS; подключение Alpine.js и других библиотек</td></tr>
                            <tr><td class="py-2 pr-4 font-mono text-dc-secondary">crm/routes/web.php</td><td class="py-2">Web-роуты приложения</td></tr>
                            <tr><td class="py-2 pr-4 font-mono text-dc-secondary">crm/routes/auth.php</td><td class="py-2">Breeze-роуты авторизации</td></tr>
                            <tr><td class="py-2 pr-4 font-mono text-dc-secondary">crm/tailwind.config.js</td><td class="py-2">Конфигурация Tailwind CSS (токены, safelist)</td></tr>
                            <tr><td class="py-2 pr-4 font-mono text-dc-secondary">crm/vite.config.js</td><td class="py-2">Конфигурация Vite (бандлинг ассетов)</td></tr>
                            <tr><td class="py-2 pr-4 font-mono text-dc-secondary">crm/public/</td><td class="py-2">Публичная директория (index.php, собранные ассеты build/)</td></tr>
                            <tr><td class="py-2 pr-4 font-mono text-dc-secondary">crm/storage/logs/</td><td class="py-2">Логи Laravel (<code class="font-mono">laravel.log</code>)</td></tr>
                            <tr><td class="py-2 pr-4 font-mono text-dc-secondary">crm/tests/</td><td class="py-2">Тесты (Feature/, Unit/) — запуск: <code class="font-mono">php artisan test</code></td></tr>
                            <tr><td class="py-2 pr-4 font-mono text-dc-secondary">crm/.env</td><td class="py-2">Переменные окружения (не в git; шаблон: <code class="font-mono">.env.example</code>)</td></tr>
                            <tr><td class="py-2 pr-4 font-mono text-dc-secondary">docs/</td><td class="py-2">Техническая документация репозитория</td></tr>
                        </tbody>
                    </table>
                </div>
            </x-dc.card>

            {{-- ===== MAINTENANCE COMMANDS ===== --}}
            <x-dc.card padding="lg" shadow="card">
                <h3 class="text-ys-m-s font-semibold text-dc mb-4">🛠 Команды обслуживания (Git Bash)</h3>

                {{-- Git --}}
                <div class="mb-5">
                    <h4 class="font-semibold text-dc mb-2">Git</h4>
                    <div class="overflow-x-auto">
                        <table class="w-full text-ys-s text-dc">
                            <tbody class="divide-y divide-[color:var(--color-border)]">
                                <tr><td class="py-1.5 pr-4 font-mono text-dc-secondary whitespace-nowrap">git status</td><td class="py-1.5">Состояние рабочей директории</td></tr>
                                <tr><td class="py-1.5 pr-4 font-mono text-dc-secondary whitespace-nowrap">git branch -a</td><td class="py-1.5">Все ветки (локальные + remote)</td></tr>
                                <tr><td class="py-1.5 pr-4 font-mono text-dc-secondary whitespace-nowrap">git fetch origin</td><td class="py-1.5">Получить обновления без применения</td></tr>
                                <tr><td class="py-1.5 pr-4 font-mono text-dc-secondary whitespace-nowrap">git pull origin main</td><td class="py-1.5">Обновить текущую ветку из remote</td></tr>
                                <tr><td class="py-1.5 pr-4 font-mono text-dc-secondary whitespace-nowrap">git checkout -b feature/name</td><td class="py-1.5">Создать и переключиться на новую ветку</td></tr>
                                <tr><td class="py-1.5 pr-4 font-mono text-dc-secondary whitespace-nowrap">git stash -u</td><td class="py-1.5">Временно убрать все изменения (включая untracked)</td></tr>
                                <tr><td class="py-1.5 pr-4 font-mono text-dc-secondary whitespace-nowrap">git stash pop</td><td class="py-1.5">Вернуть спрятанные изменения</td></tr>
                                <tr><td class="py-1.5 pr-4 font-mono text-dc-secondary whitespace-nowrap">git diff</td><td class="py-1.5">Показать несохранённые изменения</td></tr>
                                <tr><td class="py-1.5 pr-4 font-mono text-dc-secondary whitespace-nowrap">git log --oneline -10</td><td class="py-1.5">Последние 10 коммитов</td></tr>
                                <tr><td class="py-1.5 pr-4 font-mono text-dc-secondary whitespace-nowrap">git reset --soft HEAD~1</td><td class="py-1.5">Отменить последний коммит (изменения останутся в staged)</td></tr>
                                <tr><td class="py-1.5 pr-4 font-mono text-dc-secondary whitespace-nowrap">git reset --hard HEAD</td><td class="py-1.5 text-amber-600 dark:text-amber-400">⚠ Сбросить рабочие изменения до HEAD — необратимо</td></tr>
                                <tr><td class="py-1.5 pr-4 font-mono text-dc-secondary whitespace-nowrap">git clean -fd</td><td class="py-1.5 text-amber-600 dark:text-amber-400">⚠ Удалить неотслеживаемые файлы и папки — необратимо</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Laravel --}}
                <div class="mb-5">
                    <h4 class="font-semibold text-dc mb-2">Laravel (выполнять внутри <code class="font-mono text-dc-secondary">crm/</code>)</h4>
                    <div class="overflow-x-auto">
                        <table class="w-full text-ys-s text-dc">
                            <tbody class="divide-y divide-[color:var(--color-border)]">
                                <tr><td class="py-1.5 pr-4 font-mono text-dc-secondary whitespace-nowrap">php artisan optimize:clear</td><td class="py-1.5">Очистить все кэши (config, route, view, cache)</td></tr>
                                <tr><td class="py-1.5 pr-4 font-mono text-dc-secondary whitespace-nowrap">php artisan config:cache</td><td class="py-1.5">Закэшировать конфигурацию (production)</td></tr>
                                <tr><td class="py-1.5 pr-4 font-mono text-dc-secondary whitespace-nowrap">php artisan route:cache</td><td class="py-1.5">Закэшировать роуты (production)</td></tr>
                                <tr><td class="py-1.5 pr-4 font-mono text-dc-secondary whitespace-nowrap">php artisan view:cache</td><td class="py-1.5">Предварительно скомпилировать шаблоны</td></tr>
                                <tr><td class="py-1.5 pr-4 font-mono text-dc-secondary whitespace-nowrap">php artisan migrate</td><td class="py-1.5">Применить новые миграции</td></tr>
                                <tr><td class="py-1.5 pr-4 font-mono text-dc-secondary whitespace-nowrap">php artisan migrate:status</td><td class="py-1.5">Показать статус всех миграций</td></tr>
                                <tr><td class="py-1.5 pr-4 font-mono text-dc-secondary whitespace-nowrap">php artisan migrate:rollback</td><td class="py-1.5 text-amber-600 dark:text-amber-400">⚠ Откатить последний batch миграций</td></tr>
                                <tr><td class="py-1.5 pr-4 font-mono text-dc-secondary whitespace-nowrap">php artisan db:seed</td><td class="py-1.5">Запустить seeders (первоначальные данные)</td></tr>
                                <tr><td class="py-1.5 pr-4 font-mono text-dc-secondary whitespace-nowrap">php artisan tinker</td><td class="py-1.5">Интерактивная REPL-консоль Laravel</td></tr>
                                <tr><td class="py-1.5 pr-4 font-mono text-dc-secondary whitespace-nowrap">php artisan route:list --path=kb</td><td class="py-1.5">Список роутов секции KB</td></tr>
                                <tr><td class="py-1.5 pr-4 font-mono text-dc-secondary whitespace-nowrap">php artisan storage:link</td><td class="py-1.5">Создать симлинк public/storage → storage/app/public</td></tr>
                                <tr><td class="py-1.5 pr-4 font-mono text-dc-secondary whitespace-nowrap">php artisan test</td><td class="py-1.5">Запустить тесты PHPUnit</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Composer --}}
                <div class="mb-5">
                    <h4 class="font-semibold text-dc mb-2">Composer (выполнять внутри <code class="font-mono text-dc-secondary">crm/</code>)</h4>
                    <div class="overflow-x-auto">
                        <table class="w-full text-ys-s text-dc">
                            <tbody class="divide-y divide-[color:var(--color-border)]">
                                <tr><td class="py-1.5 pr-4 font-mono text-dc-secondary whitespace-nowrap">composer install</td><td class="py-1.5">Установить зависимости по <code class="font-mono">composer.lock</code></td></tr>
                                <tr><td class="py-1.5 pr-4 font-mono text-dc-secondary whitespace-nowrap">composer install --no-dev</td><td class="py-1.5">Установить только production-зависимости</td></tr>
                                <tr><td class="py-1.5 pr-4 font-mono text-dc-secondary whitespace-nowrap">composer update</td><td class="py-1.5">Обновить зависимости и <code class="font-mono">composer.lock</code></td></tr>
                                <tr><td class="py-1.5 pr-4 font-mono text-dc-secondary whitespace-nowrap">composer dump-autoload</td><td class="py-1.5">Регенерировать autoload-карту классов</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Node / Vite --}}
                <div class="mb-5">
                    <h4 class="font-semibold text-dc mb-2">Node / Vite (выполнять внутри <code class="font-mono text-dc-secondary">crm/</code>)</h4>
                    <div class="overflow-x-auto">
                        <table class="w-full text-ys-s text-dc">
                            <tbody class="divide-y divide-[color:var(--color-border)]">
                                <tr><td class="py-1.5 pr-4 font-mono text-dc-secondary whitespace-nowrap">npm install</td><td class="py-1.5">Установить npm-зависимости</td></tr>
                                <tr><td class="py-1.5 pr-4 font-mono text-dc-secondary whitespace-nowrap">npm run dev</td><td class="py-1.5">Запустить Vite dev-сервер (HMR) для локальной разработки</td></tr>
                                <tr><td class="py-1.5 pr-4 font-mono text-dc-secondary whitespace-nowrap">npm run build</td><td class="py-1.5">Собрать production-ассеты в <code class="font-mono">public/build/</code></td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Logs --}}
                <div>
                    <h4 class="font-semibold text-dc mb-2">Логи Laravel</h4>
                    <ul class="space-y-2 text-ys-s text-dc">
                        <li>
                            <span class="font-medium">Файл логов:</span>
                            <code class="ml-1 font-mono text-dc-secondary">crm/storage/logs/laravel.log</code>
                        </li>
                        <li>
                            <span class="font-medium">Git Bash / Linux (если доступен <code class="font-mono">tail</code>):</span>
                            <code class="ml-1 font-mono text-dc-secondary">tail -f storage/logs/laravel.log</code>
                        </li>
                        <li>
                            <span class="font-medium">Git Bash (PowerShell-альтернатива):</span>
                            <code class="ml-1 font-mono text-dc-secondary">Get-Content storage/logs/laravel.log -Wait -Tail 50</code>
                        </li>
                        <li>
                            <span class="font-medium">Если <code class="font-mono">tail</code> недоступен в Git Bash на Windows:</span>
                            откройте файл в редакторе (VSCode) или используйте <code class="font-mono">cat storage/logs/laravel.log</code> для разового вывода.
                        </li>
                        <li class="text-dc-secondary">
                            <span class="font-medium text-dc">Примечание (Windows/OSPanel):</span>
                            убедитесь, что папка <code class="font-mono">storage/</code> доступна для записи PHP.
                            При использовании OSPanel права выставляются автоматически, но при ручном переносе файлов может потребоваться <code class="font-mono">chmod -R 775 storage bootstrap/cache</code> (в среде Linux/WSL).
                        </li>
                    </ul>
                </div>
            </x-dc.card>

        </div>
    </div>
</x-app-layout>
