<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Actualizar contraseña') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Introduce la nueva contraseña') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div class="relative mt-4">
            <x-input-label for="update_password_current_password" :value="__('Contraseña actual')" />
            <x-text-input id="update_password_current_password" name="current_password" type="password" class="mt-1 block w-full" autocomplete="current-password" />
            <!-- Ojo -->
            <button
                type="button"
                onclick="toggleCurrentPassword()"
                class="absolute right-3 top-1/2  text-gray-500 hover:text-gray-700"
            >
                <i id="eyeClosed3" class="fa-solid fa-eye-slash"></i>
                <i id="eyeOpen3" class="fa-solid fa-eye hidden"></i>
            </button>
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div class="relative mt-4">
            <x-input-label for="update_password_password" :value="__('Nueva contraseña')" />
            <x-text-input id="update_password_password" name="password" type="password" class="mt-1 block w-full" autocomplete="new-password" />
            <!-- Ojo -->
            <button
                type="button"
                onclick="togglePassword()"
                class="absolute right-3 top-1/2  text-gray-500 hover:text-gray-700"
            >
                <i id="eyeClosed" class="fa-solid fa-eye-slash"></i>
                <i id="eyeOpen" class="fa-solid fa-eye hidden"></i>
            </button>
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div class="relative mt-4">
            <x-input-label for="update_password_password_confirmation" :value="__('Confirmar contraseña')" />
            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" autocomplete="new-password" />
            <!-- Ojo -->
            <button
                type="button"
                onclick="toggleConfirmPassword()"
                class="absolute right-3 top-1/2  text-gray-500 hover:text-gray-700"
            >
                <i id="eyeClosed2" class="fa-solid fa-eye-slash"></i>
                <i id="eyeOpen2" class="fa-solid fa-eye hidden"></i>
            </button>
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
            
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Guardar') }}</x-primary-button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Guardada.') }}</p>
            @endif
        </div>
    </form>
</section>


<script>
    function toggleCurrentPassword() {
        const input = document.getElementById('update_password_current_password');
        const eyeOpen = document.getElementById('eyeOpen3');
        const eyeClosed = document.getElementById('eyeClosed3');

        if (input.type === 'password') {
            input.type = 'text';
            eyeOpen.classList.remove('hidden');
            eyeClosed.classList.add('hidden');
        } else {
            input.type = 'password';
            eyeOpen.classList.add('hidden');
            eyeClosed.classList.remove('hidden');
        }
    }

    function togglePassword() {
        const input = document.getElementById('update_password_password');
        const eyeOpen = document.getElementById('eyeOpen');
        const eyeClosed = document.getElementById('eyeClosed');

        if (input.type === 'password') {
            input.type = 'text';
            eyeOpen.classList.remove('hidden');
            eyeClosed.classList.add('hidden');
        } else {
            input.type = 'password';
            eyeOpen.classList.add('hidden');
            eyeClosed.classList.remove('hidden');
        }
    }

    function toggleConfirmPassword() {
        const input = document.getElementById('update_password_password_confirmation');
        const eyeOpen = document.getElementById('eyeOpen2');
        const eyeClosed = document.getElementById('eyeClosed2');

        if (input.type === 'password') {
            input.type = 'text';
            eyeOpen.classList.remove('hidden');
            eyeClosed.classList.add('hidden');
        } else {
            input.type = 'password';
            eyeOpen.classList.add('hidden');
            eyeClosed.classList.remove('hidden');
        }
    }
</script>