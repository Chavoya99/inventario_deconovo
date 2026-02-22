<x-app-layout>
    <div class="px-4 md:px-8 lg:px-12 max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow border border-gray-200 px-6 pb-2 mt-2">

        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Agregar nuevo producto') }}
            </h2>
        </x-slot>

        <form method="POST" action="{{ route($ruta_guardar) }}" class="space-y-6">
            @csrf

            <!-- Nombre -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Nombre del producto
                </label>
                <input type="text" name="nombre" class="w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500" required>
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
                        <option value="{{$proveedor->id}}" @if(old('proveedor') == $proveedor->id) selected @endif>{{$proveedor->nombre}}</option>
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
                        <option value="Caja" @if(old('unidad') == "Caja") selected @endif>Caja</option>
                        <option value="Pza" @if(old('unidad') == "Pza") selected @endif>Pza</option>
                        <option value="Bulto por saco" @if(old('unidad') == "Bulto por saco") selected @endif>Bulto por saco</option>
                        <option value="Tarima" @if(old('unidad') == "Tarima") selected @endif>Tarima</option>
                </select>
                <x-input-error :messages="$errors->get('unidad')" class="mt-2" />
            </div>

            @if (request()->routeIs('nuevo_recubrimiento'))
                <!-- Contenido-->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Contenido
                    </label>
                    <input
                        type="number"
                        id="contenido"
                        name="contenido"
                        min="1"
                        value=0
                        step="0.01"
                        class="w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                        required
                    >
                    <x-input-error :messages="$errors->get('contenido')" class="mt-2" />
                </div>
            @endif
            

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6"
                x-data="{
                    stock: 0,
                    stock_max: 1
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

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Precio proveedor
                    </label>
                    <input type="number" step="0.01" name="precio_proveedor" id="precio_proveedor"
                    value=0 min = 0.01 min=1 class="w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                    required>

                    <x-input-error :messages="$errors->get('precio_proveedor')" class="mt-2" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        % Utilidad
                    </label>
                    <input type="number" step="1" name="utilidad" id="porcentaje_utilidad"
                    value=0 min=1 max=99 class="w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                    required>

                    <x-input-error :messages="$errors->get('utilidad')" class="mt-2" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Precio venta
                    </label>
                    <input 
                        type="number" 
                        step="0.01"
                        value=0
                        id="precio_calculado"
                        class="w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                        disabled
                    >
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4">
                <a onclick="window.history.back()" class="px-4 py-2 rounded-md border border-gray-300 text-gray-700 hover:bg-gray-100 cursor-pointer">
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
    const porcentajeInput = document.getElementById('porcentaje_utilidad');
    const precioCalculadoInput = document.getElementById('precio_calculado');
    const contenidoInput = document.getElementById('contenido');

    function calcularPrecio() {
        let precioProveedor = parseFloat(precioProveedorInput.value);
        let porcentaje = parseFloat(porcentajeInput.value);

        let contenido = (contenidoInput) ? parseFloat(contenidoInput.value) : 1;
        console.log(contenido);

        if (!precioProveedor || !porcentaje || porcentaje >= 100 || !contenido) {
            precioCalculadoInput.value = '';
            return;
        }

        let resultado = (precioProveedor * contenido) / (1 - (porcentaje / 100));
        precioCalculadoInput.value = resultado.toFixed(2);
    }

    // 👇 Se ejecuta cada vez que el usuario escribe
    precioProveedorInput.addEventListener('input', calcularPrecio);
    porcentajeInput.addEventListener('input', calcularPrecio);
    if(contenidoInput){
        contenidoInput.addEventListener('input', calcularPrecio);
    }
});
</script>