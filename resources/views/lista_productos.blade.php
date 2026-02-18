<x-app-layout>
    

<div class="bg-neutral-primary-soft shadow-xs rounded-base border border-default">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Lista de productos')." ".strtoupper($nombre_proveedor_actual) }}
        </h2>
    </x-slot>
    <div class="px-4 md:px-8 lg:px-12 py-4 md:px-8 lg:px-20">
        <div class="flex items-end gap-4">
        @if (auth()->user()->isAdmin())
                <!-- Botón nuevo producto -->
                <a href="{{ route('nuevo_producto') }}"
                class="py-3 px-4 inline-flex items-center gap-x-2 text-sm font-medium 
                        rounded-lg border border-transparent bg-teal-500 text-white 
                        hover:bg-teal-600 focus:outline-hidden focus:bg-teal-600 cursor-pointer">
                    Nuevo producto
                </a>
        @endif
                <!-- Filtro por proveedor -->
                <form action="{{ route('lista_productos') }}" method="GET"
                    class="flex items-end gap-3">

                    <select name="proveedor"
                            class="rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                            required>
                        <option value="">Selecciona un proveedor</option>
                        @foreach ($proveedores as $proveedor)
                            <option value="{{ $proveedor->id }}"
                                {{ old('proveedor', request('proveedor')) == $proveedor->id ? 'selected' : '' }}>
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
                <a href="{{route('lista_productos')}}"
                class="py-3 px-4 inline-flex items-center gap-x-2 text-sm font-medium 
                    rounded-lg border border-transparent bg-red-300 text-black 
                    hover:bg-red-800 cursor-pointer">
                    Limpiar filtro
                </a>
            </div>
            <br>
            <div class="flex items-end gap-4">
                <form action="{{ route('lista_productos') }}" method="GET"
                class="flex items-end gap-3">
                    <input class="rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500" 
                    type="text" placeholder="Busqueda por nombre" name="busqueda" required>
                    <button type="submit"
                            class="py-3 px-4 inline-flex items-center gap-x-2 text-sm font-medium 
                                rounded-lg border border-transparent bg-teal-500 text-white 
                                hover:bg-teal-600 focus:outline-hidden focus:bg-teal-600 cursor-pointer">
                        Buscar
                    </button>
                </form>
            </div>  
            <br>
            
        <div class="overflow-x-auto bg-white rounded-lg shadow border border-gray-200">
            <table class="w-full text-sm text-left text-gray-700">
                @if ($productos->isEmpty())
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-center text-gray-500">
                                No hay productos registrados
                            </td>
                        </tr>
                @else   
                    <thead class="bg-sky-400 text-black border-b">
                        <tr>
                            <th class="px-6 py-3 font-medium">Producto</th>
                            <th class="px-6 py-3 font-medium">Proveedor</th>
                            <th class="px-6 py-3 font-medium">Unidad</th>
                            @if(auth()->user()->isAdmin())<th class="px-6 py-3 font-medium">Contenido</th>@endif
                            <th class="px-6 py-3 font-medium">Máximo</th>
                            <th class="px-6 py-3 font-medium">Existencia</th> 
                            
                            @if(auth()->user()->isAdmin())
                                <th class="px-6 py-3 font-medium">Pedir</th>
                                <th class="px-6 py-3 font-medium">Precio venta</th>
                                <th class="px-6 py-3 font-medium">Precio proveedor</th>
                                
                                <th class="px-6 py-3 text-right font-medium">Acciones</th>
                            @endif
                            <th class="px-6 py-3 font-medium">Último reporte</th>
                            <th class="px-6 py-3 font-medium">Última orden</th>
                            

                        </tr>
                    </thead>
                    
                    <tbody>
                    
                    @foreach ($productos as $producto)
                        <tr class="border-b hover:bg-gray-50">
                        <td id=nombre class="px-6 py-4 font-medium text-gray-900">
                            {{$producto->producto}}
                        </td>
                        <td id=proveedor class="px-6 py-4">{{$producto->proveedor->nombre}}</td>
                        <td id=unidad class="px-6 py-4">{{$producto->unidad}}</td>
                        @if(auth()->user()->isAdmin())<td id=contenido class="px-6 py-4">{{$producto->contenido}}</td>@endif
                        <td id=stock_max class="px-6 py-4">{{$producto->maximo}}</td>
                        <td id=stock class="px-6 py-4">{{$producto->existencia}}</td>
                        
                        @if(auth()->user()->isAdmin())
                            <td id=pedir class="px-6 py-4">{{$producto->pedir}}</td>
                            <td id=precio class="px-6 py-4">{{number_Format($producto->precio_venta, 2)}}</td>
                            <td id=precio_proveedor class="px-6 py-4">{{number_Format($producto->precio_proveedor, 2)}}</td>
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
                        @endif
                        
                        <td id=fecha class="px-6 py-4">
                            @if($producto->ultimo_reporte)
                                {{$producto->ultimo_reporte->format('d/m/Y')}}
                            @else 
                                Sin reporte
                            @endif
                        </td>
                        <td id=fecha_orden class="px-6 py-4">
                            @if($producto->ultima_orden)
                                {{$producto->ultima_orden->format('d/m/Y')}}
                            @else 
                                Sin orden
                            @endif
                        </td>
                        </tr>
                    @endforeach
                @endif
                    
                    
                </tbody>
            </table>
            
        </div>
        {{ $productos->links('components.pagination') }}
    </div>

</div>

</x-app-layout>

