@props([
    'tabs' => [],
])

<div x-data="{ active: 0 }" {{ $attributes }}>
    <!-- Tab bar -->
    <div class="relative flex gap-0" style="box-shadow:inset 0 -1px 0 var(--color-border)">
        @foreach ($tabs as $i => $tab)
            <button
                type="button"
                @click="active = {{ $i }}"
                :class="active === {{ $i }} ? 'text-dc font-medium' : 'text-dc-secondary'"
                class="px-4 pb-3 pt-2 text-ys-s dc-transition focus:outline-none relative"
            >
                {{ $tab }}
                <span
                    x-show="active === {{ $i }}"
                    class="absolute bottom-0 left-0 right-0 h-0.5 rounded-t bg-dc-blue-100"
                ></span>
            </button>
        @endforeach
    </div>

    <!-- Panels — access named slots via $slot->{'panel0'}, $slot->{'panel1'}, etc. -->
    @foreach ($tabs as $i => $tab)
        @php $panelName = 'panel' . $i; @endphp
        <div x-show="active === {{ $i }}" class="pt-4">
            @if (isset($$panelName))
                {{ $$panelName }}
            @endif
        </div>
    @endforeach
</div>
