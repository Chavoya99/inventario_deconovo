@if (Route::is('login'))
    <div class="flex items-center justify-center">
        <img
            src="{{ asset('img/logo.png') }}"
            alt="Logo"
            class="block"
            width="300px"
            height="20px"
        >
    </div>
@else
   <div class="w-40 h-40 flex items-center justify-center">
        <img
            src="{{ asset('img/logo.png') }}"
            alt="Logo"
            class="max-w-full max-h-full w-auto h-auto block"
        >
    </div> 
@endif



