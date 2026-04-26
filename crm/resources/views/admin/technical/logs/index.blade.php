<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-dc leading-tight">
            Технический раздел / Логи
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if(session('success'))
            <div class="mb-4 p-4 bg-dc-green-20 border border-dc-green-100 text-dc-green-100 rounded-2xs text-ys-s">
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
                           class="flex items-center gap-2 px-3 py-2 rounded text-sm font-medium text-dc hover:bg-surface-hover dc-transition">
                            🕐 Расписание
                        </a>
                        <a href="{{ route('admin.technical.audit.index') }}"
                           class="flex items-center gap-2 px-3 py-2 rounded text-sm font-medium text-dc hover:bg-surface-hover dc-transition">
                            📋 Журнал событий
                        </a>
                        <a href="{{ route('admin.technical.logs.index') }}"
                           class="flex items-center gap-2 px-3 py-2 rounded text-sm font-medium bg-surface-hover text-dc-primary">
                            📄 Логи
                        </a>
                    </nav>
                </x-dc.card>
            </div>

            {{-- Main --}}
            <div class="flex-1 space-y-4">
                {{-- Controls --}}
                <x-dc.card>
                    <div class="p-4">
                        <form method="GET" class="flex flex-wrap gap-3 items-end">
                            <div>
                                <label class="block text-xs text-dc-secondary mb-1">Файл лога</label>
                                <select name="file"
                                        class="bg-surface border border-dc rounded text-dc text-sm px-2 py-1">
                                    @foreach($files as $file)
                                        <option value="{{ $file }}" {{ $file === $selectedFile ? 'selected' : '' }}>
                                            {{ $file }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs text-dc-secondary mb-1">Последних строк</label>
                                <input type="number" name="lines" value="{{ request('lines', 500) }}"
                                       min="10" max="5000"
                                       class="bg-surface border border-dc rounded text-dc text-sm px-2 py-1 w-24">
                            </div>
                            <div>
                                <label class="block text-xs text-dc-secondary mb-1">Уровень</label>
                                <select name="level"
                                        class="bg-surface border border-dc rounded text-dc text-sm px-2 py-1">
                                    <option value="">Все</option>
                                    <option value="error" {{ request('level') === 'error' ? 'selected' : '' }}>ERROR</option>
                                    <option value="warning" {{ request('level') === 'warning' ? 'selected' : '' }}>WARNING</option>
                                    <option value="info" {{ request('level') === 'info' ? 'selected' : '' }}>INFO</option>
                                    <option value="debug" {{ request('level') === 'debug' ? 'selected' : '' }}>DEBUG</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs text-dc-secondary mb-1">Поиск</label>
                                <input type="text" name="search" value="{{ request('search') }}"
                                       placeholder="Текст..."
                                       class="bg-surface border border-dc rounded text-dc text-sm px-2 py-1 w-40">
                            </div>
                            <div>
                                <x-dc.button type="submit" variant="normal" size="s">Применить</x-dc.button>
                            </div>
                            @if($selectedFile)
                                <div class="ml-auto flex gap-2">
                                    <a href="{{ route('admin.technical.logs.download', ['file' => $selectedFile]) }}"
                                       class="inline-flex">
                                        <x-dc.button variant="contour" size="s">↓ Скачать</x-dc.button>
                                    </a>
                                    <form method="POST" action="{{ route('admin.technical.logs.clear') }}"
                                          onsubmit="return confirm('Очистить лог {{ $selectedFile }}?')">
                                        @csrf
                                        <input type="hidden" name="file" value="{{ $selectedFile }}">
                                        <x-dc.button type="submit" variant="danger" size="s">Очистить</x-dc.button>
                                    </form>
                                </div>
                            @endif
                        </form>
                    </div>
                </x-dc.card>

                {{-- Log Output --}}
                <x-dc.card>
                    <div class="p-4">
                        <div class="flex justify-between items-center mb-3">
                            <h3 class="text-base font-medium text-dc">
                                {{ $selectedFile ?? 'Нет файлов' }}
                                @if($totalLines)
                                    <span class="text-dc-secondary text-sm font-normal">({{ $totalLines }} строк всего, показано {{ count($lines) }})</span>
                                @endif
                            </h3>
                        </div>
                        <pre class="font-mono text-xs overflow-auto max-h-[600px] bg-gray-950 text-gray-100 p-4 rounded leading-relaxed">@foreach($lines as $line)@php
    $lineClass = '';
    $lUpper = strtoupper($line);
    if (str_contains($lUpper, '.ERROR:') || str_contains($lUpper, '[ERROR]')) $lineClass = 'text-red-400';
    elseif (str_contains($lUpper, '.WARNING:') || str_contains($lUpper, '[WARNING]') || str_contains($lUpper, '.WARN:') || str_contains($lUpper, '[WARN]')) $lineClass = 'text-yellow-300';
    elseif (str_contains($lUpper, '.INFO:') || str_contains($lUpper, '[INFO]')) $lineClass = 'text-blue-300';
    elseif (str_contains($lUpper, '.DEBUG:') || str_contains($lUpper, '[DEBUG]')) $lineClass = 'text-gray-400';
@endphp<span class="{{ $lineClass }}">{{ $line }}</span>
@endforeach</pre>
                    </div>
                </x-dc.card>
            </div>
        </div>
    </div>
</x-app-layout>
