<x-app-layout>
    

<div class="bg-neutral-primary-soft shadow-xs rounded-base border border-default">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Lista de proveedores') }}
        </h2>
    </x-slot>
    <div class="px-4 md:px-8 lg:px-12 py-4 md:px-8 lg:px-20">
        @if (auth()->user()->isAdmin())
            <form action="{{route('guardar_proveedor')}}" method="POST">
                @csrf
                <label class="block text-sm font-medium text-gray-700 mb-1">
                Nombre del proveedor
                </label>
                <input type="text" autocomplete=off name="nombre" class="rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500" required>
                <x-input-error :messages="$errors->get('nombre')" class="mt-2" />

                <button class="py-3 px-4 inline-flex items-center gap-x-2 text-sm font-medium 
                rounded-lg border border-transparent bg-teal-500 text-white hover:bg-teal-600 focus:outline-hidden 
                focus:bg-teal-600 cursor-pointer">Agregar Proveedor</button>
                <br><br>
            </form>
            
        @endif
        

        <div class="overflow-x-auto bg-white rounded-lg shadow border border-gray-200">
            <table class="w-full text-sm text-left text-gray-700">
                @if ($proveedores->isEmpty())
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-center text-gray-500">
                                No hay proveedores registrados
                            </td>
                        </tr>
                @else
                    <thead class="bg-sky-400 text-black border-b">
                        <tr>
                            <th class="px-6 py-3 font-medium">Proveedor</th>
                            <th class="px-6 py-3 font-medium">Fecha de registro</th>
                            <th class="px-6 py-3 text-right font-medium">Acciones</th>
                        
                        </tr>
                    </thead>
                    
                    <tbody>
                    
                    
                    @foreach ($proveedores as $proveedor)
                        <tr
                            class="border-b hover:bg-gray-50"
                            x-data="{ editing: false, name: '{{ $proveedor->nombre }}' }"
                        >
                            <!-- PROVEEDOR -->
                            <td class="px-6 py-4">
                                <!-- Texto -->
                                <span x-show="!editing" class="font-medium text-gray-900">
                                    {{ $proveedor->nombre }}
                                </span>

                                <!-- Input -->
                                <input
                                    x-show="editing"
                                    x-model="name"
                                    type="text"
                                    class="border rounded px-2 py-1 text-sm w-full"
                                >
                            </td>

                            <!-- FECHA -->
                            <td class="px-6 py-4">
                                {{ $proveedor->created_at->format('d/m/Y') }}
                            </td>

                            <!-- ACCIONES -->
                            <td class="px-6 py-4">
                                @if(auth()->user()->isAdmin())
                                    <div class="flex justify-end items-center gap-4 text-sm">

                                        <!-- Editar -->
                                        <button
                                            x-show="!editing"
                                            @click="editing = true"
                                            class="text-blue-600 hover:underline"
                                        >
                                            Editar
                                        </button>

                                        <!-- Guardar / Cancelar -->
                                        <form
                                            x-show="editing"
                                            method="POST"
                                            action="{{ route('editar_proveedor', $proveedor) }}"
                                            class="flex gap-3"
                                        >
                                            @csrf
                                            @method('PUT')

                                            <input type="hidden" name="nombre" :value="name">

                                            <button class="text-green-600 hover:underline">
                                                Guardar
                                            </button>

                                            <button
                                                type="button"
                                                @click="editing = false"
                                                class="text-gray-600 hover:underline"
                                            >
                                                Cancelar
                                            </button>
                                        </form>

                                        <!-- Ver productos -->
                                        <a
                                            href="{{ route('lista_productos', ['proveedor' => $proveedor->id])}}"
                                            class="text-blue-600 hover:underline"
                                        >
                                            Ver productos
                                        </a>

                                        <!-- Eliminar -->
                                        <form
                                            action="{{ route('eliminar_proveedor', $proveedor) }}"
                                            method="POST"
                                            onsubmit="return confirm('Se eliminará el proveedor {{strtoupper($proveedor->nombre)}}, incluyendo todos los productos relacionados. ¿Continuar?')"
                                        >
                                            @csrf
                                            @method('DELETE')
                                            <button class="text-red-600 hover:underline">
                                                Eliminar
                                            </button>
                                        </form>

                                    </div>
                                @endif
                            </td>
                        </tr>

                    @endforeach
                @endif

                    
                    
                </tbody>
            </table>
            
        </div>
        {{$proveedores->links('components.pagination')}}
    </div>
</div>

</x-app-layout>

