<x-app-layout>
    

<div class="bg-neutral-primary-soft shadow-xs rounded-base border border-default">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ 'Revisar reporte '. strtoupper($proveedor->nombre)}}
        </h2>
    </x-slot>
    <div class="px-4 md:px-8 lg:px-12 py-4 md:px-8 lg:px-20">
       
        <livewire-reporte-estatus :reporte="$reporte"/> 

        <div class="overflow-x-auto bg-white rounded-lg shadow border border-gray-200">
            <table class="w-full text-sm text-left text-gray-700">
                    <thead class="bg-sky-400 text-black border-b">
                        <tr>
                            <th class="px-6 py-3 font-medium">Producto</th>
                            <th class="px-6 py-3 font-medium">Unidad</th>
                            <th class="px-6 py-3 font-medium">Máximo</th>
                            <th class="px-6 py-3 font-medium">Existencia</th>
                            <th class="px-6 py-3 font-medium">Pedir</th>
                            <th class="px-6 py-3 font-medium">Precio venta</th>
                            <th class="px-6 py-3 font-medium">Precio proveedor</th>
                            @if (auth()->user()->isAdmin())
                                <th class="px-6 py-3 text-right font-medium">Acciones</th>
                            @endif
                            
                        
                        </tr>
                    </thead>
                    
                    <tbody>
                    
                    @foreach ($productos as $producto)
                        <livewire-reporte-producto-estado :producto=$producto />
                    @endforeach           
                    
                </tbody>
            </table>
            
        </div>
    </div>
</div>

</x-app-layout>

