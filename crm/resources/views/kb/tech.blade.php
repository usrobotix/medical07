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

        </div>
    </div>
</x-app-layout>
