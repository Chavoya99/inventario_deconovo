<x-app-layout>
    <div class="px-4 md:px-8 lg:px-12 max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow border border-gray-200 p-6 mt-6">
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Editar producto') }}
            </h2>
        </x-slot>

        <form method="POST" action="{{ route($ruta_guardar, $producto) }}" class="space-y-6">
            @csrf
            <!-- Nombre -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Nombre del producto
                </label>
                <input type="text" value="{{$producto->producto}}" name="nombre" class="w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500" required>
                <x-input-error :messages="$errors->get('nombre')" class="mt-2" />
            </div>

            <!-- Proveedor -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Proveedor
                </label>
                <select name="proveedor" class="w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                required>
                    <option value="{{null}}">Selecciona un proveedor</option>
                    
                    @foreach ($proveedores as $proveedor)
                        <option value="{{$proveedor->id}}"
                        @if($proveedor->id == old('proveedor') || $proveedor->id == $producto->proveedor_id) selected @endif>
                        {{$proveedor->nombre}}</option>
                        
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('proveedor')" class="mt-2" />
            </div>

            <!-- Unidad -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Unidad
                </label>
                <select name="unidad" class="w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                required>
                    <option value="{{null}}">Selecciona una unidad</option>
                        <option value="Caja" @if(old('unidad') == "Caja" || $producto->unidad == "Caja") selected @endif>Caja</option>
                        <option value="Pza" @if(old('unidad') == "Pza" || $producto->unidad == "Pza") selected @endif>Pza</option>
                        <option value="Saco" @if(old('unidad') == "Saco" ||$producto->unidad == "Saco") selected @endif>Saco</option>
                        <option value="Tarima" @if(old('unidad') == "Tarima" || $producto->unidad == "Tarima") selected @endif>Tarima</option>
                </select>
                <x-input-error :messages="$errors->get('unidad')" class="mt-2" />
            </div>

            @if (request()->routeIs('editar_recubrimiento'))
                <!-- Contenido-->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Contenido
                    </label>
                    <input
                        type="number"
                        name="contenido"
                        id="contenido"
                        min="0.01"
                        step="0.01"
                        class="w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                        value="{{$producto->contenido}}"
                        required
                    >
                    <x-input-error :messages="$errors->get('contenido')" class="mt-2" />
                </div>
            @endif

            @if(request()->routeIs('editar_producto'))
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6"
                    x-data="{
                        stock: {{$producto->existencia}},
                        stock_max: {{$producto->maximo}}
                    }">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Stock actual
                        </label>

                        <input
                            type="number"
                            name="stock"
                            min="0"
                            :max="stock_max"
                            x-model.number="stock"
                            @input="stock = Math.min(stock, stock_max)"
                            class="w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                            required
                        >

                        <x-input-error :messages="$errors->get('stock')" class="mt-2" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Stock máximo
                        </label>

                        <input
                            type="number"
                            name="stock_max"
                            min="1"
                            x-model.number="stock_max"
                            class="w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                            required
                        >

                        <x-input-error :messages="$errors->get('stock_max')" class="mt-2" />
                    </div>
                </div>
            @else
                <input type="hidden" name="stock" min="0" value="{{$producto->existencia}}" required>
                <input type="hidden" name="stock_max" min="1" value="{{$producto->maximo}}" required>
            
            @endif

            <div>
                <div>
                
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Precio proveedor
                    </label>
                    <input type="number" step="0.01" name="precio_proveedor" id="precio_proveedor"
                    value="{{$producto->precio_proveedor}}" min = 0.01 class="w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                    required>

                    <x-input-error :messages="$errors->get('precio_proveedor')" class="mt-2" />
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        % Utilidad 1
                    </label>
                    <input type="number" step="1" name="utilidad_1" id="porcentaje_utilidad_1"
                    value="{{$producto->utilidad_1}}" min=1 max=99 class="w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                    required>

                    <x-input-error :messages="$errors->get('utilidad_1')" class="mt-2" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Precio venta 1
                    </label>
                    <input 
                        type="number" 
                        step="0.01"
                        value="{{$producto->precio_venta_1}}"
                        id="precio_calculado_1"
                        class="w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                        disabled
                    >
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        % Utilidad 2
                    </label>
                    <input type="number" step="1" name="utilidad_2" id="porcentaje_utilidad_2"
                    value="{{$producto->utilidad_2}}" min=1 max=99 class="w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                    required>

                    <x-input-error :messages="$errors->get('utilidad_2')" class="mt-2" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Precio venta 2
                    </label>
                    <input 
                        type="number" 
                        step="0.01"
                        value="{{$producto->precio_venta_2}}"
                        id="precio_calculado_2"
                        class="w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                        disabled
                    >
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        % Utilidad 3
                    </label>
                    <input type="number" step="1" name="utilidad_3" id="porcentaje_utilidad_3"
                    value="{{$producto->utilidad_3}}" min=1 max=99 class="w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                    required>

                    <x-input-error :messages="$errors->get('utilidad_3')" class="mt-2" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Precio venta 3
                    </label>
                    <input 
                        type="number" 
                        step="0.01"
                        value="{{$producto->precio_venta_3}}"
                        id="precio_calculado_3"
                        class="w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                        disabled
                    >
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        % Utilidad 4
                    </label>
                    <input type="number" step="1" name="utilidad_4" id="porcentaje_utilidad_4"
                    value="{{$producto->utilidad_4}}" min=1 max=99 class="w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                    required>

                    <x-input-error :messages="$errors->get('utilidad_4')" class="mt-2" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Precio venta 4
                    </label>
                    <input 
                        type="number" 
                        step="0.01"
                        value="{{$producto->precio_venta_4}}"
                        id="precio_calculado_4"
                        class="w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                        disabled
                    >
                </div>
            </div>

            <!-- Botones -->
            <div class="flex justify-end gap-3 pt-4">
                <a href="{{ route($ruta_anterior) }}" class="px-4 py-2 rounded-md border border-gray-300 text-gray-700 hover:bg-gray-100">
                    Cancelar
                </a>

                <button type="submit" class="px-4 py-2 rounded-md bg-blue-600 text-white hover:bg-blue-700">
                    Guardar producto
                </button>
            </div>
        </form>
    </div>
</div>

        

</div>
</x-app-layout>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const precioProveedorInput = document.getElementById('precio_proveedor');
    const contenidoInput = document.getElementById('contenido');

    const porcentajes = [
        { porcentaje: 'porcentaje_utilidad_1', precio: 'precio_calculado_1' },
        { porcentaje: 'porcentaje_utilidad_2', precio: 'precio_calculado_2' },
        { porcentaje: 'porcentaje_utilidad_3', precio: 'precio_calculado_3' },
        { porcentaje: 'porcentaje_utilidad_4', precio: 'precio_calculado_4' },
    ];

    function calcularPrecio() {

        let precioProveedor = parseFloat(precioProveedorInput.value);
        let contenido = contenidoInput ? parseFloat(contenidoInput.value) : 1;

        porcentajes.forEach(item => {

            let porcentajeInput = document.getElementById(item.porcentaje);
            let precioCalculadoInput = document.getElementById(item.precio);

            let porcentaje = parseFloat(porcentajeInput.value);

            if (
                isNaN(precioProveedor) ||
                isNaN(porcentaje) ||
                porcentaje >= 100 ||
                isNaN(contenido)
            ) {
                precioCalculadoInput.value = '';
            } else {

                let resultado = Math.ceil(
                    precioProveedor / (1 - (porcentaje / 100))
                ) * contenido;

                precioCalculadoInput.value = resultado.toFixed(2);
            }

        });
    }

    precioProveedorInput.addEventListener('input', calcularPrecio);

    porcentajes.forEach(item => {
        document
            .getElementById(item.porcentaje)
            .addEventListener('input', calcularPrecio);
    });

    if (contenidoInput) {
        contenidoInput.addEventListener('input', calcularPrecio);
    }
});
</script>