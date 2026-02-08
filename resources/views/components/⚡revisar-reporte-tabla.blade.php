<?php

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\ReporteFaltante;

new class extends Component
{
    public $productos, $reporte, $reporte_id;

    //protected $listeners = ['productoEliminado'];

    public function mount($reporte_id)
    {   
        $this->reporte = ReporteFaltante::with('productos')->find($reporte_id);
        $this->productos = $this->reporte->productos;
    }

    #[On('productoEliminado')]
    public function productoEliminado(){
        // $this->refreshComponent();
        // $this->reporte = ReporteFaltante::with('productos')->find($this->reporte_id);
        // $this->productos = $this->reporte
        // ->productos()
        // ->withPivot('existencia')
        // ->get();
        $this->reporte = ReporteFaltante::with([
        'productos' => function ($q) {
            $q->withPivot('existencia', 'pedir_modificado');
        }
    ])->find($this->reporte_id);

    $this->productos = $this->reporte->productos;
    }

    


};
?>

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
            <livewire-reporte-producto-estado :producto_id="$producto->id" :reporte_id="$reporte->id" :producto=$producto />
        @endforeach           
        
    </tbody>
</table>