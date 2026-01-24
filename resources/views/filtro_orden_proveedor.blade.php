<x-app-layout>
    

<div class="bg-neutral-primary-soft shadow-xs rounded-base border border-default">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Ordenes de compra '. $proveedor->nombre) }}
        </h2>
    </x-slot>
    <div class="px-4 md:px-8 lg:px-12 py-4 md:px-8 lg:px-20">
        @if (auth()->user()->isAdmin())
            <div class="flex items-end gap-4">

                <!-- Botón nuevo producto -->
                <a href=""
                class="py-3 px-4 inline-flex items-center gap-x-2 text-sm font-medium 
                        rounded-lg border border-transparent bg-teal-500 text-white 
                        hover:bg-teal-600 focus:outline-hidden focus:bg-teal-600 cursor-pointer">
                    Nuevo orden de compra
                </a>

                <!-- Filtro por proveedor -->
                <form action="{{ route('filtro_orden_proveedor') }}" method="GET"
                    class="flex items-end gap-3">

                    <select name="proveedor"
                            class="rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                            required>
                        <option value="">Selecciona un proveedor</option>
                        @foreach ($proveedores as $proveedor)
                            <option value="{{ $proveedor->id }}"
                                {{ old('proveedor') == $proveedor->id ? 'selected' : '' }}>
                                {{ $proveedor->nombre }}
                            </option>
                        @endforeach
                    </select>

                    <button type="submit"
                            class="py-3 px-4 inline-flex items-center gap-x-2 text-sm font-medium 
                                rounded-lg border border-transparent bg-teal-500 text-white 
                                hover:bg-teal-600 focus:outline-hidden focus:bg-teal-600 cursor-pointer">
                        Aplicar filtro
                    </button>
                </form>

            </div>  
            <br><br>
            
        @endif
        

        <div class="overflow-x-auto bg-white rounded-lg shadow border border-gray-200">
            <table class="w-full text-sm text-left text-gray-700">
                @if ($ordenes_compra->isEmpty())
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-center text-gray-500">
                                No hay ordenes de compra registradas
                            </td>
                        </tr>
                @else   
                    <thead class="bg-sky-400 text-black border-b">
                        <tr>
                            <th class="px-6 py-3 font-medium">Proveedor</th>
                            <th class="px-6 py-3 font-medium">Fecha generada</th>
                            <th class="px-6 py-3 font-medium">Realizada</th>
                            <th class="px-6 py-3 font-medium">Fecha realizada</th>
                            <th class="px-6 py-3 font-medium">Recibida</th>
                            <th class="px-6 py-3 font-medium">Fecha ecibida</th> 
                            
                            @if(auth()->user()->isAdmin())
                                <th class="px-6 py-3 font-medium">Pedir</th>
                                <th class="px-6 py-3 font-medium">Precio</th>
                                
                                <th class="px-6 py-3 text-right font-medium">Acciones</th>
                            @endif
                            <th class="px-6 py-3 font-medium">Último reporte</th>
                            

                        </tr>
                    </thead>
                    
                    <tbody>
                    
                    @foreach ($ordenes_compra as $orden)
                        <tr class="border-b hover:bg-gray-50">
                        <td id=proveedor class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                            {{$orden->proveedor->nombre}}
                        </td>
                        <td id=fecha_generada class="px-6 py-4">{{$orden->fecha_realizada->format('d/m/Y') }}</td>
                        <td id=realizada class="px-6 py-4">{{$orden->realizda}}</td>
                        <td id=recibida class="px-6 py-4">{{$orden->recibida}}</td>
                        {{--@if(auth()->user()->isAdmin())
                            <td id=pedir class="px-6 py-4">{{$producto->pedir}}</td>
                            <td id=precio class="px-6 py-4">{{number_Format($producto->precio_venta, 2)}}</td>
                            
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end items-center gap-4">
                                    <a href="{{ route('editar_producto', $producto) }}"
                                    class="text-blue-600 hover:underline cursor-pointer">
                                        Editar
                                    </a>

                                    <form action="{{ route('eliminar_producto', $producto) }}" 
                                    onsubmit="return confirm('Se eliminará el producto, ¿continuar?')" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="text-red-600 hover:underline cursor-pointer">
                                            Eliminar
                                        </button>
                                    </form>
                                </div>
                            </td>
                        @endif--}}
                        
                        </tr>
                    @endforeach
                @endif
                    
                    
                </tbody>
            </table>
        </div>
    </div>

</div>

</x-app-layout>

