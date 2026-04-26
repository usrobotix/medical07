<section>
    <header>
        <h2 class="text-ys-m-s font-semibold text-dc">
            Информация профиля
        </h2>
        <p class="mt-1 text-ys-s text-dc-secondary">
            Обновите имя и адрес электронной почты аккаунта.
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-5">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Имя')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2">
                    <p class="text-ys-s text-dc-secondary">
                        Ваш email не подтверждён.
                        <button form="send-verification"
                                class="text-dc-primary hover:text-dc-blue-200 dc-transition underline text-ys-s">
                            Отправить письмо повторно
                        </button>
                    </p>
                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 text-ys-s font-medium text-dc-green-100">
                            Новая ссылка отправлена на ваш email.
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>Сохранить</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-ys-s text-dc-secondary"
                >Сохранено.</p>
            @endif
        </div>
    </form>
</section>
