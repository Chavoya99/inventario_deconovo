<?php

use Livewire\Component;

new class extends Component
{
    public $reporte;

    public function aprobar(){
        $this->reporte->update(['status' => 'aprobado']);
        $this->redirect(route('revisar_reporte',['reporte' => $this->reporte->id]));
    }

    public function rechazar(){
        $this->reporte->update(['status' => 'rechazado']);
        $this->redirect(route('revisar_reporte',['reporte' => $this->reporte->id]));
    }
};
?>

<div class="flex items-center justify-between gap-6 p-4">

    <!-- INFO -->
    <div class="text-sm text-gray-700">
        <span class="font-medium">
            Fecha:
        </span>
        {{$reporte->fecha_generada->format('d/m/Y')}}

        <span class="ml-6">
            <span class="font-medium">Estatus:</span>
            {!! $reporte->status() !!}
        </span>
    </div>

    <!-- ACCIONES -->
    <div class="flex items-center gap-3">
        
        <!-- APROBAR -->
        <button
            onclick="confirm('¿Aprobar reporte?') || event.stopImmediatePropagation()"
            wire:click="aprobar"
            @disabled($reporte->status === 'aprobado')
            class="
                px-4 py-2 text-sm font-medium rounded
                text-white bg-green-600
                hover:bg-green-700
                disabled:bg-green-300
                disabled:cursor-not-allowed
                transition
            "
            title="Aprobar reporte"
        >
            Aprobar
        </button>

        <!-- RECHAZAR -->
        <button
            onclick="confirm('¿Rechazar reporte?') || event.stopImmediatePropagation()"
            wire:click="rechazar"
            @disabled($reporte->status === 'rechazado')
            class="
                px-4 py-2 text-sm font-medium rounded
                text-white bg-red-600
                hover:bg-red-700
                disabled:bg-red-300
                disabled:cursor-not-allowed
                transition
            "
            title="Rechazar reporte"
        >
            Rechazar
        </button>
        

    </div>
</div>