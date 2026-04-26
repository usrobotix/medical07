<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-dc leading-tight">
            Технический раздел / Резервные копии
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Flash messages --}}
        @if(session('success'))
            <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-800 rounded-md">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-800 rounded-md">
                {{ session('error') }}
            </div>
        @endif

        <div class="flex gap-6">
            {{-- Sidebar --}}
            <div class="w-48 shrink-0">
                <x-dc.card class="p-3">
                    <nav class="space-y-1">
                        <a href="{{ route('admin.technical.backups.index') }}"
                           class="flex items-center gap-2 px-3 py-2 rounded text-sm font-medium bg-surface-hover text-dc-primary">
                            💾 Резервные копии
                        </a>
                        <a href="{{ route('admin.technical.schedule.index') }}"
                           class="flex items-center gap-2 px-3 py-2 rounded text-sm font-medium text-dc hover:bg-surface-hover dc-transition">
                            🕐 Расписание
                        </a>
                        <a href="{{ route('admin.technical.audit.index') }}"
                           class="flex items-center gap-2 px-3 py-2 rounded text-sm font-medium text-dc hover:bg-surface-hover dc-transition">
                            📋 Журнал событий
                        </a>
                        <a href="{{ route('admin.technical.logs.index') }}"
                           class="flex items-center gap-2 px-3 py-2 rounded text-sm font-medium text-dc hover:bg-surface-hover dc-transition">
                            📄 Логи
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
                                <x-dc.button variant="contour" size="s"
                                             @click="loading=true; fetch('{{ route('admin.technical.backups.test-yandex') }}')
                                                .then(r=>r.json())
                                                .then(d=>{ result=d; loading=false; })
                                                .catch(e=>{ result={ok:false,message:e.message}; loading=false; })">
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
                                            <th class="pb-2 font-medium text-dc-secondary">Статус</th>
                                            <th class="pb-2 font-medium text-dc-secondary">Инициатор</th>
                                            <th class="pb-2 font-medium text-dc-secondary">Действия</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-dc">
                                        @foreach($backups as $backup)
                                            <tr class="hover:bg-surface-hover dc-transition">
                                                <td class="py-2 text-dc">{{ $backup->created_at->format('d.m.Y H:i') }}</td>
                                                <td class="py-2 text-dc">
                                                    @php $typeLabels = ['db'=>'БД','files'=>'Файлы','full'=>'Полная']; @endphp
                                                    {{ $typeLabels[$backup->type] ?? $backup->type }}
                                                </td>
                                                <td class="py-2 text-dc-secondary">{{ $backup->file_preset ?? '—' }}</td>
                                                <td class="py-2 text-dc-secondary">{{ implode(', ', $backup->formats ?? []) }}</td>
                                                <td class="py-2 text-dc">{{ $backup->formatted_size }}</td>
                                                <td class="py-2">
                                                    @php $statusColors = ['pending'=>'gray','running'=>'info','done'=>'success','failed'=>'error']; @endphp
                                                    <x-dc.badge :color="$statusColors[$backup->status] ?? 'gray'">
                                                        {{ $backup->status }}
                                                    </x-dc.badge>
                                                </td>
                                                <td class="py-2 text-dc-secondary">
                                                    @if($backup->initiated_by === 'user' && $backup->user)
                                                        {{ $backup->user->name }}
                                                    @else
                                                        cron
                                                    @endif
                                                </td>
                                                <td class="py-2">
                                                    <div class="flex items-center gap-2">
                                                        @if($backup->status === 'done' && !empty($backup->local_paths))
                                                            @foreach($backup->local_paths as $fmt => $path)
                                                                @if(is_file($path))
                                                                    <a href="{{ route('admin.technical.backups.download', [$backup, 'fmt' => $fmt]) }}"
                                                                       class="text-dc-primary hover:underline text-xs">↓{{ $fmt }}</a>
                                                                @endif
                                                            @endforeach
                                                        @endif
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
</x-app-layout>
