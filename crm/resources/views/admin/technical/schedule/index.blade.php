<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-dc leading-tight">
            Технический раздел / Расписание
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if(session('success'))
            <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-800 rounded-md">
                {{ session('success') }}
            </div>
        @endif

        <div class="flex gap-6">
            {{-- Sidebar --}}
            <div class="w-48 shrink-0">
                <x-dc.card class="p-3">
                    <nav class="space-y-1">
                        <a href="{{ route('admin.technical.backups.index') }}"
                           class="flex items-center gap-2 px-3 py-2 rounded text-sm font-medium text-dc hover:bg-surface-hover dc-transition">
                            💾 Резервные копии
                        </a>
                        <a href="{{ route('admin.technical.schedule.index') }}"
                           class="flex items-center gap-2 px-3 py-2 rounded text-sm font-medium bg-surface-hover text-dc-primary">
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

            {{-- Form --}}
            <div class="flex-1">
                <x-dc.card>
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-dc mb-6">Настройки расписания резервных копий</h3>
                        <form method="POST" action="{{ route('admin.technical.schedule.update') }}"
                              x-data="{ type: '{{ $settings->backup_type }}' }">
                            @csrf
                            @method('PATCH')
                            <div class="space-y-5">
                                {{-- Enabled --}}
                                <div>
                                    <label class="flex items-center gap-3 cursor-pointer">
                                        <input type="hidden" name="enabled" value="0">
                                        <input type="checkbox" name="enabled" value="1"
                                               {{ $settings->enabled ? 'checked' : '' }}
                                               class="text-dc-primary w-4 h-4">
                                        <span class="text-sm font-medium text-dc">Включить автоматическое резервное копирование</span>
                                    </label>
                                </div>

                                {{-- Time --}}
                                <div>
                                    <label class="block text-sm font-medium text-dc-secondary mb-2">Время запуска (HH:MM)</label>
                                    <input type="text" name="schedule_time" value="{{ $settings->schedule_time }}"
                                           pattern="\d{2}:\d{2}" placeholder="03:00"
                                           class="bg-surface border border-dc rounded text-dc text-sm px-3 py-2 w-32">
                                </div>

                                {{-- Backup Type --}}
                                <div>
                                    <label class="block text-sm font-medium text-dc-secondary mb-2">Тип резервной копии</label>
                                    <div class="flex gap-6">
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input type="radio" name="backup_type" value="db" x-model="type"
                                                   class="text-dc-primary">
                                            <span class="text-sm text-dc">Только БД</span>
                                        </label>
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input type="radio" name="backup_type" value="files" x-model="type"
                                                   class="text-dc-primary">
                                            <span class="text-sm text-dc">Только файлы</span>
                                        </label>
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input type="radio" name="backup_type" value="full" x-model="type"
                                                   class="text-dc-primary">
                                            <span class="text-sm text-dc">БД + файлы</span>
                                        </label>
                                    </div>
                                </div>

                                {{-- File Preset --}}
                                <div x-show="type === 'files' || type === 'full'">
                                    <label class="block text-sm font-medium text-dc-secondary mb-2">Файловый пресет</label>
                                    <select name="file_preset"
                                            class="bg-surface border border-dc rounded text-dc text-sm px-3 py-2">
                                        <option value="project" {{ $settings->file_preset === 'project' ? 'selected' : '' }}>Проект</option>
                                        <option value="storage_app" {{ $settings->file_preset === 'storage_app' ? 'selected' : '' }}>Storage/app</option>
                                    </select>
                                </div>

                                {{-- Formats --}}
                                <div>
                                    <label class="block text-sm font-medium text-dc-secondary mb-2">Форматы</label>
                                    <div class="flex gap-6">
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input type="checkbox" name="formats[]" value="zip"
                                                   {{ in_array('zip', $settings->formats ?? []) ? 'checked' : '' }}
                                                   class="text-dc-primary">
                                            <span class="text-sm text-dc">.zip</span>
                                        </label>
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input type="checkbox" name="formats[]" value="tar_gz"
                                                   {{ in_array('tar_gz', $settings->formats ?? []) ? 'checked' : '' }}
                                                   class="text-dc-primary">
                                            <span class="text-sm text-dc">.tar.gz</span>
                                        </label>
                                    </div>
                                </div>

                                {{-- Yandex --}}
                                <div>
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="hidden" name="upload_yandex" value="0">
                                        <input type="checkbox" name="upload_yandex" value="1"
                                               {{ $settings->upload_yandex ? 'checked' : '' }}
                                               class="text-dc-primary">
                                        <span class="text-sm text-dc">Загружать на Яндекс.Диск</span>
                                    </label>
                                </div>

                                {{-- Note --}}
                                <p class="text-xs text-dc-secondary">
                                    Хранится последних 20 резервных копий (более старые удаляются автоматически).
                                </p>

                                <div>
                                    <x-dc.button type="submit" variant="action" size="m">
                                        Сохранить настройки
                                    </x-dc.button>
                                </div>
                            </div>
                        </form>
                    </div>
                </x-dc.card>
            </div>
        </div>
    </div>
</x-app-layout>
