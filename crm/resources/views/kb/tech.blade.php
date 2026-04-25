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

            {{-- ===== SITE SECTIONS ===== --}}

            {{-- Dashboard --}}
            <x-dc.card padding="lg" shadow="card">
                <h3 class="text-ys-m-s font-semibold text-dc mb-3">🏠 Dashboard <code class="text-ys-s font-mono text-dc-secondary">/dashboard</code></h3>
                <ul class="space-y-2 text-ys-s text-dc">
                    <li><span class="font-medium">Назначение:</span> стартовая страница после входа — сводка разделов CRM и быстрые ссылки.</li>
                    <li>
                        <span class="font-medium">Route:</span>
                        <code class="ml-1 font-mono text-dc-secondary">routes/web.php</code>
                        → <code class="font-mono">Route::get('/dashboard', fn () =&gt; view('dashboard'))</code>
                    </li>
                    <li>
                        <span class="font-medium">View:</span>
                        <code class="ml-1 font-mono text-dc-secondary">resources/views/dashboard.blade.php</code>
                    </li>
                    <li>
                        <span class="font-medium">Middleware:</span>
                        <code class="ml-1 font-mono">auth</code>, <code class="font-mono">verified</code>
                    </li>
                    <li><span class="font-medium">Где менять UI:</span> редактируй <code class="font-mono">dashboard.blade.php</code> и карточки внутри.</li>
                </ul>
            </x-dc.card>

            {{-- Profile --}}
            <x-dc.card padding="lg" shadow="card">
                <h3 class="text-ys-m-s font-semibold text-dc mb-3">👤 Profile <code class="text-ys-s font-mono text-dc-secondary">/profile</code></h3>
                <ul class="space-y-2 text-ys-s text-dc">
                    <li><span class="font-medium">Назначение:</span> редактирование имени, почты, пароля; удаление аккаунта.</li>
                    <li>
                        <span class="font-medium">Routes:</span>
                        <code class="ml-1 font-mono text-dc-secondary">routes/web.php</code>
                        → <code class="font-mono">GET/PATCH/DELETE /profile</code>
                    </li>
                    <li>
                        <span class="font-medium">Controller:</span>
                        <code class="ml-1 font-mono text-dc-secondary">app/Http/Controllers/ProfileController.php</code>
                        (методы <code class="font-mono">edit</code>, <code class="font-mono">update</code>, <code class="font-mono">destroy</code>)
                    </li>
                    <li>
                        <span class="font-medium">Views:</span>
                        <code class="ml-1 font-mono text-dc-secondary">resources/views/profile/</code>
                    </li>
                    <li>
                        <span class="font-medium">Middleware:</span>
                        <code class="ml-1 font-mono">auth</code>
                    </li>
                </ul>
            </x-dc.card>

            {{-- Cases --}}
            <x-dc.card padding="lg" shadow="card">
                <h3 class="text-ys-m-s font-semibold text-dc mb-3">📋 Cases <code class="text-ys-s font-mono text-dc-secondary">/cases</code></h3>
                <ul class="space-y-2 text-ys-s text-dc">
                    <li><span class="font-medium">Назначение:</span> создание и управление медицинскими кейсами пациентов.</li>
                    <li>
                        <span class="font-medium">Список / создание:</span>
                        <code class="ml-1 font-mono text-dc-secondary">app/Http/Controllers/MedicalCaseController.php</code>
                        → resource (index, create, store)
                        | Views: <code class="font-mono">resources/views/cases/</code>
                    </li>
                    <li>
                        <span class="font-medium">Доска (board):</span>
                        <code class="ml-1 font-mono text-dc-secondary">GET /cases/board</code>
                        → <code class="font-mono">app/Http/Controllers/CaseBoardController.php@index</code>
                    </li>
                    <li>
                        <span class="font-medium">Смена статуса — pipeline:</span>
                        <code class="ml-1 font-mono text-dc-secondary">PATCH /cases/{case}/pipeline-status</code>
                        → <code class="font-mono">CaseStatusController@updatePipeline</code>
                    </li>
                    <li>
                        <span class="font-medium">Смена статуса — service:</span>
                        <code class="ml-1 font-mono text-dc-secondary">PATCH /cases/{case}/service-status</code>
                        → <code class="font-mono">CaseStatusController@updateService</code>
                    </li>
                    <li>
                        <span class="font-medium">Controller статусов:</span>
                        <code class="ml-1 font-mono text-dc-secondary">app/Http/Controllers/CaseStatusController.php</code>
                    </li>
                    <li>
                        <span class="font-medium">Middleware:</span>
                        <code class="ml-1 font-mono">auth</code>
                    </li>
                </ul>
            </x-dc.card>

            {{-- Patients --}}
            <x-dc.card padding="lg" shadow="card">
                <h3 class="text-ys-m-s font-semibold text-dc mb-3">🏥 Patients <code class="text-ys-s font-mono text-dc-secondary">/patients</code></h3>
                <ul class="space-y-2 text-ys-s text-dc">
                    <li><span class="font-medium">Назначение:</span> справочник пациентов — список и добавление.</li>
                    <li>
                        <span class="font-medium">Routes:</span>
                        resource (index, create, store) в <code class="ml-1 font-mono text-dc-secondary">routes/web.php</code>
                    </li>
                    <li>
                        <span class="font-medium">Controller:</span>
                        <code class="ml-1 font-mono text-dc-secondary">app/Http/Controllers/PatientController.php</code>
                    </li>
                    <li>
                        <span class="font-medium">Views:</span>
                        <code class="ml-1 font-mono text-dc-secondary">resources/views/patients/</code>
                    </li>
                    <li>
                        <span class="font-medium">Middleware:</span>
                        <code class="ml-1 font-mono">auth</code>
                    </li>
                </ul>
            </x-dc.card>

            {{-- KB Read --}}
            <x-dc.card padding="lg" shadow="card">
                <h3 class="text-ys-m-s font-semibold text-dc mb-3">📖 KB — зона чтения <code class="text-ys-s font-mono text-dc-secondary">/kb/*</code></h3>
                <ul class="space-y-2 text-ys-s text-dc">
                    <li><span class="font-medium">Назначение:</span> просмотр справочников (партнёры, страны, ниши, направления, чек-листы, шаблоны сообщений, верификации).</li>
                    <li>
                        <span class="font-medium">Routes:</span>
                        <code class="ml-1 font-mono text-dc-secondary">routes/web.php</code>
                        → группа <code class="font-mono">Route::prefix('kb')->name('kb.')</code>
                        — только <code class="font-mono">index</code> и <code class="font-mono">show</code> для каждого resource.
                    </li>
                    <li>
                        <span class="font-medium">Controllers:</span>
                        <code class="ml-1 font-mono text-dc-secondary">app/Http/Controllers/Kb/</code>
                    </li>
                    <li>
                        <span class="font-medium">Views:</span>
                        <code class="ml-1 font-mono text-dc-secondary">resources/views/kb/</code>
                        (подпапки по разделам)
                    </li>
                    <li>
                        <span class="font-medium">Middleware:</span>
                        <code class="ml-1 font-mono">auth</code> — доступно всем авторизованным.
                    </li>
                </ul>
            </x-dc.card>

            {{-- KB Write / Admin --}}
            <x-dc.card padding="lg" shadow="card">
                <h3 class="text-ys-m-s font-semibold text-dc mb-3">✏️ KB — зона записи / администрирование</h3>
                <p class="text-ys-s text-dc mb-3">Те же prefix <code class="font-mono">/kb</code>, но группа с middleware <code class="font-mono">role:admin|manager</code>.</p>
                <div class="overflow-x-auto">
                    <table class="w-full text-ys-s text-dc border-collapse">
                        <thead>
                            <tr class="border-b" style="border-color:var(--color-border)">
                                <th class="text-left py-1.5 pr-4 font-medium text-dc-secondary">Раздел</th>
                                <th class="text-left py-1.5 pr-4 font-medium text-dc-secondary">Views</th>
                                <th class="text-left py-1.5 font-medium text-dc-secondary">Controller</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y" style="border-color:var(--color-border)">
                            <tr>
                                <td class="py-1.5 pr-4">Partners</td>
                                <td class="py-1.5 pr-4"><code class="font-mono text-dc-secondary">kb/partners/</code></td>
                                <td class="py-1.5"><code class="font-mono text-dc-secondary">Kb/PartnerController</code></td>
                            </tr>
                            <tr>
                                <td class="py-1.5 pr-4">Countries</td>
                                <td class="py-1.5 pr-4"><code class="font-mono text-dc-secondary">kb/countries/</code></td>
                                <td class="py-1.5"><code class="font-mono text-dc-secondary">Kb/CountryController</code></td>
                            </tr>
                            <tr>
                                <td class="py-1.5 pr-4">Niches</td>
                                <td class="py-1.5 pr-4"><code class="font-mono text-dc-secondary">kb/niches/</code></td>
                                <td class="py-1.5"><code class="font-mono text-dc-secondary">Kb/NicheController</code></td>
                            </tr>
                            <tr>
                                <td class="py-1.5 pr-4">Country Directions</td>
                                <td class="py-1.5 pr-4"><code class="font-mono text-dc-secondary">kb/country-directions/</code></td>
                                <td class="py-1.5"><code class="font-mono text-dc-secondary">Kb/CountryDirectionController</code></td>
                            </tr>
                            <tr>
                                <td class="py-1.5 pr-4">Verification Checklists</td>
                                <td class="py-1.5 pr-4"><code class="font-mono text-dc-secondary">kb/verification-checklists/</code></td>
                                <td class="py-1.5"><code class="font-mono text-dc-secondary">Kb/VerificationChecklistController</code></td>
                            </tr>
                            <tr>
                                <td class="py-1.5 pr-4">Checklist Items</td>
                                <td class="py-1.5 pr-4"><code class="font-mono text-dc-secondary">(inline в чек-листе)</code></td>
                                <td class="py-1.5"><code class="font-mono text-dc-secondary">Kb/VerificationChecklistItemController</code></td>
                            </tr>
                            <tr>
                                <td class="py-1.5 pr-4">Message Templates</td>
                                <td class="py-1.5 pr-4"><code class="font-mono text-dc-secondary">kb/message-templates/</code></td>
                                <td class="py-1.5"><code class="font-mono text-dc-secondary">Kb/MessageTemplateController</code></td>
                            </tr>
                            <tr>
                                <td class="py-1.5 pr-4">Partner Verifications</td>
                                <td class="py-1.5 pr-4"><code class="font-mono text-dc-secondary">kb/partner-verifications/</code></td>
                                <td class="py-1.5"><code class="font-mono text-dc-secondary">Kb/PartnerVerificationController</code></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <ul class="space-y-1.5 text-ys-s text-dc mt-3">
                    <li>
                        <span class="font-medium">Partners — start-verification:</span>
                        <code class="ml-1 font-mono text-dc-secondary">POST /kb/partners/{partner}/start-verification</code>
                    </li>
                    <li>
                        <span class="font-medium">Partner Verifications — bulk update items:</span>
                        <code class="ml-1 font-mono text-dc-secondary">POST /kb/partner-verifications/{id}/items/update-bulk</code>
                    </li>
                    <li>
                        <span class="font-medium">Middleware:</span>
                        <code class="ml-1 font-mono">auth</code> + <code class="font-mono">role:admin|manager</code>
                    </li>
                </ul>
            </x-dc.card>

            {{-- Auth --}}
            <x-dc.card padding="lg" shadow="card">
                <h3 class="text-ys-m-s font-semibold text-dc mb-3">🔑 Auth <code class="text-ys-s font-mono text-dc-secondary">routes/auth.php</code></h3>
                <ul class="space-y-2 text-ys-s text-dc">
                    <li><span class="font-medium">Назначение:</span> вход, регистрация, сброс пароля, подтверждение email.</li>
                    <li>
                        <span class="font-medium">Routes:</span>
                        <code class="ml-1 font-mono text-dc-secondary">routes/auth.php</code>
                        (подключается в конце <code class="font-mono">routes/web.php</code>)
                    </li>
                    <li>
                        <span class="font-medium">Controllers:</span>
                        <code class="ml-1 font-mono text-dc-secondary">app/Http/Controllers/Auth/</code>
                    </li>
                    <li>
                        <span class="font-medium">Views:</span>
                        <code class="ml-1 font-mono text-dc-secondary">resources/views/auth/</code>
                        (Breeze-стек)
                    </li>
                </ul>
            </x-dc.card>

            {{-- ===== STYLES & COMPONENTS ===== --}}

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

            {{-- ===== PROJECT STRUCTURE ===== --}}

            <x-dc.card padding="lg" shadow="card">
                <h3 class="text-ys-m-s font-semibold text-dc mb-3">🗂 Структура проекта (crm/)</h3>
                <ul class="space-y-2 text-ys-s text-dc">
                    <li>
                        <code class="font-mono text-dc-secondary">app/Http/Controllers/</code>
                        — все контроллеры; KB-контроллеры — в подпапке <code class="font-mono">Kb/</code>
                    </li>
                    <li>
                        <code class="font-mono text-dc-secondary">app/Models/</code>
                        — Eloquent-модели
                    </li>
                    <li>
                        <code class="font-mono text-dc-secondary">app/Providers/</code>
                        — сервис-провайдеры (AuthServiceProvider, AppServiceProvider…)
                    </li>
                    <li>
                        <code class="font-mono text-dc-secondary">app/Policies/</code>
                        — политики авторизации (если добавляются)
                    </li>
                    <li>
                        <code class="font-mono text-dc-secondary">routes/web.php</code>
                        — все веб-роуты (dashboard, profile, cases, patients, KB read/write)
                    </li>
                    <li>
                        <code class="font-mono text-dc-secondary">routes/auth.php</code>
                        — роуты аутентификации (Breeze)
                    </li>
                    <li>
                        <code class="font-mono text-dc-secondary">resources/views/</code>
                        — Blade-шаблоны: <code class="font-mono">dashboard</code>, <code class="font-mono">cases/</code>, <code class="font-mono">patients/</code>, <code class="font-mono">profile/</code>, <code class="font-mono">auth/</code>, <code class="font-mono">kb/</code>, <code class="font-mono">layouts/</code>, <code class="font-mono">components/</code>
                    </li>
                    <li>
                        <code class="font-mono text-dc-secondary">resources/css/app.css</code>
                        — CSS-переменные и базовые стили
                    </li>
                    <li>
                        <code class="font-mono text-dc-secondary">resources/js/app.js</code>
                        — точка входа JS (Alpine.js, axios…)
                    </li>
                    <li>
                        <code class="font-mono text-dc-secondary">database/migrations/</code>
                        — миграции БД
                    </li>
                    <li>
                        <code class="font-mono text-dc-secondary">database/seeders/</code>
                        — сидеры (начальные данные)
                    </li>
                    <li>
                        <code class="font-mono text-dc-secondary">config/</code>
                        — конфиги Laravel и пакетов (в т.ч. <code class="font-mono">permission.php</code> — Spatie)
                    </li>
                    <li>
                        <code class="font-mono text-dc-secondary">public/</code>
                        — webroot: <code class="font-mono">index.php</code>, скомпилированные ассеты (<code class="font-mono">build/</code>)
                    </li>
                    <li>
                        <code class="font-mono text-dc-secondary">storage/logs/laravel.log</code>
                        — лог-файл приложения
                    </li>
                    <li>
                        <code class="font-mono text-dc-secondary">vite.config.js</code> / <code class="font-mono">tailwind.config.js</code>
                        — настройки сборки фронтенда
                    </li>
                    <li>
                        <code class="font-mono text-dc-secondary">composer.json</code> / <code class="font-mono">package.json</code>
                        — зависимости PHP и JS
                    </li>
                </ul>
            </x-dc.card>

            {{-- ===== KB PAGES OVERVIEW ===== --}}

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
                    <li>
                        Страница <code class="font-mono">/kb/tech</code> также защищена: <code class="font-mono">auth</code> + <code class="font-mono">role:admin|manager</code>.
                    </li>
                </ul>
            </x-dc.card>

            {{-- ===== MAINTENANCE COMMANDS ===== --}}

            <x-dc.card padding="lg" shadow="card">
                <h3 class="text-ys-m-s font-semibold text-dc mb-3">🛠 Команды обслуживания (Git Bash)</h3>

                <p class="text-ys-s font-semibold text-dc mb-1">Git</p>
                <pre class="text-ys-xs font-mono text-dc-secondary bg-dc-bg rounded p-3 mb-4 overflow-x-auto whitespace-pre-wrap">git status
git branch --show-current
git fetch --all --prune
git pull origin main
git log -n 20 --oneline --decorate
git diff
git diff main..feature/my-branch

# Временно убрать незакоммиченные правки
git stash -u
git stash pop

# Откатить конкретный файл
git restore path/to/file

# ⚠️ Осторожно: сбросить ВСЕ незакоммиченные изменения
git reset --hard
git clean -fd</pre>

                <p class="text-ys-s font-semibold text-dc mb-1">Laravel <span class="font-normal text-dc-secondary">(запускать из папки <code class="font-mono">crm/</code>)</span></p>
                <pre class="text-ys-xs font-mono text-dc-secondary bg-dc-bg rounded p-3 mb-4 overflow-x-auto whitespace-pre-wrap">php artisan optimize:clear          # очистить все кэши сразу
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

php artisan route:list              # просмотр всех роутов
php artisan route:list --path=kb   # фильтр по KB

php artisan migrate:status          # статус миграций
php artisan migrate                 # накатить новые миграции

php artisan storage:link            # симлинк public/storage → storage/app/public

php artisan tinker                  # интерактивная REPL-консоль</pre>

                <p class="text-ys-s font-semibold text-dc mb-1">Composer</p>
                <pre class="text-ys-xs font-mono text-dc-secondary bg-dc-bg rounded p-3 mb-4 overflow-x-auto whitespace-pre-wrap">composer install          # установить зависимости из composer.lock
composer dump-autoload    # перегенерировать autoload-карты</pre>

                <p class="text-ys-s font-semibold text-dc mb-1">Node / Vite <span class="font-normal text-dc-secondary">(package.json присутствует)</span></p>
                <pre class="text-ys-xs font-mono text-dc-secondary bg-dc-bg rounded p-3 mb-4 overflow-x-auto whitespace-pre-wrap">npm ci          # чистая установка из package-lock.json (CI/prod)
npm install     # установка/обновление зависимостей
npm run dev     # dev-сервер с hot-reload
npm run build   # сборка production-ассетов в public/build/</pre>

                <p class="text-ys-s font-semibold text-dc mb-1">Логи</p>
                <pre class="text-ys-xs font-mono text-dc-secondary bg-dc-bg rounded p-3 overflow-x-auto whitespace-pre-wrap"># Git Bash / Linux / macOS:
tail -f storage/logs/laravel.log

# Windows PowerShell (если tail недоступен):
Get-Content storage\logs\laravel.log -Tail 100 -Wait</pre>
            </x-dc.card>

            {{-- Cache commands --}}
            <x-dc.card padding="lg" shadow="card">
                <h3 class="text-ys-m-s font-semibold text-dc mb-3">⚡ Быстрая очистка кэша</h3>
                <ul class="space-y-2 text-ys-s text-dc">
                    <li><code class="font-mono text-dc-secondary">php artisan optimize:clear</code> — очистить все кэши (config, route, view, cache)</li>
                    <li><code class="font-mono text-dc-secondary">php artisan config:clear</code> — только кэш конфигурации</li>
                    <li><code class="font-mono text-dc-secondary">php artisan route:clear</code> — только кэш роутов</li>
                    <li><code class="font-mono text-dc-secondary">php artisan view:clear</code> — только кэш шаблонов</li>
                    <li><code class="font-mono text-dc-secondary">php artisan cache:clear</code> — только кэш приложения</li>
                    <li><code class="font-mono text-dc-secondary">npm run build</code> — пересборка фронтенд-ассетов</li>
                </ul>
            </x-dc.card>

        </div>
    </div>
</x-app-layout>
