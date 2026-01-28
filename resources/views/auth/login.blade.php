<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>

            <x-input-label for="username" :value="__('Usuario')" />
            <x-text-input id="username" class="block mt-1 w-full" type="text" name="username" :value="old('username')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
<div class="relative mt-4">
    <x-input-label for="password" value="Contraseña" />

    <input
        id="password"
        type="password"
        name="password"
        class="block w-full mt-1 rounded-md border-gray-300 focus:border-teal-500 focus:ring-teal-500 pr-12 py-2"
        required
    >

    <!-- Ojo -->
    <button
        type="button"
        onclick="togglePassword()"
        class="absolute right-3 top-1/2  text-gray-500 hover:text-gray-700"
    >
        <i id="eyeClosed" class="fa-solid fa-eye-slash"></i>
        <i id="eyeOpen" class="fa-solid fa-eye hidden"></i>
    </button>
</div>


        {{--<div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

        </div>--}}
        <br>
        <x-primary-button class="primary">
            {{ __('Iniciar sesión') }}
        </x-primary-button>
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
</script>
