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

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex gap-4 overflow-x-auto pb-4">
                @foreach($statuses as $status)
                    <div class="min-w-[320px] bg-gray-50 border rounded p-3">
                        <div class="font-semibold text-sm mb-2">
                            {{ $status->sort_order }} — {{ $status->name }}
                        </div>

                        <div class="space-y-2">
                            @php($list = $cases->get($status->id, collect()))
                            @forelse($list as $c)
                                <div class="bg-white border rounded p-3 shadow-sm">
                                    <div class="text-sm font-semibold">
                                        #{{ $c->id }} {{ $c->title ?: 'Без названия' }}
                                    </div>
                                    <div class="text-xs text-gray-600 mt-1">
                                        {{ $c->patient?->full_name }}
                                    </div>
                                    <div class="text-xs text-gray-500 mt-2">
                                        обновлено: {{ $c->updated_at?->format('Y-m-d H:i') }}
                                    </div>
                                </div>
                            @empty
                                <div class="text-xs text-gray-500">Нет кейсов</div>
                            @endforelse
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>