<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Nombre de usuario') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Actualizar el nombre de usuario") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')
        @if($errors->any())
    {{ implode('', $errors->all('<div>:message</div>')) }}
@endif
        <div>
            <x-input-label for="username" :value="__('Nuevo nombre')" />
            <x-text-input id="name" name="username" type="text" class="mt-1 block w-full" :value="$user->username" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Guardar') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Guardado') }}</p>
            @endif
        </div>
    </form>
</section>
