<?php

use Livewire\Component;
use App\Models\ReporteFaltante;

new class extends Component
{
    public $reporte, $reporte_id;
    public $precios = [], $productos = [], $productos_cero = [], $nombres_productos = [];
    public $pedir_modificado = [];
    public $seleccionados = [];
    public $precios_venta = [];
    public $producto_utilidad = [];

    public function mount()
    {
        $this->cargarProductos();

        foreach ($this->productos as $index => $producto) {

            $this->precios[$producto->id] =
            old("productos.$index.precio_proveedor", $producto->precio_proveedor);

            $this->precios_venta[$producto->id] =
                old("productos.$index.precio_venta", $producto->precio_venta);

            $this->pedir_modificado[$producto->id] =
                old("productos.$index.pedir", $producto->pivot->pedir_modificado);

            $this->nombres_productos[$producto->id] =
                old("productos.$index.producto", $producto->producto);

            $this->producto_utilidad[$producto->id] =
                old("productos.$index.utilidad", $producto->utilidad);

            $this->seleccionados[$producto->id] =
            old("productos.$index.seleccionado",
                isset($this->seleccionados[$producto->id])
                    ? $this->seleccionados[$producto->id]
                    : false
            );
        }

        foreach ($this->productos_cero as $producto) {
            $this->precios[$producto->id] = $producto->precio_proveedor;
            $this->precios_venta[$producto->id] = $producto->precio_venta;
            $this->pedir_modificado[$producto->id] = $producto->pivot->pedir_modificado;
            $this->nombres_productos[$producto->id] = $producto->producto;
            $this->producto_utilidad[$producto->id] = $producto->utilidad;
        }
    }

    public function cargarProductos()
    {
        //$reporte = ReporteFaltante::find($this->reporte_id);
            
        //Filtrar productos con columna incluir igual a 1
        $this->productos = $this->reporte->productos->filter(function ($producto){
            return $producto->pivot->incluir != 0;
        })->sortBy(function($producto){
            return $producto->producto && $producto->pivot->registrado;
        });
        
        //Filtrar productos con columna incluir igual a 0
        $this->productos_cero = $this->reporte->productos->filter(function ($producto){
            return $producto->pivot->incluir == 0;
        })->sortByDesc(function($producto){
            return $producto->producto && $producto->pivot->pedir_registrado;
        });

    }

    public function incluir_producto_reporte($producto_id)
    {   
        $producto = $this->reporte->productos()->where('producto_id', $producto_id)->first();
        $producto->reportes()->where('reporte_id', $this->reporte_id)->update(['pedir_modificado' => $producto->pivot->pedir_registrado,'incluir' => 1]);

        $this->precios[$producto_id] = $producto->precio_proveedor;
        $this->precios_venta[$producto_id] = $producto->precio_venta;
        $this->pedir_modificado[$producto_id] = $producto->pivot->pedir_modificado;
        $this->nombres_productos[$producto->id] = $producto->producto;
        $this->producto_utilidad[$producto->id] = $producto->utilidad;

        $this->cargarProductos();
    }

    public function eliminar_producto_reporte($producto_id)
    {   
        if (count($this->productos) <= 1) {

            $this->dispatch('error',
                message: 'No puedes eliminar el último producto del reporte.'
            );
            $this->cargarProductos();
            return;
        }else{
            $producto = $this->reporte->productos()->where('producto_id', $producto_id)->first();
            $producto->reportes()->where('reporte_id', $this->reporte_id)->update(['incluir' => 0]);
            
            unset($this->precios[$producto_id]);
            unset($this->precios_venta[$producto_id]);
            unset($this->pedir_modificado[$producto_id]);
            unset($this->seleccionados[$producto_id]);
            unset($this->nombres_productos[$producto->id]);
            unset($this->producto_utilidad[$producto->id]);

            $this->cargarProductos();
        }
        
    }

};
?>
<div>
    <h3 class="font-semibold text-2xl text-gray-800 leading-tight text-center ">
        {{ __('Incluir') }}
    </h3>
    <div class="overflow-x-auto bg-white rounded-lg shadow border border-gray-200">
        
        <table class="w-full text-sm text-left text-gray-700">
                <thead class="bg-sky-400 text-black border-b">
                    <tr>
                        <th class="px-6 py-3 font-medium">Producto</th>
                        {{--<th class="px-6 py-3 font-medium">Unidad</th>--}}
                        @if($reporte->de_recubrimiento == 1 ) 
                            <th class="px-6 py-3 font-medium">Contenido</th>
                        @endif
                        <th class="px-6 py-3 font-medium">Máximo</th>
                        <th class="px-6 py-3 font-medium">Existencia</th>
                        <th class="px-6 py-3 font-medium">Pedir</th>
                        <th class="px-6 py-3 font-medium">Utilidad</th>
                        <th class="px-6 py-3 font-medium">Precio proveedor</th>
                        <th class="px-6 py-3 font-medium">Precio venta</th>
                        @if (auth()->user()->isAdmin())
                            <th class="px-6 py-3 text-right font-medium">Acciones</th>
                        @endif
                    </tr>
                </thead>
                
                <tbody>
                
                @foreach ($productos as $index => $producto)
                
                    <tr 
                        wire:key="producto-{{ $producto->id }}" 
                        class="border-b hover:bg-gray-50"
                    >
                        @if($producto->pivot->registrado != 1 && $reporte->status == 'aprobado' )
                            <td class="px-6 py-4 font-medium text-gray-900 min-w-[400px]">
                        <!--Nombre producto-->
                                <input type="hidden" name="productos[{{$index}}][id]" value="{{$producto->id}}" required>
                                <input  
                                    wire:model.defer="nombres_productos.{{ $producto->id }}" 
                                    wire:key="producto-{{ $producto->id }}" 
                                    type="text" 
                                    class="w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm"
                                    id="nombre_producto_{{$index}}"
                                    name="productos[{{$index}}][producto]" @required(isset($seleccionados[$producto->id]) && $seleccionados[$producto->id])>
                                    <x-input-error 
                                    :messages="$errors->get('productos.' . $index . '.producto')" 
                                    class="mt-1" />
                                
                            </td>
                        @else
                            <td class="px-6 py-4">
                                {{$producto->producto}}   
                            </td>
                        @endif
                        
                        {{--<td class="px-6 py-4">--}}
                            @if($producto->pivot->registrado != 1 && $reporte->status == 'aprobado' )
                                <input type="hidden" name="productos[{{$index}}][unidad]" value="{{$producto->unidad}}">
                                
                            @endif
                            {{--$producto->unidad--}}
                        {{--</td>--}}

                        <!--Contenido-->
                        <input type="hidden" value="{{$producto->contenido}}" class="contenido-input">
                        @if($reporte->de_recubrimiento == 1 )
                            <td class="px-6 py-4">
                                
                                {{$producto->contenido}}
                            </td>
                         @endif
                        <td class="px-6 py-4">
                            {{$producto->maximo}}
                        </td>
                        <td class="px-6 py-4">
                            {{$producto->pivot->existencia}}
                        </td>
                        
                        @if($producto->pivot->registrado != 1 && $reporte->status == 'aprobado' )
                        <!--Pedir-->
                            <td class="px-6 py-4 min-w-[120px]">
                                <input  wire:model.defer="pedir_modificado.{{ $producto->id }}" 
                                wire:key="producto-{{ $producto->id }}" 
                                type="number" step="1" min="1" value="{{$producto->pivot->pedir_modificado}}"
                                class="w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                                max="{{$producto->maximo - $producto->pivot->existencia}}"
                                id="pedir_producto_{{$index}}"
                                name="productos[{{$index}}][pedir]" @required(isset($seleccionados[$producto->id]) && $seleccionados[$producto->id])>
                                <x-input-error 
                                :messages="$errors->get('productos.' . $index . '.pedir')" 
                                class="mt-1" />
                                
                            </td>
                        @endif

                        @if($producto->pivot->registrado != 1 && $reporte->status == 'aprobado' )
                            <!--Utilidad-->
                            <td class="px-6 py-4 min-w-[110px]">
                                <input  wire:model.defer="producto_utilidad.{{ $producto->id }}" 
                                wire:key="producto-{{ $producto->id }}" 
                                type="number" step="1" min="1" value="{{$producto->utilidad}}"
                                class="w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 utilidad-input"
                                max="99"
                                id="producto_utilidad_{{$index}}"
                                name="productos[{{$index}}][utilidad]" @required(isset($seleccionados[$producto->id]) && $seleccionados[$producto->id])>
                                <x-input-error 
                                :messages="$errors->get('productos.' . $index . '.utilidad')" 
                                class="mt-1" />
                                
                            </td>
                        @else
                            <td class="px-6 py-4">
                                {{$producto->utilidad}}
                            </td>
                        @endif

                        @if($producto->pivot->registrado != 1 && $reporte->status == 'aprobado' )
                        <!--Precio proveedor-->
                            <td class="px-6 py-4 min-w-[125px]">
                                <input  wire:model.defer="precios.{{ $producto->id }}" 
                                wire:key="producto-{{ $producto->id }}" 
                                type="number" step="0.01" min="0" value="{{$producto->precio_proveedor}}"
                                class="w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 precio-proveedor-input"
                                max="999999"
                                id="precio_proveedor_{{$index}}"
                                name="productos[{{$index}}][precio_proveedor]" @required(isset($seleccionados[$producto->id]) && $seleccionados[$producto->id]) >
                                <x-input-error 
                                :messages="$errors->get('productos.' . $index . '.precio_proveedor')" 
                                class="mt-1" />
                            </td>

                        <!--Precio venta-->
                            <td class="px-6 py-4 min-w-[150px]">
                                <input name="productos[{{$index}}][precio_venta]" wire:model.defer="precios_venta.{{ $producto->id }}" 
                                wire:key="producto-{{ $producto->id }}" 
                                type="number" step="0.01" min="0" value=""
                                class="w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 precio-venta-input"
                                max="999999"
                                disabled
                                id="precio_venta_{{$index}}">
                                <x-input-error 
                                :messages="$errors->get('productos.' . $index . '.precio_venta')" 
                                class="mt-1" />
                            </td>
                        @else
                            <td class="px-6 py-4">
                                {{$producto->pivot->pedir_modificado}}
                            </td>
                            <td class="px-6 py-4">
                                {{$producto->precio_proveedor}}
                            </td>
                            <td class="px-6 py-4">
                                {{$producto->precio_venta}}
                            </td>

                        @endif
                
                        <!-- ACCIONES -->
                        <td class="px-6 py-4">
                            @if(auth()->user()->isAdmin())
                                <div class="flex justify-end items-center gap-4 text-sm">
                            <!--Checkbox-->
                                    @if($producto->pivot->registrado)
                                        <div class="max-w-sm w-full space-y-3">
                                            <input checked disabled type="checkbox" 
                                            class="shrink-0 size-5 bg-transparent border-line-3 rounded-sm 
                                            shadow-2xs text-primary focus:ring-0 focus:ring-offset-0 
                                            checked:bg-primary-checked checked:bg-green-500 disabled:opacity-50 
                                            disabled:pointer-events-none" id="hs-default-checkbox"
                                            title="Registrado"
                                            >
                                        </div>
                                    @else
                                        <div class="max-w-sm w-full space-y-3">
                                            <input name="productos[{{$index}}][seleccionado]" wire:model="seleccionados.{{ $producto->id }}" type="checkbox" 
                                            class="shrink-0 size-5 bg-transparent border-line-3 rounded-sm 
                                            shadow-2xs text-primary focus:ring-0 focus:ring-offset-0 
                                            checked:bg-primary-checked checked:border-primary-checked 
                                            disabled:opacity-50 disabled:pointer-events-none" id="hs-default-checkboxs"
                                            onchange="campos_requeridos('{{$index}}')"
                                            title="Incluir" @if(old("productos.$index.seleccionado")) checked @endif>
                                        </div>
                                    @endif
                                    

                                    <!-- Eliminar -->
                                    @if ($producto->pivot->registrado == 1)
                                        <i class="fa-solid fa-ban"></i>
                                    @else
                                        <button
                                            type="button"
                                            wire:key="producto-{{ $producto->id }}"
                                            onclick="confirm('¿Estas seguro de continuar?')
                                            || event.stopImmediatePropagation()"
                                            wire:click="eliminar_producto_reporte({{$producto->id}})"
                                            class="text-red-600 hover:text-red-800"
                                            title="Quitar">
                                            <i class="fa-solid fa-xl fa-circle-minus"></i>
                                        </button>
                                    @endif
                                    
                                </div>
                            @endif
                        </td>
                    </tr>
                @endforeach           
                
            </tbody>
            
        </table>
    </div>
    <br>
    <hr class="border-gray-500 dark:border-neutral-500">
    <br>
    <h3 class="font-semibold text-2xl text-gray-800 leading-tight text-center ">
        {{ __('No incluir') }}
    </h3>
    <div class="overflow-x-auto bg-white rounded-lg shadow border border-gray-200">
        <table class="w-full text-sm text-left text-gray-700">

                    <thead class="bg-sky-400 text-black border-b">
                        <tr>
                            <th class="px-6 py-3 font-medium">Producto</th>
                            <th class="px-6 py-3 font-medium">Unidad</th>
                            <th class="px-6 py-3 font-medium">Máximo</th>
                            <th class="px-6 py-3 font-medium">Existencia</th>
                            <th class="px-6 py-3 font-medium">Pedir</th>
                            <th class="px-6 py-3 font-medium">Precio proveedor</th>
                            <th class="px-6 py-3 font-medium">Precio venta</th>
                            @if (auth()->user()->isAdmin())
                                <th class="px-6 py-3 text-right font-medium">Acciones</th>
                            @endif
                            
                        
                        </tr>
                    </thead>
                    
                    <tbody>
                    
                    
                    @foreach ($productos_cero as $producto)
                        <tr 
                            wire:key="producto-{{ $producto->id }}" 
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
                                {{$producto->precio_proveedor}}
                            </td>
                            <td class="px-6 py-4">
                                {{$producto->precio_venta}}
                            </td>

                    
                            <!-- ACCIONES -->
                            <td class="px-6 py-4">
                                @if(auth()->user()->isAdmin())
                                    <div class="flex justify-end items-center gap-4 text-sm">

                                        <!--Incluir -->
                                        @if ($producto->pivot->registrado == 1)
                                            <i class="fa-solid fa-ban"></i>
                                        @else
                                            @if($producto->pivot->pedir_registrado == 0)
                                                <button
                                                    type="button"
                                                    wire:key="producto-{{ $producto->id }}"
                                                    wire:click="incluir_producto_reporte({{$producto->id}})"
                                                    class="text-dark-600 hover:text-dark-800 block mx-auto"
                                                    title="No permitido">
                                                    <i class="fa-solid fa-xl fa-circle-stop"></i>
                                                </button>
                                            @else
                                                <button
                                                    type="button"
                                                    wire:key="producto-{{ $producto->id }}"
                                                    wire:click="incluir_producto_reporte({{$producto->id}})"
                                                    class="text-green-600 hover:text-green-800 block mx-auto"
                                                    title="Agregar">
                                                    <i class="fa-solid fa-xl fa-circle-plus"></i>
                                                </button>
                                            @endif
                                            
                                        @endif
                                        
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                       
                
            </tbody>
            
        </table>
    </div>
</div>