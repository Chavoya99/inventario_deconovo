<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- username -->
        <div>
            <x-input-label for="username" :value="__('Nuevo usuario')" />
            <x-text-input id="username" class="block mt-1 w-full" type="text" name="username" :value="old('username')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('username')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="relative mt-4">
            <x-input-label for="password" :value="__('Contraseña')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />
            <!-- Ojo -->
            <button
                type="button"
                onclick="togglePassword()"
                class="absolute right-3 top-1/2  text-gray-500 hover:text-gray-700"
            >
                <i id="eyeClosed" class="fa-solid fa-eye-slash"></i>
                <i id="eyeOpen" class="fa-solid fa-eye hidden"></i>
            </button>

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>
        

        <!-- Confirm Password -->
        <div class="relative mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />
            
            <!-- Ojo -->
            <button
                type="button"
                onclick="toggleConfirmPassword()"
                class="absolute right-3 top-1/2  text-gray-500 hover:text-gray-700"
            >
                <i id="eyeClosed2" class="fa-solid fa-eye-slash"></i>
                <i id="eyeOpen2" class="fa-solid fa-eye hidden"></i>
            </button>

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a href="{{ route('dashboard') }}" class="px-4 py-2 rounded-md border border-gray-300 text-gray-700 hover:bg-gray-100">
                Cancelar
            </a>
            <button class="ml-5 px-4 py-2 rounded-md border bg-gray-800 border-transparent text-white hover:bg-gray-700">
                {{ __('Confirmar') }}
            </button>
        </div>

    </form>
</x-guest-layout>

<script>
    function togglePassword() {
        const input = document.getElementById('password');
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
        const input = document.getElementById('password_confirmation');
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
