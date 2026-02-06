<x-app-layout>
    

<div class="bg-neutral-primary-soft shadow-xs rounded-base border border-default">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Reportes faltantes') }}
        </h2>
    </x-slot>
    <div class="px-4 md:px-8 lg:px-12 py-4 md:px-8 lg:px-20">
        <div class="flex items-end gap-4">
            <!-- Filtro por proveedor -->
            <form action="{{ route('reportes_faltantes') }}" method="GET"
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
            <a href="{{route('reportes_faltantes')}}"
            class="py-3 px-4 inline-flex items-center gap-x-2 text-sm font-medium 
                rounded-lg border border-transparent bg-red-300 text-black 
                hover:bg-red-800 cursor-pointer">
                Limpiar filtro
            </a>
        </div>
        <br>
        <div class="overflow-x-auto bg-white rounded-lg shadow border border-gray-200">
            <table class="w-full text-sm text-left text-gray-700">
                @if ($reportes->isEmpty())
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-center text-gray-500">
                                No hay reportes registrados
                            </td>
                        </tr>
                @else
                    <thead class="bg-sky-400 text-black border-b">
                        <tr>
                            <th class="fixed px-6 py-3 font-medium">No. reporte</th>
                            <th class="px-6 py-3 font-medium">Proveedor</th>
                            <th class="px-6 py-3 font-medium">Estatus</th>
                            <th class="px-6 py-3 font-medium">Fecha</th>
                            <th class="px-6 py-3 text-right font-medium">Acciones</th>
                        
                        </tr>
                    </thead>
                    
                    <tbody>
                    
                    @foreach ($reportes as $reporte)
                        <tr
                            class="border-b hover:bg-gray-50"
                        >
                            <td class="px-6 py-4">
                                {{$reporte->id}}
                            </td>

                            <td class="px-6 py-4">
                                {{$reporte->proveedor->nombre}}
                            </td>
 
                            <td class="px-6 py-4">
                                {!! $reporte->status()!!}
                            </td>

                            <td class="px-6 py-4">
                                {{$reporte->fecha_generada->format('d/m/y')}}
                            </td>

                            <!-- ACCIONES -->
                            <td class="px-6 py-4">
                                <div class="flex justify-end items-center gap-4 text-sm">
                                @if(auth()->user()->isAdmin())
                                    

                                        <!-- Ver productos -->
                                        <a
                                            href="{{route('revisar_reporte', ['reporte' => $reporte])}}"
                                            class="text-blue-600 hover:underline"
                                        >
                                            Revisar
                                        </a>

                                        <!-- Eliminar -->
                                        <form
                                            action="{{route('eliminar_reporte_faltante', $reporte)}}"
                                            method="POST"
                                            onsubmit="return confirm('Se eliminará el reporte ¿Continuar?')"
                                        >
                                            @csrf
                                            @method('DELETE')
                                            <button class="text-red-600 hover:underline">
                                                Eliminar
                                            </button>
                                        </form>

                                    
                                @elseif(auth()->user()->isEmpleado())
                                    <a
                                        href="{{route('detalles_reporte', ['reporte'=>$reporte])}}"
                                        class="text-blue-600 hover:underline"
                                    >
                                        Detalles
                                    </a>
                                @endif
                                </div>
                            </td>
                        </tr>

                    @endforeach
                @endif

                    
                    
                </tbody>
            </table>
            
        </div>
        {{$reportes->links('components.pagination')}}
    </div>
</div>

</x-app-layout>

