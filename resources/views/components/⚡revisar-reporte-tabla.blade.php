<?php

use Livewire\Component;
use App\Models\ReporteFaltante;

new class extends Component
{
    public $reporte, $reporte_id;
    public $precios = [], $productos = [], $productos_cero = [], $nombres_productos = [];
    public $pedir_modificado = [];
    public $seleccionados = [];
    public $precios_venta_1 = [],  $precios_venta_2 = [],  $precios_venta_3 = [],  $precios_venta_4 = [];
    public $producto_utilidad_1 = [], $producto_utilidad_2 = [], $producto_utilidad_3 = [], $producto_utilidad_4 = [];
    public $contenido = [];

    public function mount()
    {
        $this->cargarProductos();

        foreach ($this->productos as $index => $producto) {

            $this->precios[$producto->id] =
            old("productos.$index.precio_proveedor", $producto->precio_proveedor);

            $this->precios_venta_1[$producto->id] =
                old("productos.$index.precio_venta_1", $producto->precio_venta_1);

            $this->precios_venta_2[$producto->id] =
                old("productos.$index.precio_venta_2", $producto->precio_venta_2);

            $this->precios_venta_3[$producto->id] =
                old("productos.$index.precio_venta_3", $producto->precio_venta_3);

            $this->precios_venta_4[$producto->id] =
                old("productos.$index.precio_venta_4", $producto->precio_venta_4);

            $this->pedir_modificado[$producto->id] =
                old("productos.$index.pedir", $producto->pivot->pedir_modificado);

            $this->nombres_productos[$producto->id] =
                old("productos.$index.producto", $producto->producto);

            $this->producto_utilidad_1[$producto->id] =
                old("productos.$index.utilidad_1", $producto->utilidad_1);

            $this->producto_utilidad_2[$producto->id] =
                old("productos.$index.utilidad_2", $producto->utilidad_2);

            $this->producto_utilidad_3[$producto->id] =
                old("productos.$index.utilidad_3", $producto->utilidad_3);

            $this->producto_utilidad_4[$producto->id] =
                old("productos.$index.utilidad_4", $producto->utilidad_4);

            $this->contenido[$producto->id] =
                old("productos.$index.contenido", $producto->contenido);

            $this->seleccionados[$producto->id] =
            old("productos.$index.seleccionado",
                isset($this->seleccionados[$producto->id])
                    ? $this->seleccionados[$producto->id]
                    : false
            );
        }

        foreach ($this->productos_cero as $producto) {
            $this->precios[$producto->id] = $producto->precio_proveedor;
            $this->precios_venta_1[$producto->id] = $producto->precio_venta_1;
            $this->precios_venta_2[$producto->id] = $producto->precio_venta_2;
            $this->precios_venta_3[$producto->id] = $producto->precio_venta_3;
            $this->precios_venta_4[$producto->id] = $producto->precio_venta_4;
            $this->pedir_modificado[$producto->id] = $producto->pivot->pedir_modificado;
            $this->nombres_productos[$producto->id] = $producto->producto;
            $this->producto_utilidad_1[$producto->id] = $producto->utilidad_1;
            $this->producto_utilidad_2[$producto->id] = $producto->utilidad_2;
            $this->producto_utilidad_3[$producto->id] = $producto->utilidad_3;
            $this->producto_utilidad_4[$producto->id] = $producto->utilidad_4;
            $this->contenido[$producto->id] = $producto->contenido;
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
        $this->precios_venta_1[$producto_id] = $producto->precio_venta_1;
        $this->precios_venta_2[$producto_id] = $producto->precio_venta_2;
        $this->precios_venta_3[$producto_id] = $producto->precio_venta_3;
        $this->precios_venta_4[$producto_id] = $producto->precio_venta_4;
        $this->pedir_modificado[$producto_id] = $producto->pivot->pedir_modificado;
        $this->nombres_productos[$producto->id] = $producto->producto;
        $this->producto_utilidad_1[$producto->id] = $producto->utilidad_1;
        $this->producto_utilidad_2[$producto->id] = $producto->utilidad_2;
        $this->producto_utilidad_3[$producto->id] = $producto->utilidad_3;
        $this->producto_utilidad_4[$producto->id] = $producto->utilidad_4;
        $this->contenido[$producto->id] = $producto->contenido;

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
            unset($this->precios_venta_1[$producto_id]);
            unset($this->precios_venta_2[$producto_id]);
            unset($this->precios_venta_3[$producto_id]);
            unset($this->precios_venta_4[$producto_id]);
            unset($this->pedir_modificado[$producto_id]);
            unset($this->seleccionados[$producto_id]);
            unset($this->nombres_productos[$producto->id]);
            unset($this->producto_utilidad_1[$producto->id]);
            unset($this->producto_utilidad_2[$producto->id]);
            unset($this->producto_utilidad_3[$producto->id]);
            unset($this->producto_utilidad_4[$producto->id]);
            unset($this->contenido[$producto->id]);

            $this->cargarProductos();
        }
        
    }

};
?>
<div>
    <h3 class="font-semibold text-2xl text-gray-800 leading-tight text-center ">
        {{ __('Incluir') }}
    </h3>
    <div class="overflow-x-auto bg-white rounded-lg shadow border border-gray-200 drag-scroll select-none">
        
        <table class="w-full text-sm text-left text-gray-700">
                <thead class="bg-sky-400 text-black border-b">
                    <tr>
                        <th class="px-6 py-3 font-medium">Producto</th>
                        <th class="px-6 py-3 font-medium">Unidad</th>
                        @if($reporte->de_recubrimiento == 1 ) 
                            
                            <th class="px-6 py-3 font-medium">Contenido</th>
                        @endif

                        @if ($reporte->de_recubrimiento == 0)
                            <th class="px-6 py-3 font-medium">Máximo</th>
                        @endif
                        
                        <th class="px-6 py-3 font-medium">Existencia</th>
                        <th class="px-6 py-3 font-medium">Pedir</th>
                        <th class="px-6 py-3 font-medium">Precio proveedor</th>
                        <th class="px-6 py-3 font-medium">Utilidad 1</th>
                        <th class="px-6 py-3 font-medium">Precio venta 1</th>
                        <th class="px-6 py-3 font-medium">Utilidad 2</th>
                        <th class="px-6 py-3 font-medium">Precio venta 2</th>
                        <th class="px-6 py-3 font-medium">Utilidad 3</th>
                        <th class="px-6 py-3 font-medium">Precio venta 3</th>
                        <th class="px-6 py-3 font-medium">Utilidad 4</th>
                        <th class="px-6 py-3 font-medium">Precio venta 4</th>
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
                                {{$producto->pivot->producto}}   
                            </td>
                        @endif
                        @if($reporte->de_recubrimiento == 1 )
                            @if($producto->pivot->registrado != 1 && $reporte->status == 'aprobado' )

                                <td class="px-6 py-4 min-w-[150px]">
                                    <select name="productos[{{$index}}][unidad]" class="w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                                    id="unidad_{{$index}}">
                                        <option value="Caja" @if(old('unidad') == "Caja" || $producto->unidad == "Caja") selected @endif>Caja</option>
                                        <option value="Pza" @if(old('unidad') == "Pza" || $producto->unidad == "Pza") selected @endif>Pza</option>
                                        <option value="Saco" @if(old('unidad') == "Saco" ||$producto->unidad == "Saco") selected @endif>Saco</option>
                                        <option value="Tarima" @if(old('unidad') == "Tarima" || $producto->unidad == "Tarima") selected @endif>Tarima</option>
                                    </select>
                                </td>
                            @else

                                <td class="px-6 py-4">
                                    {{$producto->pivot->unidad}}
                                </td>
                            @endif
                        @else
                            
                            <input type="hidden" name="productos[{{$index}}][unidad]" value="{{$producto->unidad}}">
                            <td class="px-6 py-4">
                                {{$producto->pivot->unidad}}
                            </td>

                        @endif
                        <!--Contenido-->
                        @if($reporte->de_recubrimiento == 1 )
                            
                            @if($producto->pivot->registrado != 1 && $reporte->status == 'aprobado' )
                            
                                <td class="px-6 py-4 min-w-[125px]">
                                    <input  wire:model.defer="contenido.{{ $producto->id }}" 
                                    
                                    type="number" step="0.01" min="0.01" value="{{$producto->contenido}}"
                                    class="w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 contenido-input"
                                    max="999999"
                                    id="contenido_{{$index}}"
                                    name="productos[{{$index}}][contenido]" @required(isset($seleccionados[$producto->id]) && $seleccionados[$producto->id])>
                                    <x-input-error 
                                    :messages="$errors->get('productos.' . $index . '.contenido')" 
                                    class="mt-1" />
                                    
                                </td>
                            @else
                                <td class="px-6 py-4">
                                    {{$producto->pivot->contenido}}
                                </td>
                            @endif
                        @else
                            <input type="hidden" name="productos[{{$index}}][contenido]" value="{{$producto->contenido}}">   
                        @endif

                        @if ($reporte->de_recubrimiento == 0)
                            <td class="px-6 py-4">
                                {{$producto->maximo}}
                            </td>
                        @endif

                        <td class="px-6 py-4">
                            {{$producto->pivot->existencia}}
                        </td>
                        
                        @if($producto->pivot->registrado != 1 && $reporte->status == 'aprobado' )
                        <!--Pedir-->
                            <td class="px-6 py-4 min-w-[125px]">
                                <input  wire:model.defer="pedir_modificado.{{ $producto->id }}" 
                                
                                type="number" step="1" min="1" value="{{$producto->pivot->pedir_modificado}}"
                                class="w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                                max="999999"
                                id="pedir_producto_{{$index}}"
                                name="productos[{{$index}}][pedir]" @required(isset($seleccionados[$producto->id]) && $seleccionados[$producto->id])>
                                <x-input-error 
                                :messages="$errors->get('productos.' . $index . '.pedir')" 
                                class="mt-1" />
                                
                            </td>
                        @else
                            <td class="px-6 py-4">
                                {{$producto->pivot->pedir_modificado}}
                            </td>
                        @endif

                        @if($producto->pivot->registrado != 1 && $reporte->status == 'aprobado' )
                        <!--Precio proveedor-->
                            <td class="px-6 py-4 min-w-[150px]">
                                <input  wire:model.defer="precios.{{ $producto->id }}" 
                                
                                type="number" step="0.01" min="0" value="{{$producto->precio_proveedor}}"
                                class="w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 precio-proveedor-input"
                                max="999999"
                                id="precio_proveedor_{{$index}}"
                                name="productos[{{$index}}][precio_proveedor]" @required(isset($seleccionados[$producto->id]) && $seleccionados[$producto->id]) >
                                <x-input-error 
                                :messages="$errors->get('productos.' . $index . '.precio_proveedor')" 
                                class="mt-1" />
                            </td>

                        <!--Utilidad 1-->
                            <td class="px-6 py-4 min-w-[110px]">
                                <input  wire:model.defer="producto_utilidad_1.{{ $producto->id }}" 
                                
                                type="number" step="1" min="0" value="{{$producto->utilidad_1}}"
                                class="w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 utilidad-input"
                                max="99"
                                id="producto_utilidad_1_{{$index}}"
                                name="productos[{{$index}}][utilidad_1]" @required(isset($seleccionados[$producto->id]) && $seleccionados[$producto->id])>
                                <x-input-error 
                                :messages="$errors->get('productos.' . $index . '.utilidad_1')" 
                                class="mt-1" />
                                
                            </td>
                        <!--Precio venta 1-->
                            <td class="px-6 py-4 min-w-[150px]">
                                <input name="productos[{{$index}}][precio_venta_1]" wire:model.defer="precios_venta_1.{{ $producto->id }}" 
                                
                                type="number" step="0.01" min="0" value=""
                                class="w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 precio-venta-input"
                                max="999999"
                                disabled
                                id="precio_venta_1_{{$index}}">
                                <x-input-error 
                                :messages="$errors->get('productos.' . $index . '.precio_venta_1')" 
                                class="mt-1" />
                            </td>

                        <!--Utilidad 2-->
                            <td class="px-6 py-4 min-w-[110px]">
                                <input  wire:model.defer="producto_utilidad_2.{{ $producto->id }}" 
                                
                                type="number" step="1" min="0" value="{{$producto->utilidad_2}}"
                                class="w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 utilidad-input"
                                max="99"
                                id="producto_utilidad_2_{{$index}}"
                                name="productos[{{$index}}][utilidad_2]" @required(isset($seleccionados[$producto->id]) && $seleccionados[$producto->id])>
                                <x-input-error 
                                :messages="$errors->get('productos.' . $index . '.utilidad_2')" 
                                class="mt-1" />
                                
                            </td>
                        <!--Precio venta 2-->
                            <td class="px-6 py-4 min-w-[150px]">
                                <input name="productos[{{$index}}][precio_venta_2]" wire:model.defer="precios_venta_2.{{ $producto->id }}" 
                                
                                type="number" step="0.01" min="0" value=""
                                class="w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 precio-venta-input"
                                max="999999"
                                disabled
                                id="precio_venta_2_{{$index}}">
                                <x-input-error 
                                :messages="$errors->get('productos.' . $index . '.precio_venta_2')" 
                                class="mt-1" />
                            </td>

                        <!--Utilidad 3-->
                            <td class="px-6 py-4 min-w-[110px]">
                                <input  wire:model.defer="producto_utilidad_3.{{ $producto->id }}" 
                                 
                                type="number" step="1" min="0" value="{{$producto->utilidad_3}}"
                                class="w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 utilidad-input"
                                max="99"
                                id="producto_utilidad_3_{{$index}}"
                                name="productos[{{$index}}][utilidad_3]" @required(isset($seleccionados[$producto->id]) && $seleccionados[$producto->id])>
                                <x-input-error 
                                :messages="$errors->get('productos.' . $index . '.utilidad_3')" 
                                class="mt-1" />
                                
                            </td>

                        <!--Precio venta 3-->
                            <td class="px-6 py-4 min-w-[150px]">
                                <input name="productos[{{$index}}][precio_venta_3]" wire:model.defer="precios_venta_3.{{ $producto->id }}" 
                                
                                type="number" step="0.01" min="0" value=""
                                class="w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 precio-venta-input"
                                max="999999"
                                disabled
                                id="precio_venta_3_{{$index}}">
                                <x-input-error 
                                :messages="$errors->get('productos.' . $index . '.precio_venta_3')" 
                                class="mt-1" />
                            </td>

                        <!--Utilidad 4-->
                            <td class="px-6 py-4 min-w-[110px]">
                                <input  wire:model.defer="producto_utilidad_4.{{ $producto->id }}" 
                                
                                type="number" step="1" min="0" value="{{$producto->utilidad_4}}"
                                class="w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 utilidad-input"
                                max="99"
                                id="producto_utilidad_4_{{$index}}"
                                name="productos[{{$index}}][utilidad_4]" @required(isset($seleccionados[$producto->id]) && $seleccionados[$producto->id])>
                                <x-input-error 
                                :messages="$errors->get('productos.' . $index . '.utilidad_4')" 
                                class="mt-1" />
                                
                            </td>
                        <!--Precio venta 4-->
                            <td class="px-6 py-4 min-w-[150px]">
                                <input name="productos[{{$index}}][precio_venta_4]" wire:model.defer="precios_venta_4.{{ $producto->id }}" 
                                
                                type="number" step="0.01" min="0" value=""
                                class="w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 precio-venta-input"
                                max="999999"
                                disabled
                                id="precio_venta_4_{{$index}}">
                                <x-input-error 
                                :messages="$errors->get('productos.' . $index . '.precio_venta_4')" 
                                class="mt-1" />
                            </td>
                        @else
                            <td class="px-6 py-4">
                                {{$producto->pivot->precio_proveedor}}
                            </td>

                            <td class="px-6 py-4">
                                {{$producto->pivot->utilidad_1}}
                            </td>
                            <td class="px-6 py-4">
                                {{$producto->pivot->precio_venta_1}}
                            </td>

                            <td class="px-6 py-4">
                                {{$producto->pivot->utilidad_2}}
                            </td>
                            <td class="px-6 py-4">
                                {{$producto->pivot->precio_venta_2}}
                            </td>

                            <td class="px-6 py-4">
                                {{$producto->pivot->utilidad_3}}
                            </td>
                            <td class="px-6 py-4">
                                {{$producto->pivot->precio_venta_3}}
                            </td>

                            <td class="px-6 py-4">
                                {{$producto->pivot->utilidad_4}}
                            </td>
                            <td class="px-6 py-4">
                                {{$producto->pivot->precio_venta_4}}
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
                            @if($reporte->de_recubrimiento == 1 )
                                <th class="px-6 py-3 font-medium">Contenido</th>
                            @endif

                            @if ($reporte->de_recubrimiento == 0)
                                <th class="px-6 py-3 font-medium">Máximo</th>
                            @endif
                            <th class="px-6 py-3 font-medium">Existencia</th>
                            <th class="px-6 py-3 font-medium">Pedir</th>
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
                            @if($reporte->de_recubrimiento == 1 ) 
                                
                                <td class="px-6 py-4">
                                    {{$producto->contenido}}
                                </td>
                            @endif
                            @if($reporte->de_recubrimiento == 0 ) 
                                <td class="px-6 py-4">
                                    {{$producto->maximo}}
                                </td>
                            @endif
                            <td class="px-6 py-4">
                                {{$producto->pivot->existencia}}
                            </td>
                            
                            <td class="px-6 py-4">
                                {{$producto->pivot->pedir_modificado}}
                            </td>
                    
                            <!-- ACCIONES -->
                            <td class="px-6 py-4">
                                @if(auth()->user()->isAdmin())
                                    <div class="flex justify-end gap-4 text-sm">

                                        <button 
                                            type="button"
                                            
                                            wire:click="incluir_producto_reporte({{$producto->id}})"
                                            class="text-green-600 hover:text-green-800 block"
                                            title="Agregar">
                                            <i class="fa-solid fa-xl fa-circle-plus"></i>
                                        </button>
                                            
                                   
                                        
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                       
                
            </tbody>
            
        </table>
    </div>
</div>