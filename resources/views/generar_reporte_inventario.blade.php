<x-app-layout>
    

<div class="bg-neutral-primary-soft shadow-xs rounded-base border border-default">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Generar reporte') }}
        </h2>
    </x-slot>
    
    <div class="px-4 md:px-8 lg:px-12 py-4 md:px-8 lg:px-20">

        <div x-data="{ tab: {{ $proveedorActivo }} }">

            <!-- TABS -->
            <div class="flex flex-wrap gap-2 mb-4 border-b pb-2">
                @foreach ($proveedores as $proveedor)
                    <a href="{{ route('reporte_inventario', ['proveedor_id' => $proveedor->id]) }}"
                    @click.prevent="tab = {{ $proveedor->id }}"
                    class="px-4 py-2 rounded-t-lg text-sm font-medium"
                    :class="tab === {{ $proveedor->id }}
                        ? 'bg-sky-400 text-black'
                        : 'bg-gray-200 text-gray-600 hover:bg-gray-300'">
                        {{ $proveedor->nombre }}
                    </a>
                @endforeach
            </div>

            <!-- TABLAS -->
                
                @foreach ($proveedores as $proveedor)
                    
                    <div x-show="tab === {{ $proveedor->id }}" x-transition>
                        <span>
                                
                            Último reporte: @if ($proveedor->hasReportes())
                                {{ \Carbon\Carbon::parse($proveedor->reportes_faltantes_max_fecha_generada)->format('d/m/Y') }}
                            @else
                                Sin reportes
                            @endif
                        </span>
                        <form action="{{route('generar_reporte_inventario')}}" method="POST"
                        onsubmit="return confirm('Se generará el reporte, ¿continuar?')">
                        @csrf
                            <div class="overflow-x-auto bg-white rounded-lg shadow border border-gray-200">
                                <table class="w-full text-sm text-left text-gray-700">

                                    <thead class="bg-sky-400 text-black border-b">
                                        <tr>
                                            <th class="px-6 py-3 font-medium">Producto</th>
                                            <th class="px-6 py-3 font-medium">Unidad</th>
                                            {{--<th class="px-6 py-3 font-medium">Proveedor</th>--}}
                                            <th class="px-6 py-3 font-medium">Máximo</th>
                                            <th class="px-6 py-3 font-medium">Existencia</th>
                                            <th class="px-6 py-3 font-medium">Pedir</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @forelse ($proveedor->productos as $index => $producto)
                                            <tr class="border-b hover:bg-gray-50"
                                                x-data="{
                                                    maximo: {{ $producto->maximo }},
                                                    existencia: {{ old('productos.' . $index . '.existencia') != null 
                                                    ? old('productos.' . $index . '.existencia') : 'null' }},
                                                    get pedir() {
                                                    if (this.existencia === null || this.existencia === '') {
                                                        return '-';
                                                    }
                                                        return Math.max(this.maximo - this.existencia, 0);
                                                    }
                                                }"
                                            >
                                            <input type="hidden" name="productos[{{$index}}][id]" value="{{$producto->id}}">
                                            <input type="hidden" name="productos[{{$index}}][producto]" value="{{$producto->producto}}">
                                            <input type="hidden" name="productos[{{$index}}][unidad]" value="{{$producto->unidad}}">
                                            <input type="hidden" name="productos[{{$index}}][maximo]" value="{{$producto->maximo}}">
                                            <input type="hidden" name="proveedor" value="{{$proveedor->id}}">
                                                <td class="px-6 py-4 font-medium text-gray-900">
                                                    {{ $producto->producto }}
                                                </td>

                                                <td class="px-6 py-4 font-medium text-gray-900">
                                                    {{ $producto->unidad }}
                                                </td>

                                                {{--<td class="px-6 py-4">
                                                    {{ $proveedor->nombre }}
                                                </td>--}}

                                                <td class="px-6 py-4">
                                                    {{ $producto->maximo }}
                                                </td>

                                                <!-- EXISTENCIA -->
                                                <td class="px-6 py-4">
                                                    <input type="number"
                                                        min="0"
                                                        :max="maximo"
                                                        x-model.number="existencia"
                                                        @input="existencia = Math.min(existencia, maximo)"
                                                        name="productos[{{$index}}][existencia]"
                                                        id="stock_{{$producto->id}}"
                                                        class="w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                                                        required>
                                                        <x-input-error 
                                                        :messages="$errors->get('productos.' . $index . '.existencia')" 
                                                        class="mt-1" />
                                                </td>
                                            
                                                <!-- PEDIR -->
                                                <td class="px-6 py-4 font-semibold text-blue-600">
                                                    <span x-text="pedir"></span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                                    No hay productos para este proveedor
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>

                                </table>
                            </div>
                            <br>
                            <div class="flex justify-end">

                        
                                <button type="submit"
                                    class="py-3 px-4 inline-flex items-center gap-x-2 text-sm font-medium 
                                        rounded-lg border border-transparent bg-teal-500 text-white 
                                        hover:bg-teal-600 focus:outline-hidden focus:bg-teal-600 cursor-pointer">
                                    Generar reporte
                                </button>
                            </div>
                        </form>
                    </div>
                @endforeach
            
                
            
        
            

        </div>
    </div>

</div>

</x-app-layout>

