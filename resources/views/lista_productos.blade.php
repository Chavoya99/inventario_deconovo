<x-app-layout>
    

<div class="bg-neutral-primary-soft shadow-xs rounded-base border border-default">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Lista de productos')." ".strtoupper($nombre_proveedor_actual) }}
        </h2>
        <a href="{{route('lista_productos')}}"
        class="py-3 px-4 inline-flex items-center gap-x-2 text-sm font-medium 
            rounded-lg border border-transparent bg-sky-300 text-black 
            hover:bg-sky-600 cursor-pointer">
            Ver productos
        </a>
        <a href="{{route('lista_recubrimientos')}}"
        class="py-3 px-4 inline-flex items-center gap-x-2 text-sm font-medium 
            rounded-lg border border-transparent bg-green-300 text-black 
            hover:bg-green-600 cursor-pointer">
            Ver recubrimientos
        </a>
    </x-slot>
    <div class="px-4 md:px-8 lg:px-12 py-4 md:px-8 lg:px-10">
        <div class="flex items-end gap-4">
        @if (auth()->user()->isAdmin())
                <!-- Botón nuevo producto -->
                @if (request()->routeIs('lista_productos'))
                    <a href="{{ route('nuevo_producto') }}"
                    class="py-3 px-4 inline-flex items-center gap-x-2 text-sm font-medium 
                            rounded-lg border border-transparent bg-teal-500 text-white 
                            hover:bg-teal-600 focus:outline-hidden focus:bg-teal-600 cursor-pointer">
                        Nuevo producto
                    </a>
                @elseif( request()->routeIs('lista_recubrimientos'))
                    <a href="{{ route('nuevo_recubrimiento') }}"
                    class="py-3 px-4 inline-flex items-center gap-x-2 text-sm font-medium 
                            rounded-lg border border-transparent bg-teal-500 text-white 
                            hover:bg-teal-600 focus:outline-hidden focus:bg-teal-600 cursor-pointer">
                        Nuevo recubrimiento
                    </a>
                @endif
                
        @endif
                <!-- Filtro por proveedor -->
                <form action="{{ route(request()->route()->uri) }}" method="GET"
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
                <a href="{{route(request()->route()->uri)}}"
                class="py-3 px-4 inline-flex items-center gap-x-2 text-sm font-medium 
                    rounded-lg border border-transparent bg-red-300 text-black 
                    hover:bg-red-800 cursor-pointer">
                    Limpiar filtro
                </a>
            </div>
            <br>
            <div class="flex items-end gap-4">
                <form action="{{ route(request()->route()->uri) }}" method="GET"
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

                            @if(request()->routeIs('lista_recubrimientos'))
                                <th class="px-6 py-3 font-medium">Contenido</th>
                            @endif
                            @if(request()->routeIs('lista_productos'))
                                <th class="px-6 py-3 font-medium">Máximo</th>
                            @endif
                            <th class="px-6 py-3 font-medium">Existencia</th> 
                            
                            @if(auth()->user()->isAdmin())
                                {{--<th class="px-6 py-3 font-medium">Utilidad</th>--}}
                                {{--<th class="px-6 py-3 font-medium">Precio proveedor</th>--}}
                            @endif
                            {{--<th class="px-6 py-3 font-medium">Precio venta</th>--}}

                            <th class="px-6 py-3 font-medium">Último reporte</th>
                            <th class="px-6 py-3 font-medium">Última orden</th>
                            @if (auth()->user()->isAdmin())
                                <th class="px-6 py-3 text-right font-medium">Acciones</th>
                            @endif 
                                
                            
                            

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

                        @if(request()->routeIs('lista_recubrimientos'))
                            <td id=contenido class="px-6 py-4">{{$producto->contenido}}</td>
                        @endif
                        @if(request()->routeIs('lista_productos'))
                            <td id=stock_max class="px-6 py-4">{{$producto->maximo}}</td>
                        @endif
                        <td id=stock class="px-6 py-4">{{$producto->existencia}}</td>
                        
                        @if(auth()->user()->isAdmin())
                            {{--<td id=utilidad class="px-6 py-4">{{$producto->utilidad}}%</td>
                            <td id=pedir class="px-6 py-4">{{$producto->pedir}}</td>
                            <td id=precio_proveedor class="px-6 py-4">{{number_Format($producto->precio_proveedor, 2)}}</td>--}}
                        @endif

                        {{--<td id=precio class="px-6 py-4">{{number_Format($producto->precio_venta, 2)}}</td>--}}

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
                        

                        @if (auth()->user()->isAdmin())
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end items-center gap-4">
                                    @if(request()->routeIs('lista_recubrimientos'))
                                        <a href="{{ route('editar_recubrimiento', $producto) }}"
                                        class="text-blue-600 hover:underline cursor-pointer">
                                            Editar
                                        </a>
                                    @elseif(request()->routeIs('lista_productos'))
                                        <a href="{{ route('editar_producto', $producto) }}"
                                        class="text-blue-600 hover:underline cursor-pointer">
                                            Editar
                                        </a>
                                    @endif
                                    <a
                                    onclick='abrirModalProducto(@json($producto))'
                                    class="text-blue-600 hover:underline cursor-pointer">
                                        Detalles
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

<div id="modalProducto" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center">
    
    <div class="bg-white p-6 rounded shadow-lg w-[500px]">

        <h2 class="text-xl font-bold mb-4">Detalles del producto</h2>

        <div class="grid grid-cols-2 gap-2 text-sm">

            <p><b>Producto:</b> <span id="m_producto"></span></p>
            <p><b>Proveedor:</b> <span id="m_proveedor"></span></p>
            <p><b>Unidad:</b> <span id="m_unidad"></span></p>

            <p><b>Existencia:</b> <span id="m_existencia"></span></p>

            <p @if(request()->routeIs('lista_recubrimientos')) hidden @endif><b>Máximo:</b> <span id="m_maximo"></span></p>


            <p @if(request()->routeIs('lista_recubrimientos')) hidden @endif><b>Pedir:</b> <span id="m_pedir"></span></p>

            <p><b>Precio proveedor:</b> <span id="m_precio_proveedor"></span></p>

            <p><b>Utilidad 1:</b> <span id="m_utilidad1"></span>%</p>
            <p><b>Utilidad 2:</b> <span id="m_utilidad2"></span>%</p>
            <p><b>Utilidad 3:</b> <span id="m_utilidad3"></span>%</p>
            <p><b>Utilidad 4:</b> <span id="m_utilidad4"></span>%</p>

            <p><b>Precio venta 1:</b> <span id="m_precio1"></span></p>
            <p><b>Precio venta 2:</b> <span id="m_precio2"></span></p>
            <p><b>Precio venta 3:</b> <span id="m_precio3"></span></p>
            <p><b>Precio venta 4:</b> <span id="m_precio4"></span></p>
            <p><b>Ultimo reporte:</b> <span id="m_reporte"></span></p>
            <p><b>Ultima orden:</b> <span id="m_orden"></span></p>

        </div>

        <button onclick="cerrarModal()" class="mt-4 bg-gray-500 text-white px-4 py-2 rounded">
            Cerrar
        </button>

    </div>

</div>

<script>

function abrirModalProducto(producto){
    document.getElementById('m_producto').textContent = producto.producto
    document.getElementById('m_unidad').textContent = producto.unidad
    document.getElementById('m_existencia').textContent = producto.existencia
    document.getElementById('m_maximo').textContent = producto.maximo
    document.getElementById('m_pedir').textContent = producto.pedir
    document.getElementById('m_proveedor').textContent = producto.proveedor.nombre

    document.getElementById('m_precio_proveedor').textContent = producto.precio_proveedor

    document.getElementById('m_utilidad1').textContent = producto.utilidad_1
    document.getElementById('m_utilidad2').textContent = producto.utilidad_2
    document.getElementById('m_utilidad3').textContent = producto.utilidad_3
    document.getElementById('m_utilidad4').textContent = producto.utilidad_4

    document.getElementById('m_precio1').textContent = producto.precio_venta_1
    document.getElementById('m_precio2').textContent = producto.precio_venta_2
    document.getElementById('m_precio3').textContent = producto.precio_venta_3
    document.getElementById('m_precio4').textContent = producto.precio_venta_4
    document.getElementById('m_reporte').textContent = producto.ultimo_reporte ? producto.ultimo_reporte : "Sin reporte"
    document.getElementById('m_orden').textContent = producto.ultima_orden ? producto.ultima_orden : "Sin orden"

    document.getElementById('modalProducto').classList.remove('hidden')

    
}

function cerrarModal(){
    document.getElementById('modalProducto').classList.add('hidden')
}

</script>

