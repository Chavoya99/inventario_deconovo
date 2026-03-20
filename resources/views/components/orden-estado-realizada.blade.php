<?php

use Livewire\Component;
use App\Models\OrdenCompra;

new class extends Component
{
    public OrdenCompra $orden;

    public function toggleRealizada()
    {
        $fecha = (!$this->orden->realizada) ? now('America/Belize') : null;

        $this->orden->update([
            'realizada' => ! $this->orden->realizada,
            'fecha_realizada' => $fecha,
        ]);

        $this->orden->refresh();
    }

    public function mount(OrdenCompra $orden)
    {
        $this->orden = $orden;
    }


};
?>

@fragment('estado')
<td class="px-6 py-4">
    <button
    onclick="confirm('¿Confirmar cambio?') || event.stopImmediatePropagation()"
    wire:click="toggleRealizada"
    class="cursor-pointer"
    title="Cambiar estado"
    >   
        
        @if ($orden->realizada)
            <svg xmlns="http://www.w3.org/2000/svg"
                width="18" height="18"
                viewBox="0 0 24 24"
                fill="none"
                stroke="#00ff00"
                stroke-width="3"
                stroke-linecap="round"
                stroke-linejoin="round"
                class="transition hover:scale-110">
                <path d="M20 6 9 17l-5-5"/>
            </svg>
        @else
            <svg xmlns="http://www.w3.org/2000/svg"
                width="18" height="18"
                viewBox="0 0 24 24"
                fill="none"
                stroke="#ff0000"
                stroke-width="3"
                stroke-linecap="round"
                stroke-linejoin="round"
                class="transition hover:scale-110">
                <path d="M18 6 6 18"/>
                <path d="m6 6 12 12"/>
            </svg>
        @endif
    </button>
</td>
@endframent

@fragment('fecha')
<td id=recibida class="px-6 py-4">
    @if ($orden->fecha_realizada)
        {{$orden->fecha_realizada->format('d/m/Y')}}
    @else
        Sin fecha registrada
    @endif
</td>
@endfragment