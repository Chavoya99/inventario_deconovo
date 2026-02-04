<x-app-layout>
    

<div class="bg-neutral-primary-soft shadow-xs rounded-base border border-default">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Ordenes de compra')." ".strtoupper($nombre_proveedor_actual) }}
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
                    Nueva orden de compra
                </a>

                <!-- Filtro por proveedor -->
                <form action="{{ route('lista_ordenes_compra') }}" method="GET"
                    class="flex items-end gap-3">

                    <select name="proveedor"
                            class="rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                            required>
                        <option value="">Selecciona un proveedor</option>
                        @foreach ($proveedores as $proveedor)
                            <option value="{{ $proveedor->id }}"
                                {{ request('proveedor') == $proveedor->id ? 'selected' : '' }}>
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
            <br>
            <div class="flex items-end gap-4">
                <a href="{{ route('lista_ordenes_compra', ['proveedor' => request()->get('proveedor'),'filtro' => 'realizadas']) }}"
                class="py-3 px-4 inline-flex items-center gap-x-2 text-sm font-medium 
                        rounded-lg border border-transparent bg-sky-500 hover:bg-cyan-600 text-white">
                    Realizadas
                </a>

                <a href="{{ route('lista_ordenes_compra', ['proveedor' => request()->get('proveedor'), 'filtro' => 'recibidas']) }}"
                class="py-3 px-4 inline-flex items-center gap-x-2 text-sm font-medium 
                        rounded-lg border border-transparent bg-green-400 hover:bg-green-600 text-white">
                    Recibidas
                </a>

                <a href="{{ route('lista_ordenes_compra', ['proveedor' => request()->get('proveedor'), 'filtro' => 'pendientes']) }}"
                class="py-3 px-4 inline-flex items-center gap-x-2 text-sm font-medium 
                        rounded-lg border border-transparent bg-yellow-300 hover:bg-yellow-500 text-black">
                    Pendientes
                </a>

                <a href="{{route('lista_ordenes_compra', ['proveedor'=> request()->get('proveedor')])}}"
                class="py-3 px-4 inline-flex items-center gap-x-2 text-sm font-medium 
                    rounded-lg border border-transparent bg-neutral-600 text-white 
                    hover:bg-neutral-800 cursor-pointer">
                    Todas
                </a>

                <a href="{{route('lista_ordenes_compra')}}"
                class="py-3 px-4 inline-flex items-center gap-x-2 text-sm font-medium 
                    rounded-lg border border-transparent bg-red-300 text-black 
                    hover:bg-red-800 cursor-pointer">
                    Limpiar filtros
                </a>

            </div>  
            <br>
            
        @endif
        

        <div class="overflow-x-auto bg-white rounded-lg shadow border border-gray-200">
            @if ($ordenes_compra->isEmpty())
                <table class="w-full text-sm text-left text-gray-700">
                    <tr>
                        <td colspan="3" class="px-6 py-4 text-center text-gray-500">
                            No hay ordenes de compra registradas
                        </td>
                    </tr>
                </table>
            @else
                <table class="w-full text-sm text-left text-gray-700">
                    <thead class="bg-sky-400 text-black border-b">
                        <tr>
                            <th class="px-6 py-3 font-medium">Folio</th>
                            <th class="px-6 py-3 font-medium">Proveedor</th>
                            <th class="px-6 py-3 font-medium">Fecha generada</th>
                            <th class="px-6 py-3 font-medium">Realizada</th>
                            <th class="px-6 py-3 font-medium">Fecha realizada</th>
                            <th class="px-6 py-3 font-medium">Recibida</th>
                            <th class="px-6 py-3 font-medium">Fecha recibida</th> 
                            <th class="px-6 py-3 text-right font-medium">Acciones</th>
                        </tr>
                    </thead>
                    
                    <tbody>
                        @foreach ($ordenes_compra as $orden)
                            <livewire:orden-estado-fila :orden="$orden"/>
                        @endforeach
                    </tbody>
                </table>
                
            @endif
        </div>
        {{ $ordenes_compra->links('components.pagination') }}
    </div>

</div>

</x-app-layout>

