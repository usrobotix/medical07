<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Канбан</h2>
            <div class="flex gap-2">
                <a href="{{ route('cases.index') }}" class="px-3 py-2 border rounded">Список</a>
                <a href="{{ route('cases.create') }}" class="px-3 py-2 bg-blue-600 text-white rounded">+ Кейс</a>
            </div>
        </div>
    </x-slot>

    {{-- Toast notification --}}
    <div id="toast"
         class="hidden fixed top-4 right-4 z-50 px-4 py-3 rounded shadow-lg text-sm font-medium transition-all"
         role="alert"></div>

    <div class="py-6">
        <div class="max-w-full mx-auto px-4">
            <div class="flex gap-4 overflow-x-auto pb-4" id="kanban-board">
                @foreach($statuses as $status)
                    <div class="min-w-[280px] max-w-[280px] bg-gray-50 border border-gray-200 rounded-lg p-3 flex-shrink-0 kanban-col"
                         data-status-id="{{ $status->id }}"
                         data-sort-order="{{ $status->sort_order }}"
                         ondragover="event.preventDefault(); this.classList.add('ring-2','ring-blue-400');"
                         ondragleave="this.classList.remove('ring-2','ring-blue-400');"
                         ondrop="handleDrop(event, {{ $status->id }})">

                        <div class="font-semibold text-sm mb-3 text-gray-700 flex items-center justify-between">
                            <span>{{ $status->sort_order }}. {{ $status->name }}</span>
                            <span class="text-xs text-gray-400 font-normal ml-1">
                                ({{ $cases->get($status->id, collect())->count() }})
                            </span>
                        </div>

                        <div class="space-y-2 kanban-cards" id="col-{{ $status->id }}">
                            @php($list = $cases->get($status->id, collect()))
                            @forelse($list as $c)
                                <div class="bg-white border border-gray-200 rounded p-3 shadow-sm cursor-grab active:cursor-grabbing kanban-card"
                                     draggable="true"
                                     data-case-id="{{ $c->id }}"
                                     data-pipeline-status-id="{{ $c->pipeline_status_id }}"
                                     ondragstart="handleDragStart(event)"
                                     ondragend="handleDragEnd(event)">

                                    {{-- Title --}}
                                    <div class="text-sm font-semibold text-gray-800">
                                        #{{ $c->id }} {{ $c->title ?: 'Без названия' }}
                                    </div>

                                    {{-- Patient --}}
                                    <div class="text-xs text-gray-500 mt-1">
                                        {{ $c->patient?->full_name }}
                                    </div>

                                    {{-- Service status badge (overlay) --}}
                                    @if($c->serviceStatus)
                                        <div class="mt-2">
                                            <span class="inline-block px-2 py-0.5 text-xs rounded-full bg-yellow-100 text-yellow-800 font-medium service-badge"
                                                  id="svc-badge-{{ $c->id }}">
                                                ⏸ {{ $c->serviceStatus->name }}
                                            </span>
                                        </div>
                                    @else
                                        <div class="mt-2" id="svc-badge-{{ $c->id }}"></div>
                                    @endif

                                    {{-- Service status dropdown --}}
                                    <div class="mt-2">
                                        <select class="text-xs border border-gray-200 rounded px-1 py-0.5 w-full bg-white service-status-select"
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
                                    <div class="text-xs text-gray-400 mt-2 flex justify-between">
                                        <span>P{{ $c->priority }}</span>
                                        <span>{{ $c->updated_at?->format('d.m H:i') }}</span>
                                    </div>
                                </div>
                            @empty
                                <div class="text-xs text-gray-400 italic py-2 text-center">Нет кейсов</div>
                            @endforelse
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
        setTimeout(() => draggedCard.classList.add('opacity-40'), 0);
    }

    function handleDragEnd(e) {
        draggedCard?.classList.remove('opacity-40');
        document.querySelectorAll('.kanban-col').forEach(col => {
            col.classList.remove('ring-2', 'ring-blue-400');
        });
    }

    function handleDrop(e, targetStatusId) {
        e.preventDefault();
        const col = e.currentTarget;
        col.classList.remove('ring-2', 'ring-blue-400');

        if (!draggedCard) return;

        const caseId = draggedCard.dataset.caseId;
        const sourceStatusId = dragSourceColId;

        if (String(targetStatusId) === String(sourceStatusId)) return;

        // Optimistic UI: move the card immediately
        const targetList = document.getElementById('col-' + targetStatusId);
        targetList.appendChild(draggedCard);
        draggedCard.dataset.pipelineStatusId = targetStatusId;

        // Update counter badges
        updateColCount(sourceStatusId);
        updateColCount(targetStatusId);

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
            const sourceList = document.getElementById('col-' + sourceStatusId);
            if (sourceList) {
                sourceList.appendChild(draggedCard);
                draggedCard.dataset.pipelineStatusId = sourceStatusId;
                updateColCount(sourceStatusId);
                updateColCount(targetStatusId);
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
                badgeEl.innerHTML = `<span class="inline-block px-2 py-0.5 text-xs rounded-full bg-yellow-100 text-yellow-800 font-medium">⏸ ${data.service_status_name}</span>`;
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
        const col = document.querySelector(`.kanban-col[data-status-id="${statusId}"]`);
        if (!col) return;
        const count = col.querySelectorAll('.kanban-card').length;
        const badge = col.querySelector('.text-gray-400.font-normal');
        if (badge) badge.textContent = `(${count})`;
    }

    function showToast(msg, type) {
        const el = document.getElementById('toast');
        el.textContent = msg;
        el.className = 'fixed top-4 right-4 z-50 px-4 py-3 rounded shadow-lg text-sm font-medium transition-all '
            + (type === 'error' ? 'bg-red-100 text-red-800 border border-red-300'
                                : 'bg-green-100 text-green-800 border border-green-300');
        el.classList.remove('hidden');
        clearTimeout(el._hideTimer);
        el._hideTimer = setTimeout(() => el.classList.add('hidden'), 3000);
    }
    </script>
</x-app-layout>