<x-app-layout>
    <x-slot name="header">
        <h2 class="text-ys-l font-semibold text-dc leading-tight">
            Профиль
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <x-dc.card padding="lg" shadow="card">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </x-dc.card>

            <x-dc.card padding="lg" shadow="card">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </x-dc.card>

            <x-dc.card padding="lg" shadow="card">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </x-dc.card>
        </div>
    </div>
</x-app-layout>
