<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <article class="text-wrap">
                        <p class="xl:text-7xl md:text-4xl sm:text text-heading">¡HOLA {{strtoupper(auth()->user()->username)}} BIENVENIDO!</p>
                    </article>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
