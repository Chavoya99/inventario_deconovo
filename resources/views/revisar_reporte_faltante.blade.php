
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
        <button
            onclick="confirm('¿Generar orden de compra?') || event.stopImmediatePropagation()"
            class="
                my-5
                block mx-auto
                px-4 py-2 text-sm font-medium rounded
                text-white bg-sky-600
                hover:bg-sky-800
                transition
                disabled:opacity-50 hover:bg-sky-600 disabled:cursor-not-allowed
            "
            title="Generar orden de compra"
            @if($reporte->status != 'aprobado') disabled @endif
            >
            Generar orden de compra
        </button>
        
        <livewire-revisar-reporte-tabla :productos=$productos :productos_cero=$productos_cero :reporte=$reporte :reporte_id="$reporte->id" :key="$reporte->id"/>

    </div>
</div>

</x-app-layout>