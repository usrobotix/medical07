<x-guest-layout>
    <div class="mb-4 text-ys-s text-dc-secondary">
        {{ __('Спасибо за регистрацию! Пожалуйста, подтвердите ваш email, перейдя по ссылке из письма. Если вы не получили письмо — мы отправим его снова.') }}
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 text-ys-s font-medium text-dc-green-100 p-3 bg-dc-green-20 rounded-2xs">
            {{ __('Новая ссылка для подтверждения отправлена на ваш email.') }}
        </div>
    @endif

    <div class="mt-4 flex items-center justify-between">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <x-primary-button>
                {{ __('Отправить повторно') }}
            </x-primary-button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-ys-s text-dc-primary hover:text-dc-blue-200 dc-transition underline">
                {{ __('Выйти') }}
            </button>
        </form>
    </div>
</x-guest-layout>
