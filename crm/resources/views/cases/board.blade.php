<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-ys-l font-semibold text-dc leading-tight">Канбан</h2>
            <div class="flex gap-2">
                <a href="{{ route('cases.index') }}"
                   class="dc-btn inline-flex items-center px-[14px] h-9 text-ys-s rounded-2xs font-medium dc-transition bg-transparent text-dc shadow-[inset_0_0_0_1px_var(--color-border)] hover:shadow-[inset_0_0_0_1px_var(--color-border-hover)]">
                    Список
                </a>
                <a href="{{ route('cases.create') }}"
                   class="dc-btn inline-flex items-center px-[14px] h-9 text-ys-s rounded-2xs font-medium dc-transition bg-dc-blue-100 text-white hover:bg-dc-blue-200">
                    + Кейс
                </a>
            </div>
        </div>
    </x-slot>

    {{-- Toast notification --}}
    <div id="toast"
         class="hidden fixed top-4 right-4 z-50 px-4 py-3 rounded-lg shadow-lg text-sm font-medium transition-all"
         role="alert" aria-live="polite"></div>

    <div class="py-6">
        <div class="max-w-full mx-auto px-4">
            <div class="flex gap-4 overflow-x-auto pb-4" id="kanban-board" style="min-height: 70vh;">
                @foreach($statuses as $status)
                    <div class="flex flex-col min-w-[280px] max-w-[280px] rounded-lg flex-shrink-0 kanban-col border"
                         style="background-color:var(--color-surface-2);border-color:var(--color-border)"
                         data-status-id="{{ $status->id }}"
                         data-sort-order="{{ $status->sort_order }}"
                         ondragover="event.preventDefault(); this.classList.add('ring-2','ring-indigo-400');"
                         ondragleave="this.classList.remove('ring-2','ring-indigo-400');"
                         ondrop="handleDrop(event, {{ $status->id }})">

                        {{-- Sticky column header --}}
                        <div class="sticky top-0 z-10 px-3 py-2.5 rounded-t-lg border-b"
                             style="background-color:var(--color-surface-2);border-color:var(--color-border)">
                            <div class="font-semibold text-ys-s text-dc flex items-center justify-between">
                                <span>{{ $status->sort_order }}. {{ $status->name }}</span>
                                <span class="text-ys-xs text-dc-secondary font-normal ml-1 col-count" data-status-id="{{ $status->id }}">
                                    ({{ $cases->get($status->id, collect())->count() }})
                                </span>
                            </div>
                        </div>

                        {{-- Cards container --}}
                        <div class="flex-1 overflow-y-auto p-3 space-y-2 kanban-cards" id="col-{{ $status->id }}">
                            @php($list = $cases->get($status->id, collect()))
                            @forelse($list as $c)
                                <div class="rounded-md p-3 shadow-sm cursor-grab active:cursor-grabbing kanban-card dc-transition hover:shadow-card border"
                                     style="background-color:var(--color-surface);border-color:var(--color-border)"
                                     draggable="true"
                                     data-case-id="{{ $c->id }}"
                                     data-pipeline-status-id="{{ $c->pipeline_status_id }}"
                                     ondragstart="handleDragStart(event)"
                                     ondragend="handleDragEnd(event)">

                                    {{-- Title --}}
                                    <div class="text-ys-s font-semibold text-dc">
                                        #{{ $c->id }} {{ $c->title ?: 'Без названия' }}
                                    </div>

                                    {{-- Patient --}}
                                    <div class="text-ys-xs text-dc-secondary mt-1">
                                        {{ $c->patient?->full_name }}
                                    </div>

                                    {{-- Service status badge (overlay) --}}
                                    <div class="mt-2" id="svc-badge-{{ $c->id }}">
                                        @if($c->serviceStatus)
                                            <span class="inline-block px-2 py-0.5 text-ys-xs rounded-full bg-dc-orange-30 text-dc-orange-100 font-medium">
                                                ⏸ {{ $c->serviceStatus->name }}
                                            </span>
                                        @endif
                                    </div>

                                    {{-- Service status dropdown --}}
                                    <div class="mt-2">
                                        <select class="text-ys-xs border rounded px-1 py-0.5 w-full bg-surface dc-transition focus:outline-none service-status-select"
                                                style="border-color:var(--color-border);color:var(--color-text)"
                                                data-case-id="{{ $c->id }}"
                                                onchange="updateServiceStatus(this)">
                                            <option value="">— Без паузы —</option>
                                            @foreach($serviceStatuses as $ss)
                                                <option value="{{ $ss->id }}"
                                                        {{ $c->service_status_id == $ss->id ? 'selected' : '' }}>
                                                    {{ $ss->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- Priority & date --}}
                                    <div class="text-ys-xs text-dc-secondary mt-2 flex justify-between">
                                        <span>Приоритет: {{ $c->priority }}</span>
                                        <span>{{ $c->updated_at?->format('d.m H:i') }}</span>
                                    </div>
                                </div>
                            @empty
                                <div class="flex flex-col items-center justify-center py-8 text-dc-disabled kanban-empty">
                                    <svg class="h-8 w-8 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                    <span class="text-xs italic">Нет кейсов</span>
                                </div>
                            @endforelse

                            {{-- Drop zone placeholder (shown on drag-over when col is empty) --}}
                            <div class="kanban-drop-placeholder hidden h-16 rounded border-2 border-dashed border-indigo-300 dark:border-indigo-600 bg-indigo-50 dark:bg-indigo-900/20"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <script>
    const CSRF = document.querySelector('meta[name="csrf-token"]')?.content
                 || '{{ csrf_token() }}';

    let draggedCard = null;
    let dragSourceColId = null;

    function handleDragStart(e) {
        draggedCard = e.currentTarget;
        dragSourceColId = draggedCard.closest('.kanban-col').dataset.statusId;
        e.dataTransfer.effectAllowed = 'move';
        e.dataTransfer.setData('text/plain', draggedCard.dataset.caseId);
        setTimeout(() => {
            draggedCard.classList.add('opacity-40', 'ring-2', 'ring-indigo-400');
        }, 0);
        // Show placeholders in all other columns
        document.querySelectorAll('.kanban-col').forEach(col => {
            if (col.dataset.statusId !== dragSourceColId) {
                const ph = col.querySelector('.kanban-drop-placeholder');
                if (ph) ph.classList.remove('hidden');
            }
        });
    }

    function handleDragEnd(e) {
        if (draggedCard) {
            draggedCard.classList.remove('opacity-40', 'ring-2', 'ring-indigo-400');
        }
        document.querySelectorAll('.kanban-col').forEach(col => {
            col.classList.remove('ring-2', 'ring-indigo-400');
            const ph = col.querySelector('.kanban-drop-placeholder');
            if (ph) ph.classList.add('hidden');
        });
    }

    function handleDrop(e, targetStatusId) {
        e.preventDefault();
        const col = e.currentTarget;
        col.classList.remove('ring-2', 'ring-indigo-400');
        const ph = col.querySelector('.kanban-drop-placeholder');
        if (ph) ph.classList.add('hidden');

        if (!draggedCard) return;

        const caseId = draggedCard.dataset.caseId;
        const sourceStatusId = dragSourceColId;

        if (String(targetStatusId) === String(sourceStatusId)) return;

        // Optimistic UI: move the card immediately
        const targetList = document.getElementById('col-' + targetStatusId);
        const emptyEl = targetList.querySelector('.kanban-empty');
        if (emptyEl) emptyEl.classList.add('hidden');
        targetList.insertBefore(draggedCard, ph);
        draggedCard.dataset.pipelineStatusId = targetStatusId;

        // Update counter badges
        updateColCount(sourceStatusId);
        updateColCount(targetStatusId);

        // Show empty placeholder in source col if needed
        const sourceList = document.getElementById('col-' + sourceStatusId);
        if (sourceList && sourceList.querySelectorAll('.kanban-card').length === 0) {
            const srcEmpty = sourceList.querySelector('.kanban-empty');
            if (srcEmpty) srcEmpty.classList.remove('hidden');
        }

        // Call server
        fetch(`/cases/${caseId}/pipeline-status`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ pipeline_status_id: targetStatusId }),
        })
        .then(async res => {
            if (!res.ok) {
                const body = await res.json().catch(() => ({}));
                throw new Error(body.message || `HTTP ${res.status}`);
            }
            return res.json();
        })
        .then(() => {
            showToast('Статус обновлён', 'success');
        })
        .catch(err => {
            showToast('Ошибка: ' + err.message, 'error');
            // Revert: move card back
            if (sourceList) {
                const srcPh = sourceList.querySelector('.kanban-drop-placeholder');
                sourceList.insertBefore(draggedCard, srcPh);
                draggedCard.dataset.pipelineStatusId = sourceStatusId;
                updateColCount(sourceStatusId);
                updateColCount(targetStatusId);
                // Re-hide empty in target
                if (targetList && targetList.querySelectorAll('.kanban-card').length === 0) {
                    const tgtEmpty = targetList.querySelector('.kanban-empty');
                    if (tgtEmpty) tgtEmpty.classList.remove('hidden');
                }
                // Hide empty in source
                const srcEmpty2 = sourceList.querySelector('.kanban-empty');
                if (srcEmpty2 && sourceList.querySelectorAll('.kanban-card').length > 0) {
                    srcEmpty2.classList.add('hidden');
                }
            }
        });
    }

    function updateServiceStatus(select) {
        const caseId = select.dataset.caseId;
        const serviceStatusId = select.value || null;
        const badgeEl = document.getElementById('svc-badge-' + caseId);

        fetch(`/cases/${caseId}/service-status`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ service_status_id: serviceStatusId }),
        })
        .then(async res => {
            if (!res.ok) {
                const body = await res.json().catch(() => ({}));
                throw new Error(body.message || `HTTP ${res.status}`);
            }
            return res.json();
        })
        .then(data => {
            if (data.service_status_name) {
                badgeEl.innerHTML = `<span class="inline-block px-2 py-0.5 text-xs rounded-full bg-yellow-100 dark:bg-yellow-900/50 text-yellow-800 dark:text-yellow-300 font-medium">⏸ ${data.service_status_name}</span>`;
            } else {
                badgeEl.innerHTML = '';
            }
            showToast('Статус паузы обновлён', 'success');
        })
        .catch(err => {
            showToast('Ошибка: ' + err.message, 'error');
        });
    }

    function updateColCount(statusId) {
        const badge = document.querySelector(`.col-count[data-status-id="${statusId}"]`);
        if (!badge) return;
        const col = document.getElementById('col-' + statusId);
        const count = col ? col.querySelectorAll('.kanban-card').length : 0;
        badge.textContent = `(${count})`;
    }

    function showToast(msg, type) {
        const el = document.getElementById('toast');
        el.textContent = msg;
        const base = 'fixed top-4 right-4 z-50 px-4 py-3 rounded-lg shadow-lg text-sm font-medium transition-all ';
        el.className = base + (type === 'error'
            ? 'bg-red-100 dark:bg-red-900/80 text-red-800 dark:text-red-200 border border-red-300 dark:border-red-700'
            : 'bg-green-100 dark:bg-green-900/80 text-green-800 dark:text-green-200 border border-green-300 dark:border-green-700');
        el.classList.remove('hidden');
        clearTimeout(el._hideTimer);
        el._hideTimer = setTimeout(() => el.classList.add('hidden'), 3000);
    }
    </script>
</x-app-layout>