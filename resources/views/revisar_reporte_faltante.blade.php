<x-app-layout>
    

<div class="bg-neutral-primary-soft shadow-xs rounded-base border border-default">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ 'Revisar reporte '. strtoupper($proveedor->nombre)}}
        </h2>
    </x-slot>
    <div class="px-4 md:px-8 lg:px-12 py-4 md:px-8 lg:px-20">
        <x-back-button/>
        <livewire-reporte-estatus :reporte="$reporte"/> 

        <div class="overflow-x-auto bg-white rounded-lg shadow border border-gray-200">
            <livewire-revisar-reporte-tabla :productos=$productos :reporte=$reporte :reporte_id="$reporte->id" :key="$reporte->id"/>
            
        </div>
    </div>
</div>

</x-app-layout>

