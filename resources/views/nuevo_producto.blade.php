<x-app-layout>
    <div class="px-4 md:px-8 lg:px-12 max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow border border-gray-200 p-6 mt-6">

        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Agregar nuevo producto') }}
            </h2>
        </x-slot>

        <form method="POST" action="{{ route('guardar_producto') }}" class="space-y-6">
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
                        <option value="Bulto" @if(old('unidad') == "Bulto") selected @endif>Bulto</option>
                </select>
                <x-input-error :messages="$errors->get('unidad')" class="mt-2" />
            </div>


            <!-- Stock -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Stock actual
                    </label>
                    <input type="number" name="stock" min="0" class="w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                    required>
                    <x-input-error :messages="$errors->get('stock')" class="mt-2" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Stock máximo
                    </label>
                    <input type="number" name="stock_max" min="1" class="w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                    required>
                    <x-input-error :messages="$errors->get('stock_max')" class="mt-2" />
                   
                </div>
            </div>

            <!-- Precio -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Precio
                </label>
                <input type="number" step="0.01" name="precio" class="w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                required>
                <x-input-error :messages="$errors->get('precio')" class="mt-2" />
            </div>

            <!-- Botones -->
            <div class="flex justify-end gap-3 pt-4">
                <a href="{{ route('lista_productos') }}" class="px-4 py-2 rounded-md border border-gray-300 text-gray-700 hover:bg-gray-100">
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