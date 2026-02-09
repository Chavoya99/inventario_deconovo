<x-app-layout>
    

<div class="bg-neutral-primary-soft shadow-xs rounded-base border border-default">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ 'Revisar reporte '. strtoupper($proveedor->nombre)}}
        </h2>
    </x-slot>
    <div class="px-4 md:px-8 lg:px-12 py-4 md:px-8 lg:px-20">
        <x-back-button/>
       <!-- INFO -->
        <div class="text-sm text-gray-700">
            <span class="font-medium">
                Fecha:
            </span>
            {{$reporte->fecha_generada->format('d/m/Y')}}

            <span class="ml-6">
                <span class="font-medium">Estatus:</span>
                {!! $reporte->status() !!}
            </span>
        </div>
        <br>
        <div class="overflow-x-auto bg-white rounded-lg shadow border border-gray-200">
            <table class="border-collapse w-full text-sm text-left text-gray-700">
                    <thead class="bg-sky-400 text-black border-b">
                        <tr>
                            <th class="px-6 py-3 font-medium w-1/3">Producto</th>
                            <th class="px-6 py-3 font-medium">Unidad</th>
                            <th class="px-6 py-3 font-medium">Máximo</th>
                            <th class="px-6 py-3 font-medium">Existencia</th>
                            <th class="px-6 py-3 font-medium">Pedir</th>
                            
                        
                        </tr>
                    </thead>
                    
                    <tbody>
                    
                    @foreach ($productos as $producto)
                        <tr
                            class="border-b hover:bg-gray-50"
                        >
                            <td id=nombre class="px-6 py-4 font-medium text-gray-900">
                                {{$producto->producto}}
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
                                {{$producto->pivot->pedir_registrado}}
                            </td>

                        </tr>
                    @endforeach           
                    
                </tbody>
            </table>
            
        </div>
    </div>
</div>

</x-app-layout>