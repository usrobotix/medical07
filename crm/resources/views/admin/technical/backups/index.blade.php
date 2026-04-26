<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-dc leading-tight">
            Технический раздел / Резервные копии
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Flash messages --}}
        @if(session('success'))
            <div class="mb-4 p-4 bg-dc-green-20 border border-dc-green-100 text-dc-green-100 rounded-2xs text-ys-s">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mb-4 p-4 bg-dc-red-20 border border-dc-red-100 text-dc-red-100 rounded-2xs text-ys-s">
                {{ session('error') }}
            </div>
        @endif

        {{-- Restore progress banner (filesystem-based, survives DB restore) --}}
        @if(session('restore_uuid'))
            <div x-data="restoreUuidPoller(
                    {{ json_encode(session('restore_uuid')) }},
                    {{ json_encode(route('admin.technical.backups.restore-status', ['restoreUuid' => session('restore_uuid')])) }}
                 )"
                 x-init="start()"
                 class="mb-4 p-4 bg-blue-50 border border-blue-300 text-blue-900 rounded-2xs">
                <div class="flex items-center gap-2 mb-2">
                    <strong>Восстановление БД</strong>
                    <span class="text-sm" x-text="statusLabel"></span>
                    <span class="text-xs text-blue-600" x-text="stepLabel ? ('— ' + stepLabel) : ''"></span>
                </div>
                <div class="w-full bg-blue-200 rounded-full h-2">
                    <div class="h-2 rounded-full bg-blue-600 transition-all duration-300"
                         :style="'width:' + progress + '%'"></div>
                </div>
                <div class="text-xs mt-1 text-blue-700" x-text="progress + '%'"></div>
                <div x-show="errorMsg" class="text-red-600 mt-1 text-xs" x-text="errorMsg"></div>
            </div>
        @endif

        {{-- Windows / OpenServer hint when queue driver is sync --}}
        @if($queueIsSync)
            <div class="mb-4 p-4 bg-yellow-50 border border-yellow-300 text-yellow-800 rounded-2xs text-sm" id="queue-sync-hint">
                <strong>&#9888; Очередь не настроена (QUEUE_CONNECTION=sync)</strong> — длинные резервные копии (файлы) могут прерваться из-за timeout HTTP-запроса.<br>
                Для надёжной фоновой обработки выполните в отдельном терминале:
                <ol class="mt-2 ml-4 list-decimal space-y-1">
                    <li>Добавьте в <code>.env</code>: <code>QUEUE_CONNECTION=database</code></li>
                    <li>Создайте таблицы очереди: <code>php artisan queue:table &amp;&amp; php artisan migrate</code></li>
                    <li>Запустите воркер: <code>php artisan queue:work --tries=1</code></li>
                </ol>
            </div>
        @endif

        <div class="flex gap-6">
            {{-- Sidebar --}}
            <div class="w-48 shrink-0">
                <x-dc.card class="p-3">
                    <nav class="space-y-1">
                        <a href="{{ route('admin.technical.backups.index') }}"
                           class="flex items-center gap-2 px-3 py-2 rounded text-sm font-medium bg-surface-hover text-dc-primary">
                            &#128190; Резервные копии
                        </a>
                        <a href="{{ route('admin.technical.schedule.index') }}"
                           class="flex items-center gap-2 px-3 py-2 rounded text-sm font-medium text-dc hover:bg-surface-hover dc-transition">
                            &#128336; Расписание
                        </a>
                        <a href="{{ route('admin.technical.audit.index') }}"
                           class="flex items-center gap-2 px-3 py-2 rounded text-sm font-medium text-dc hover:bg-surface-hover dc-transition">
                            &#128203; Журнал событий
                        </a>
                        <a href="{{ route('admin.technical.logs.index') }}"
                           class="flex items-center gap-2 px-3 py-2 rounded text-sm font-medium text-dc hover:bg-surface-hover dc-transition">
                            &#128196; Логи
                        </a>
                    </nav>
                </x-dc.card>
            </div>

            {{-- Main content --}}
            <div class="flex-1 space-y-6">
                {{-- Create Backup Form --}}
                <x-dc.card>
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-dc mb-4">Создать резервную копию</h3>
                        <form method="POST" action="{{ route('admin.technical.backups.store') }}"
                              x-data="{ type: 'full' }">
                            @csrf
                            <div class="space-y-4">
                                {{-- Type --}}
                                <div>
                                    <label class="block text-sm font-medium text-dc-secondary mb-2">Тип резервной копии</label>
                                    <div class="flex gap-6">
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input type="radio" name="type" value="db" x-model="type"
                                                   class="text-dc-primary">
                                            <span class="text-sm text-dc">Только БД</span>
                                        </label>
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input type="radio" name="type" value="files" x-model="type"
                                                   class="text-dc-primary">
                                            <span class="text-sm text-dc">Только файлы</span>
                                        </label>
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input type="radio" name="type" value="full" x-model="type"
                                                   class="text-dc-primary">
                                            <span class="text-sm text-dc">БД + файлы</span>
                                        </label>
                                    </div>
                                </div>

                                {{-- File Preset --}}
                                <div x-show="type === 'files' || type === 'full'">
                                    <label class="block text-sm font-medium text-dc-secondary mb-2">Файловый пресет</label>
                                    <select name="preset"
                                            class="bg-surface border border-dc rounded text-dc text-sm px-3 py-2">
                                        <option value="project">Проект (весь проект)</option>
                                        <option value="storage_app">Storage/app</option>
                                    </select>
                                </div>

                                {{-- Formats --}}
                                <div>
                                    <label class="block text-sm font-medium text-dc-secondary mb-2">Форматы</label>
                                    <div class="flex gap-6">
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input type="checkbox" name="formats[]" value="zip" checked
                                                   class="text-dc-primary">
                                            <span class="text-sm text-dc">.zip</span>
                                        </label>
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input type="checkbox" name="formats[]" value="tar_gz" checked
                                                   class="text-dc-primary">
                                            <span class="text-sm text-dc">.tar.gz</span>
                                        </label>
                                    </div>
                                </div>

                                {{-- Yandex --}}
                                <div>
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="checkbox" name="upload_yandex" value="1" checked
                                               class="text-dc-primary">
                                        <span class="text-sm text-dc">Загрузить на Яндекс.Диск</span>
                                    </label>
                                </div>

                                <div>
                                    <x-dc.button type="submit" variant="action" size="m">
                                        Создать резервную копию
                                    </x-dc.button>
                                </div>
                            </div>
                        </form>
                    </div>
                </x-dc.card>

                {{-- Yandex Disk Status --}}
                <x-dc.card>
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-dc">Яндекс.Диск</h3>
                                <p class="text-sm text-dc-secondary mt-1">
                                    @if($yandexConfigured)
                                        <x-dc.badge color="success">Настроен</x-dc.badge>
                                    @else
                                        <x-dc.badge color="warning">Не настроен (YANDEX_DISK_TOKEN не задан)</x-dc.badge>
                                    @endif
                                </p>
                            </div>
							
                            <div x-data="{ result: null, loading: false }">
                                <x-dc.button
    variant="contour"
    size="s"
    @click="loading=true; fetch('{{ route('admin.technical.backups.test-yandex') }}')
        .then(r=>r.json())
        .then(d=>{ result=d; loading=false; })
        .catch(e=>{ result={ok:false,message:e.message}; loading=false; })"
>
    <span x-show="!loading">Проверить соединение</span>
    <span x-show="loading">Проверяем...</span>
</x-dc.button>
                                <p x-show="result" class="mt-2 text-sm"
                                   :class="result?.ok ? 'text-green-600' : 'text-red-600'"
                                   x-text="result?.message"></p>
                            </div>
                        </div>
                    </div>
                </x-dc.card>

                {{-- Backups Table --}}
                <x-dc.card>
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-dc mb-4">История резервных копий</h3>
                        @if($backups->isEmpty())
                            <p class="text-dc-secondary text-sm">Резервных копий ещё нет.</p>
                        @else
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm">
                                    <thead>
                                        <tr class="border-b border-dc text-left">
                                            <th class="pb-2 font-medium text-dc-secondary">Дата</th>
                                            <th class="pb-2 font-medium text-dc-secondary">Тип</th>
                                            <th class="pb-2 font-medium text-dc-secondary">Пресет</th>
                                            <th class="pb-2 font-medium text-dc-secondary">Форматы</th>
                                            <th class="pb-2 font-medium text-dc-secondary">Размер</th>
                                            <th class="pb-2 font-medium text-dc-secondary">Статус / Прогресс</th>
                                            <th class="pb-2 font-medium text-dc-secondary">Инициатор</th>
                                            <th class="pb-2 font-medium text-dc-secondary">Действия</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-dc">
                                        @foreach($backups as $backup)
                                            @php
                                                $isActive = in_array($backup->status, ['queued', 'running']);
                                                $statusColors = ['queued'=>'gray','pending'=>'gray','running'=>'info','done'=>'success','failed'=>'error'];
                                            @endphp
                                            <tr class="hover:bg-surface-hover dc-transition"
                                                @if($isActive)
                                                    x-data="backupPoller({{ $backup->id }}, {{ json_encode(route('admin.technical.backups.status', $backup)) }})"
                                                    x-init="start()"
                                                @endif
                                                id="backup-row-{{ $backup->id }}">
                                                <td class="py-2 text-dc">
                                                    {{ $backup->created_at->timezone(config('app.timezone'))->format('d.m.Y H:i') }}
                                                </td>
                                                <td class="py-2 text-dc">
                                                    @php
                                                        $typeLabels = ['db'=>'БД','files'=>'Файлы','full'=>'Полная'];
                                                        $kindLabels = ['backup'=>'', 'restore'=>' (Восстановление)', 'safety_snapshot'=>' (Снимок)'];
                                                        $kindLabel  = $kindLabels[$backup->kind ?? 'backup'] ?? '';
                                                    @endphp
                                                    {{ ($typeLabels[$backup->type] ?? $backup->type) . $kindLabel }}
                                                </td>
                                                <td class="py-2 text-dc-secondary">{{ $backup->file_preset ?? '—' }}</td>
                                                <td class="py-2 text-dc-secondary">{{ implode(', ', $backup->formats ?? []) }}</td>
                                                <td class="py-2 text-dc">{{ $backup->formatted_size }}</td>
                                                <td class="py-2 min-w-[180px]">
                                                    @if($isActive)
                                                        {{-- Live status cell --}}
                                                        <div>
                                                            <div class="flex items-center gap-2 mb-1">
                                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium"
                                                                      :class="{
                                                                          'bg-blue-100 text-blue-700': status === 'running',
                                                                          'bg-gray-100 text-gray-600': status === 'queued' || status === 'pending',
                                                                          'bg-green-100 text-green-700': status === 'done',
                                                                          'bg-red-100 text-red-700': status === 'failed',
                                                                      }"
                                                                      x-text="statusLabel"></span>
                                                                <span class="text-xs text-dc-secondary" x-text="stepLabel"></span>
                                                            </div>
                                                            <div class="w-full bg-gray-200 rounded-full h-1.5">
                                                                <div class="h-1.5 rounded-full transition-all duration-300"
                                                                     :class="{
                                                                         'bg-blue-500': status === 'running',
                                                                         'bg-gray-400': status === 'queued' || status === 'pending',
                                                                         'bg-green-500': status === 'done',
                                                                         'bg-red-500': status === 'failed',
                                                                     }"
                                                                     :style="'width:' + progress + '%'"></div>
                                                            </div>
                                                            <div class="text-xs text-dc-secondary mt-0.5" x-text="progress + '%'"></div>
                                                            <div x-show="errorMsg" class="text-xs text-red-600 mt-1" x-text="errorMsg"></div>
                                                        </div>
                                                    @else
                                                        <div>
                                                            <x-dc.badge :color="$statusColors[$backup->status] ?? 'gray'">
                                                                {{ $backup->status }}
                                                            </x-dc.badge>
                                                            @if($backup->error_message)
                                                                <div class="text-xs text-red-600 mt-1">{{ \Illuminate\Support\Str::limit($backup->error_message, 80) }}</div>
                                                            @endif
                                                        </div>
                                                    @endif
                                                </td>
                                                <td class="py-2 text-dc-secondary">
                                                    @if($backup->initiated_by === 'user' && $backup->user)
                                                        {{ $backup->user->name }}
                                                    @else
                                                        cron
                                                    @endif
                                                </td>
                                                 <td class="py-2">
                                                    <div class="flex items-center gap-2 flex-wrap">
                                                        @if($backup->status === 'done' && !empty($backup->local_paths))
                                                            @foreach($backup->local_paths as $fmt => $path)
                                                                @if(is_file($path))
                                                                    <a href="{{ route('admin.technical.backups.download', [$backup, 'fmt' => $fmt]) }}"
                                                                       class="text-dc-primary hover:underline text-xs">&#8595;{{ $fmt }}</a>
                                                                @endif
                                                            @endforeach
                                                        @endif

                                                        {{-- Restore button: only for done db/full backups of kind=backup --}}
                                                        @if($backup->status === 'done'
                                                            && in_array($backup->type, ['db', 'full'])
                                                            && ($backup->kind ?? 'backup') === 'backup')
                                                            <div x-data="restoreModal({{ $backup->id }}, '{{ route('admin.technical.backups.restore', $backup) }}')">
                                                                <button type="button"
                                                                        @click="open = true"
                                                                        class="text-orange-600 hover:text-orange-800 text-xs dc-transition font-medium">
                                                                    &#9888; Восстановить БД
                                                                </button>

                                                                {{-- Confirmation modal --}}
                                                                <div x-show="open" x-cloak
                                                                     class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
                                                                     @keydown.escape.window="open = false">
                                                                    <div class="bg-surface rounded-lg shadow-xl max-w-md w-full mx-4 p-6"
                                                                         @click.outside="open = false">
                                                                        <h3 class="text-lg font-semibold text-dc mb-2">&#9888; Восстановление БД</h3>
                                                                        <p class="text-sm text-dc-secondary mb-3">
                                                                            Будет восстановлена резервная копия
                                                                            <strong>{{ $backup->created_at->timezone(config('app.timezone'))->format('d.m.Y H:i') }}</strong>
                                                                            ({{ $backup->type }}).
                                                                        </p>
                                                                        <p class="text-sm text-red-600 mb-4">
                                                                            Перед восстановлением автоматически создаётся снимок текущей БД.
                                                                            Тем не менее, текущие данные будут <strong>перезаписаны</strong>.
                                                                        </p>
                                                                        @if($queueIsSync)
                                                                            <div class="mb-4 p-3 bg-yellow-50 border border-yellow-300 text-yellow-800 rounded text-xs">
                                                                                <strong>&#9888; Очередь не настроена (sync)</strong> — восстановление выполнится синхронно и может превысить timeout.<br>
                                                                                Рекомендуется: установить <code>QUEUE_CONNECTION=database</code> и запустить <code>php artisan queue:work --tries=1</code>.
                                                                            </div>
                                                                        @endif
                                                                        <label class="flex items-start gap-2 mb-4 cursor-pointer">
                                                                            <input type="checkbox" x-model="confirmed" class="mt-0.5">
                                                                            <span class="text-sm text-dc">Я понимаю, что текущая БД будет перезаписана</span>
                                                                        </label>
                                                                        <div class="flex gap-3 justify-end">
                                                                            <button type="button" @click="open = false"
                                                                                    class="px-4 py-2 text-sm text-dc-secondary hover:text-dc dc-transition">
                                                                                Отмена
                                                                            </button>
                                                                            <form :action="url" method="POST">
                                                                                @csrf
                                                                                <input type="hidden" name="confirmed" value="1">
                                                                                <button type="submit"
                                                                                        :disabled="!confirmed"
                                                                                        class="px-4 py-2 text-sm font-medium rounded bg-red-600 text-white
                                                                                               disabled:opacity-40 disabled:cursor-not-allowed
                                                                                               hover:bg-red-700 dc-transition">
                                                                                    Восстановить
                                                                                </button>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif

                                                        @if(!$isActive)
                                                        <form method="POST"
                                                              action="{{ route('admin.technical.backups.destroy', $backup) }}"
                                                              onsubmit="return confirm('Удалить эту резервную копию?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                    class="text-red-500 hover:text-red-700 text-xs dc-transition">
                                                                Удалить
                                                            </button>
                                                        </form>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-4">
                                {{ $backups->links() }}
                            </div>
                        @endif
                    </div>
                </x-dc.card>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    const stepLabelsMap = {
        queued:        'В очереди',
        pending:       'В очереди',
        prepare:       'Подготовка',
        db_dump:       'Дамп БД',
        file_archive:  'Архивация файлов',
        yandex_upload: 'Загрузка на Я.Диск',
        finalize:      'Завершение',
        done:          'Готово',
        failed:        'Ошибка',
        // restore steps
        snapshot:      'Снимок безопасности',
        download:      'Загрузка архива',
        extract:       'Извлечение дампа',
        restore_db:    'Восстановление БД',
    };

    const statusLabelsMap = {
        queued:  'В очереди',
        pending: 'В очереди',
        running: 'Выполняется',
        done:    'Готово',
        failed:  'Ошибка',
    };

    function backupPoller(backupId, statusUrl) {
        return {
            status:      'queued',
            progress:    0,
            stepLabel:   '',
            statusLabel: '',
            errorMsg:    '',
            _timer:      null,

            start() {
                this._poll();
            },

            _poll() {
                this._timer = setInterval(() => {
                    fetch(statusUrl)
                        .then(r => {
                            // Stop polling gracefully when the record is gone (404 after DB restore).
                            if (r.status === 404 || r.status === 410) {
                                clearInterval(this._timer);
                                return null;
                            }
                            return r.json();
                        })
                        .then(data => {
                            if (!data) return;
                            this.status      = data.status;
                            this.progress    = data.progress_percent ?? this.progress;
                            this.stepLabel   = stepLabelsMap[data.current_step] || (data.current_step || '');
                            this.statusLabel = statusLabelsMap[data.status] || data.status;
                            this.errorMsg    = data.error_message || '';

                            if (data.status === 'done' || data.status === 'failed') {
                                clearInterval(this._timer);
                                setTimeout(() => location.reload(), 1500);
                            }
                        })
                        .catch(() => {/* silent */});
                }, 2000);
            },
        };
    }

    /**
     * Polls the filesystem-based restore status endpoint (/backups/restore/{uuid}/status).
     * Works even after the backups table has been wiped and recreated by the SQL restore.
     */
    function restoreUuidPoller(uuid, statusUrl) {
        return {
            status:      'queued',
            progress:    0,
            stepLabel:   '',
            statusLabel: 'В очереди',
            errorMsg:    '',
            _timer:      null,

            start() {
                this._poll();
            },

            _poll() {
                this._timer = setInterval(() => {
                    fetch(statusUrl)
                        .then(r => {
                            // 410 Gone: file cleaned up after completion or never existed.
                            if (r.status === 410 || r.status === 404) {
                                clearInterval(this._timer);
                                setTimeout(() => location.reload(), 1500);
                                return null;
                            }
                            return r.json();
                        })
                        .then(data => {
                            if (!data) return;
                            this.status      = data.status;
                            this.progress    = data.progress_percent ?? this.progress;
                            this.stepLabel   = stepLabelsMap[data.current_step] || (data.current_step || '');
                            this.statusLabel = statusLabelsMap[data.status] || data.status;
                            this.errorMsg    = data.error_message || '';

                            if (data.status === 'done' || data.status === 'failed' || data.status === 'gone') {
                                clearInterval(this._timer);
                                setTimeout(() => location.reload(), 1500);
                            }
                        })
                        .catch(() => {/* silent */});
                }, 2000);
            },
        };
    }

    function restoreModal(backupId, url) {
        return {
            open:      false,
            confirmed: false,
            backupId:  backupId,
            url:       url,
        };
    }
    </script>
    @endpush
</x-app-layout>
