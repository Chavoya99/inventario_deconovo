<x-app-layout>
    

<div class="bg-neutral-primary-soft shadow-xs rounded-base border border-default">
    @if (session('success'))
            <div id="success-alert" class="fixed top-5 right-5 bg-green-100 border border-green-300 text-green-800 px-6 py-4 rounded-lg shadow-lg flex items-center gap-3 z-50">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M5 13l4 4L19 7"/>
                </svg>

                <span class="text-sm font-medium">
                    {{ session('success') }}
                </span>
            </div>
        @endif
    <div class="px-4 md:px-8 lg:px-12 py-4 md:px-8 lg:px-20">
        @if (auth()->user()->isAdmin())
            <a href="{{route('nuevo_producto')}}" class="py-3 px-4 inline-flex items-center gap-x-2 text-sm font-medium 
            rounded-lg border border-transparent bg-teal-500 text-white hover:bg-teal-600 focus:outline-hidden 
            focus:bg-teal-600 cursor-pointer">Nuevo producto</a>
            <br><br>
            
        @endif
        

    <div class="overflow-x-auto bg-white rounded-lg shadow border border-gray-200">
        <table class="w-full text-sm text-left text-gray-700">
            <thead class="bg-sky-400 text-black border-b">
                <tr>
                    <th class="px-6 py-3 font-medium">Producto</th>
                    <th class="px-6 py-3 font-medium">Proveedor</th>
                    <th class="px-6 py-3 font-medium">Maximo</th>
                    <th class="px-6 py-3 font-medium">Existencia</th> 
                    
                    @if(auth()->user()->isAdmin())
                        <th class="px-6 py-3 font-medium">Pedir</th>
                        <th class="px-6 py-3 font-medium">Precio</th>
                        
                        <th class="px-6 py-3 text-right font-medium">Acciones</th>
                    @endif
                    <th class="px-6 py-3 font-medium">Último reporte</th>
                    

                </tr>
            </thead>
            
            <tbody>
                
                @foreach ($proveedores as $proveedor)
                    <tr class="border-b hover:bg-gray-50">
                    <td id=nombre class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                        {{$proveedor->producto}}
                    </td>
                    <td id=stock class="px-6 py-4">{{$producto->proveedor}}</td>
                    <td id=stock_max class="px-6 py-4">{{$producto->maximo}}</td>
                    <td id=stock class="px-6 py-4">{{$producto->existencia}}</td>
                    @if(auth()->user()->isAdmin())
                        <td id=pedir class="px-6 py-4">{{$producto->pedir}}</td>
                        <td id=precio class="px-6 py-4">{{$producto->precio_venta}}</td>
                        
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
                                        Borrar
                                    </button>
                                </form>
                            </div>
                        </td>
                    @endif
                    
                    <td id=fecha class="px-6 py-4">
                        @if($producto->ultimo_reporte)
                            {{$producto->ultimo_reporte->format('d-m-Y H:i:s')}}
                        @else 
                            Sin reporte
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

<script>
    setTimeout(() => {
        const alert = document.getElementById('success-alert');
        if (alert) {
            alert.classList.add('opacity-0');
            setTimeout(() => alert.remove(), 500);
        }
    }, 3000);
</script>