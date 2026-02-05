<x-app-layout>
    

<div class="bg-neutral-primary-soft shadow-xs rounded-base border border-default">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Revisar reporte') }}
        </h2>
    </x-slot>
    <div class="px-4 md:px-8 lg:px-12 py-4 md:px-8 lg:px-20">

        <div class="overflow-x-auto bg-white rounded-lg shadow border border-gray-200">
            <table class="w-full text-sm text-left text-gray-700">
                    <thead class="bg-sky-400 text-black border-b">
                        <tr>
                            <th class="px-6 py-3 font-medium">Producto</th>
                            <th class="px-6 py-3 font-medium">Proveedor</th>
                            <th class="px-6 py-3 font-medium">Unidad</th>
                            <th class="px-6 py-3 font-medium">Máximo</th>
                            <th class="px-6 py-3 font-medium">Existencia</th>
                            <th class="px-6 py-3 font-medium">Pedir</th>
                            <th class="px-6 py-3 font-medium">Precio venta</th>
                            <th class="px-6 py-3 font-medium">Precio proveedor</th>
                            <th class="px-6 py-3 text-right font-medium">Acciones</th>
                        
                        </tr>
                    </thead>
                    
                    <tbody>
                    
                    @foreach ($productos as $producto)
                        <tr
                            class="border-b hover:bg-gray-50"
                        >
                            <td id=nombre class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                {{$producto->producto}}
                            </td>

                            <td class="px-6 py-4">
                                {{$producto->proveedor->nombre}}
                            </td>
                            <td class="px-6 py-4">
                                {{$producto->unidad}}
                            </td>
                            <td class="px-6 py-4">
                                {{$producto->maximo}}
                            </td>
                            <td class="px-6 py-4">
                                {{$producto->pivot->existencia}}
                            </td>
                            <td class="px-6 py-4">
                                {{$producto->maximo - $producto->pivot->existencia}}
                            </td>

                            <td class="px-6 py-4">
                                {{$producto->precio_venta}}
                            </td>

                            <td class="px-6 py-4">
                                {{$producto->precio_proveedor}}
                            </td>
                            


                            <!-- ACCIONES -->
                            <td class="px-6 py-4">
                                @if(auth()->user()->isAdmin())
                                    <div class="flex justify-end items-center gap-4 text-sm">

                                        <!-- Ver productos -->
                                        <a
                                            href="{{-- --}}"
                                            class="text-blue-600 hover:underline"
                                        >
                                            Incluir
                                        </a>

                                        <!-- Eliminar -->
                                        <form
                                            action="{{----}}"
                                            method="POST"
                                            onsubmit="return confirm('Se eliminará el reporte ¿Continuar?')"
                                        >
                                            @csrf
                                            @method('DELETE')
                                            <button class="text-red-600 hover:underline">
                                                Eliminar
                                            </button>
                                        </form>

                                    </div>
                                @elseif(auth()->user()->isEmpleado())
                                    <a
                                        href="{{-- --}}"
                                        class="text-blue-600 hover:underline"
                                    >
                                        Detalles
                                    </a>
                                @endif
                            </td>
                        </tr>

                    @endforeach           
                    
                </tbody>
            </table>
            
        </div>
    </div>
</div>

</x-app-layout>

