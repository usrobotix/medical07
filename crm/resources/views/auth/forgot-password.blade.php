<x-guest-layout>
    <div class="mb-4 text-ys-s text-dc-secondary">
        {{ __('Забыли пароль? Укажите ваш email, и мы вышлем ссылку для сброса пароля.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mt-6">
            <a href="{{ route('login') }}" class="text-ys-s text-dc-primary hover:text-dc-blue-200 dc-transition underline">
                {{ __('Вернуться ко входу') }}
            </a>
            <x-primary-button>
                {{ __('Отправить ссылку') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
