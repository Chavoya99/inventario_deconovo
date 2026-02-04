@if (Route::is('login'))
    <div class="flex items-center justify-center">
        <img
            src="{{ asset('build/assets/img/logo.png') }}"
            alt="Logo"
            class="block"
            width="30%"
            height="30%"
        >
    </div>
@else
   <div class="w-40 h-40 flex items-center justify-center">
        <img
            src="{{ asset('build/assets/img/logo.png') }}"
            alt="Logo"
            class="max-w-full max-h-full w-auto h-auto block"
        >
    </div> 
@endif



