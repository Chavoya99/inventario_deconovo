<?php

use Livewire\Component;
use App\Models\Producto;
use App\Models\ReporteFaltante;
use Livewire\Attributes\Computed;

new class extends Component
{
    
    public $producto_id, $reporte_id, $producto;

    public function eliminar_producto_reporte(){
        
        $reporte = ReporteFaltante::find($this->reporte_id);
        
        $reporte->productos()->detach($this->producto_id);
        
        $this->dispatch('productoEliminado');

        $this->redirect(route('revisar_reporte', ['reporte' => $this->reporte_id]));

    }

    public function mount(){
        $this->producto = ReporteFaltante::find($this->reporte_id)
        ->productos()
        ->where('productos.id', $this->producto_id)
        ->first();
    }

    

};
?>

<tr wire:key="$producto->id"
    class="border-b hover:bg-gray-50"
>   
    <td id=nombre class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
        {{$this->producto->producto}}
    </td>
    <td class="px-6 py-4">
        {{$this->producto->unidad}}
    </td>
    <td class="px-6 py-4">
        {{$this->producto->maximo}}
    </td>
    <td class="px-6 py-4">
        {{$this->producto->pivot->existencia}}
    </td>
    <td class="px-6 py-4">
        {{$this->producto->pivot->pedir_modificado}}
    </td>

    <td class="px-6 py-4">
        {{$this->producto->precio_venta}}
    </td>

    <td class="px-6 py-4">
        <input type="number" step="0.01" value="{{$this->producto->precio_proveedor}}">
    </td>
    
    <!-- ACCIONES -->
    <td class="px-6 py-4">
        @if(auth()->user()->isAdmin())
            <div class="flex justify-end items-center gap-4 text-sm">

                <!-- Ver productos -->
                <a
                    href="{{-- --}}"
                    class="text-blue-600 hover:underline"
                >
                    Incluir
                </a>

                <!-- Eliminar -->
                <button 
                    onclick="confirm('Se eliminará el producto del reporte. Esta acción no se puede deshacer ¿Continuar?')
                    || event.stopImmediatePropagation()"
                    wire:click="eliminar_producto_reporte"
                    class="text-red-600 hover:underline">
                    Eliminar
                </button>
            </div>
        @endif
    </td>
</tr>