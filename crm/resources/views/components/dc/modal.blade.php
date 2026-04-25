@props([
    'title' => '',
    'id'    => 'dc-modal',
])

<div
    x-data="{ open: false }"
    x-on:open-modal.window="if ($event.detail === '{{ $id }}') open = true"
    x-on:close-modal.window="if ($event.detail === '{{ $id }}') open = false"
    x-show="open"
    class="fixed inset-0 z-30 flex items-center justify-center"
    style="background:rgba(0,0,0,.35)"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    x-cloak
>
    <div
        class="w-full max-w-xl rounded-md p-8"
        style="background-color:var(--color-surface);box-shadow:var(--shadow-dialog)"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-on:click.stop
    >
        @if ($title)
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-ys-l font-semibold text-dc">{{ $title }}</h3>
                <button
                    x-on:click="open = false"
                    class="text-dc-gray-500 hover:text-dc dc-transition p-1 rounded-full"
                    aria-label="Закрыть"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        @endif

        {{ $slot }}

        @isset($footer)
            <div class="flex items-center justify-end gap-3 mt-6 pt-4" style="border-top:1px solid var(--color-border)">
                {{ $footer }}
            </div>
        @endisset
    </div>
</div>
