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

    public function toggleRecibida()
    {
        $fecha = (!$this->orden->recibida) ? now('America/Belize') : null;

        $this->orden->update([
            'recibida' => ! $this->orden->recibida,
            'fecha_recibida' => $fecha,
        ]);

        $this->orden->refresh();

    }
};
?>

<tr class="border-b hover:bg-gray-50">
    <td id=folio class="px-6 py-4">{{str_pad($orden->id, 3, "0", STR_PAD_LEFT) }}</td>
    <td id=proveedor class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
        {{$orden->proveedor->nombre}}
    </td>
    <td id=fecha_generada class="px-6 py-4">{{$orden->fecha_generada->format('d/m/Y') }}</td>
    <td id=realizada class="px-6 py-4">
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

    <td id=realizada class="px-6 py-4">
        @if ($orden->fecha_realizada)
            {{$orden->fecha_realizada->format('d/m/Y')}}
        @else
            Sin fecha registrada
        @endif
    </td>

    <td class="px-6 py-4">
        <button 
        onclick="confirm('¿Confirmar cambio?') || event.stopImmediatePropagation()"
        wire:click="toggleRecibida"
        class="cursor-pointer"
        title="Cambiar estado"
        >
            @if ($orden->recibida)
                <!-- CHECK -->
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
                <!-- X -->
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

    <td id=fecha_recibida class="px-6 py-4">
        @if ($orden->fecha_recibida)
            {{$orden->fecha_recibida->format('d/m/Y')}}
        @else
            Sin fecha registrada
        @endif
    </td>
                            
    
    @if(auth()->user()->isAdmin())
        
        <td class="px-6 py-4 text-right">
            <div class="flex justify-end items-center gap-4">
                <a href="{{ route('ver_orden_compra', ['orden'=> $orden]) }}"
                class="text-blue-600 hover:underline cursor-pointer" target="_blank">
                    Ver orden
                </a>

                <a href="{{ route('descargar_orden_compra', ['orden'=> $orden]) }}"
                class="text-blue-600 hover:underline cursor-pointer">
                    Descargar
                </a>

                {{--<form action="{{ route('eliminar_producto', $producto) }}" 
                onsubmit="return confirm('Se eliminará el producto, ¿continuar?')" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="text-red-600 hover:underline cursor-pointer">
                        Eliminar
                    </button>
                </form>--}}
            </div>
        </td>
    @endif
    
</tr>
