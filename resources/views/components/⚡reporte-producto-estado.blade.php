<?php

use App\Models\Producto;
use Livewire\Component;

new class extends Component
{
    public Producto $producto;
};
?>

<tr
    class="border-b hover:bg-gray-50"
>
    <td id=nombre class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
        {{$producto->producto}}
    </td>
    <td class="px-6 py-4">
        {{$producto->unidad}}
    </td>
    <td class="px-6 py-4">
        {{$producto->maximo}}
    </td>
    <td class="px-6 py-4">
        {{$producto->pivot->existencia}}
    </td>
    <td class="px-6 py-4">
        {{$producto->pivot->pedir_modificado}}
    </td>

    <td class="px-6 py-4">
        {{$producto->precio_venta}}
    </td>

    <td class="px-6 py-4">
        {{$producto->precio_proveedor}}
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
                <form
                    action="{{----}}"
                    method="POST"
                    onsubmit="return confirm('Se eliminará el reporte ¿Continuar?')"
                >
                    @csrf
                    @method('DELETE')
                    <button class="text-red-600 hover:underline">
                        Eliminar
                    </button>
                </form>

            </div>
        @endif
    </td>
</tr>