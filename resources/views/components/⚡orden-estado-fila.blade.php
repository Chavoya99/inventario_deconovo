<?php

use Livewire\Component;
use App\Models\OrdenCompra;

new class extends Component
{
    public OrdenCompra $orden;
    public $mostrarModalComentarios = false;
    public $comentario = '';
    public $ruta_origen;



    public function abrirModalComentarios()
    {   
        $this->comentario = $this->orden->comentario ?? '';
        $this->mostrarModalComentarios = true;
    }

    public function guardarComentario()
    {   

        $this->orden->update(['comentario' => ($this->comentario == '') ? null : $this->comentario]);
        $this->mostrarModalComentarios = false;
    }

    public function cerrarModal()
    {
        $this->mostrarModalComentarios = false;
    }

    public function toggleRecibida()
    {   
        $transiciones = [
            'n' => 'p',
            'p' => 'r',
            'r' => 'n',
        ];

        /** @disregard */
        if($this->orden->recibida == 'r' && auth()->user()->isEmpleado()){
            return;
        }

        $fecha = now('America/Belize');

        $this->orden->update([
            'recibida' => $transiciones[$this->orden->recibida],
            'fecha_recibida' => $fecha,
        ]);

        $this->orden->refresh();

    }

    public function reiniciar_recibida(){

        $this->orden->update([
            'recibida' => 'n',
            'fecha_recibida' => null,
        ]);
    }
};
?>



<tr class="border-b hover:bg-gray-50">
    <td id=folio class="px-6 py-4">{{str_pad($orden->id, 3, "0", STR_PAD_LEFT) }}</td>
    <td id=proveedor class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
        {{$orden->proveedor->nombre}}
    </td>
    <td id=fecha_generada class="px-6 py-4">{{$orden->fecha_generada->format('d/m/Y') }}</td>

    <td class="px-6 py-4">
        <button 
        onclick="confirm('¿Confirmar cambio?') || event.stopImmediatePropagation()"
        wire:click="toggleRecibida()"
        class="cursor-pointer"
        title="Cambiar estado"
        >
            @if ($orden->recibida == 'r')
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
            @elseif($orden->recibida == 'p')
                <svg xmlns="http://www.w3.org/2000/svg"
                    width="18" height="18"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="#FF8000"
                    stroke-width="3"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    class="transition hover:scale-110">

                    <path d="M5 12h14"/>

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
        @if ($orden->fecha_recibida && $orden->recibida == 'p')
            {{$orden->fecha_recibida->format('d/m/Y ')}}(Parcial)
        @elseif ($orden->fecha_recibida && $orden->recibida == 'r')
            {{$orden->fecha_recibida->format('d/m/Y ')}}(Completa)
        @else
            Sin fecha registrada
        @endif
    </td>
                            
    
    
        
        <td class="px-6 py-4 text-right">
            <div class="flex justify-end items-center gap-4">
                <button
                    wire:click="abrirModalComentarios()"
                    class="text-blue-600 hover:underline cursor-pointer">
                    Comentarios
                </button>

                @if ($ruta_origen == 'lista_ordenes_compra_internas')
                    <a href="{{ route('ver_orden_compra', ['orden'=> $orden, 'tipo' => 'interna']) }}"
                    class="text-blue-600 hover:underline cursor-pointer" target="_blank">
                        Ver orden
                    </a>

                    <a href="{{ route('descargar_orden_compra', ['orden'=> $orden, 'tipo' => 'interna']) }}"
                    class="text-blue-600 hover:underline cursor-pointer">
                        Descargar
                    </a>
                @endif
                @if(auth()->user()->isAdmin())     
                    @if (request()->routeIs('lista_ordenes_compra_proveedor'))
                        <a href="{{ route('ver_orden_compra', ['orden'=> $orden, 'tipo' => 'proveedor']) }}"
                        class="text-blue-600 hover:underline cursor-pointer" target="_blank">
                            Ver orden
                        </a>

                        <a href="{{ route('descargar_orden_compra', ['orden'=> $orden, 'tipo' => 'proveedor']) }}"
                        class="text-blue-600 hover:underline cursor-pointer">
                            Descargar
                        </a>
                    @endif


                    <form action="{{ route('eliminar_orden_compra', ['ordenCompra' => $orden]) }}" 
                    onsubmit="return confirm('Se eliminará la orden de compra de forma permanente, ¿continuar?')" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="text-red-600 hover:underline cursor-pointer">
                            Eliminar
                        </button>
                    </form>
                
                    
                @endif
            </div>
        </td>


        @teleport('body')
        <div
            x-data="{ open: @entangle('mostrarModalComentarios') }"
            x-show="open"
            x-transition:enter="transition-all ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95 translate-y-2"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition-all ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
            x-transition:leave-end="opacity-0 scale-95 translate-y-2"
            class="fixed inset-0 z-50 flex items-center justify-center">

            <div class="fixed inset-0 bg-black bg-opacity-50"></div>

            <div class="bg-white rounded-lg shadow-lg w-full max-w-lg z-10 p-6">

                <h2 class="text-lg font-semibold mb-4">
                    Comentarios Orden {{str_pad($orden->id, 3, "0", STR_PAD_LEFT) }}
                </h2>

                <textarea
                    wire:model.defer="comentario"
                    class="w-full rounded-md border-gray-300"
                    rows="5"
                    placeholder="Escribe un comentario">
                </textarea>

                <div class="flex justify-end gap-3 mt-4">

                    <button wire:click="cerrarModal"
                            class="px-4 py-2 bg-gray-300 rounded">
                        Cancelar
                    </button>

                    <button wire:click="guardarComentario"
                            class="px-4 py-2 bg-blue-600 text-white rounded">
                        Guardar
                    </button>

                </div>

            </div>

        </div>
        @endteleport
</tr>


