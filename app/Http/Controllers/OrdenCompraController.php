<?php

namespace App\Http\Controllers;

use App\Http\Middleware\AdminMiddleware;
use App\Models\Producto;
use App\Models\Proveedor;
use App\Models\OrdenCompra;
use App\Models\ReporteFaltante;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class OrdenCompraController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index_internas(Request $request)
    {   

        $ordenes_compra = OrdenCompra::with('proveedor')->orderBy('id')
        ->when($request->proveedor, function ($query) use ($request) {
            $query->where('proveedor_id', $request->proveedor);
        })
        ->when($request->filtro === 'parciales', function ($query) {
            $query->where('recibida', 'p');
        })
        ->when($request->filtro === 'recibidas', function ($query) {
            $query->where('recibida', 'r');
        })
        ->when($request->filtro === 'pendientes', function($query){
            $query->where('recibida', 'n');
        })
        
        ->paginate(10)
        ->withQueryString();

        $ruta_origen = 'lista_ordenes_compra_internas';

        $proveedores = Proveedor::orderBy('nombre')->get();

        $nombre_proveedor_actual = ($request->proveedor) ? Proveedor::find($request->proveedor)->nombre : null;

        return view('lista_ordenes_compra', compact('ordenes_compra', 'proveedores', 'nombre_proveedor_actual', 'ruta_origen'));
    }

    public function index_proveedor(Request $request)
    {   
        $ordenes_compra = OrdenCompra::with('proveedor')->orderBy('id')
        ->when($request->proveedor, function ($query) use ($request) {
            $query->where('proveedor_id', $request->proveedor);
        })
        ->when($request->filtro === 'parciales', function ($query) {
            $query->where('recibida', 'p');
        })
        ->when($request->filtro === 'recibidas', function ($query) {
            $query->where('recibida', 'r');
        })
        ->when($request->filtro === 'pendientes', function($query){
            $query->where('recibida', 'n');
        })
        ->when($request->filtro === 'revisadas', function($query){
            $query->where('revisada', true);
        })
        ->when($request->filtro === 'no_revisadas', function($query){
            $query->where('revisada', false);
        })
        ->paginate(10)
        ->withQueryString();

        $ruta_origen = 'lista_ordenes_compra_proveedor';

        $proveedores = Proveedor::orderBy('nombre')->get();

        $nombre_proveedor_actual = ($request->proveedor) ? Proveedor::find($request->proveedor)->nombre : null;

        return view('lista_ordenes_compra', compact('ordenes_compra', 'proveedores', 'nombre_proveedor_actual', 'ruta_origen'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {   

        $request->validate([

            'productos' => ['required','array'],

            'productos.*.id' => [
                'required_if:productos.*.seleccionado,on',
                'exclude_unless:productos.*.seleccionado,1',
                'exists:productos,id'
            ],

            'productos.*.producto' => [
                'required_if:productos.*.seleccionado,on',
                'exclude_unless:productos.*.seleccionado,1',
                'string',
                'max:255'
            ],

            'productos.*.unidad' => [
                'required_if:productos.*.seleccionado,on',
                'exclude_unless:productos.*.seleccionado,1',
                'in:Caja,Pza,Saco,Tarima',
            ],

            'productos.*.contenido' => [
                'required_if:productos.*.seleccionado,on',
                'exclude_unless:productos.*.seleccionado,1',
                'filled',
                'numeric',
                'min:0',
                'max:30000'
            ],

            'productos.*.pedir' => [
                'required_if:productos.*.seleccionado,on',
                'exclude_unless:productos.*.seleccionado,1',
                'integer',
                'min:1',
                'max:30000'
            ],

            'productos.*.precio_proveedor' => [
                'required_if:productos.*.seleccionado,on',
                'exclude_unless:productos.*.seleccionado,1',
                'filled',
                'numeric',
                'min:0.01',
                'max:999999'
            ],

            'productos.*.utilidad_1' => [
                'required_if:productos.*.seleccionado,on',
                'exclude_unless:productos.*.seleccionado,1', 
                'integer', 'min:0', 'max:99'
            ],

            'productos.*.utilidad_2' => [
                'required_if:productos.*.seleccionado,on',
                'exclude_unless:productos.*.seleccionado,1', 
                'integer', 'min:0', 'max:99'
            ],

            'productos.*.utilidad_3' => [
                'required_if:productos.*.seleccionado,on',
                'exclude_unless:productos.*.seleccionado,1', 
                'integer', 'min:0', 'max:99'
            ],

            'productos.*.utilidad_4' => [
                'required_if:productos.*.seleccionado,on',
                'exclude_unless:productos.*.seleccionado,1', 
                'integer', 'min:0', 'max:99'
            ],

        ]);

        $reporte = ReporteFaltante::find($request->reporte_id);
        $proveedor = Proveedor::find($reporte->proveedor_id);
        $fecha_generada = now('America/Belize');
        $productos = collect($request->productos)
            ->filter(fn($producto) => isset($producto['seleccionado']))
            ->map(function ($producto) use ($reporte) {

                //Actualizar los datos en la tabla de reportes_productos   
                $reporte->productos()->where('producto_id', $producto['id'])->where('generico_id', $producto['generico_id'])
                ->update(['registrado' => true, 
                'pedir_modificado' => $producto['pedir'], 
                'reportes_productos.producto' => $producto['producto'],
                'reportes_productos.unidad' => $producto['unidad'],
                'reportes_productos.contenido' => $producto['contenido'],
                'reportes_productos.utilidad_1' => $producto['utilidad_1'],
                'reportes_productos.utilidad_2' => $producto['utilidad_2'],
                'reportes_productos.utilidad_3' => $producto['utilidad_3'],
                'reportes_productos.utilidad_4' => $producto['utilidad_4'],
                'reportes_productos.precio_venta_1' => $this->obtenerPrecioVenta($producto['precio_proveedor'], $producto['utilidad_1'], $producto['contenido']),
                'reportes_productos.precio_venta_2' => $this->obtenerPrecioVenta($producto['precio_proveedor'], $producto['utilidad_2'], $producto['contenido']),
                'reportes_productos.precio_venta_3' => $this->obtenerPrecioVenta($producto['precio_proveedor'], $producto['utilidad_3'], $producto['contenido']),
                'reportes_productos.precio_venta_4' => $this->obtenerPrecioVenta($producto['precio_proveedor'], $producto['utilidad_4'], $producto['contenido']),
                'reportes_productos.precio_proveedor' => $producto['precio_proveedor'],

                ]);

                $generico = Producto::where('producto','RECUBRIMIENTO GENERICO')->first();
                $generico = ($generico) ? $generico->id : 0;
                if($producto['id'] != $generico){
                    //Actualizar el registro de la base de datos del producto
                    $aux = Producto::find($producto['id']);
                    $contenido = $aux->contenido;
                    $aux->update([
                    'producto' => $producto['producto'],
                    'unidad' => $producto['unidad'],
                    'contenido' => $producto['contenido'],
                    'utilidad_1' => $producto['utilidad_1'], 
                    'utilidad_2' => $producto['utilidad_2'], 
                    'utilidad_3' => $producto['utilidad_3'], 
                    'utilidad_4' => $producto['utilidad_4'],
                    'precio_venta_1' => $this->obtenerPrecioVenta($producto['precio_proveedor'], $producto['utilidad_1'], $producto['contenido']),
                    'precio_venta_2' => $this->obtenerPrecioVenta($producto['precio_proveedor'], $producto['utilidad_2'], $producto['contenido']),
                    'precio_venta_3' => $this->obtenerPrecioVenta($producto['precio_proveedor'], $producto['utilidad_3'], $producto['contenido']),
                    'precio_venta_4' => $this->obtenerPrecioVenta($producto['precio_proveedor'], $producto['utilidad_4'], $producto['contenido']),
                    'precio_proveedor' => $producto['precio_proveedor'],
                    'ultima_orden' => now('America/Belize')]); 
                }
                    return [
                        'id' => $producto['id'],
                        'producto' => $producto['producto'],
                        'pedir' => $producto['pedir'],
                        'unidad' => $producto['unidad'],
                        'precio_venta_1' => $this->obtenerPrecioVenta($producto['precio_proveedor'], $producto['utilidad_1'], $producto['contenido']),
                        'precio_venta_2' => $this->obtenerPrecioVenta($producto['precio_proveedor'], $producto['utilidad_2'], $producto['contenido']),
                        'precio_venta_3' => $this->obtenerPrecioVenta($producto['precio_proveedor'], $producto['utilidad_3'], $producto['contenido']),
                        'precio_venta_4' => $this->obtenerPrecioVenta($producto['precio_proveedor'], $producto['utilidad_4'], $producto['contenido']),
                        'precio_proveedor' => $producto['precio_proveedor'],
                    ];
                
                
            } )
            ->values()
            ->toArray();

        if(count($productos) == 0){
            return redirect()->back()->with('error', 'No se puede generar una orden de compra vacía')->withInput();
        }

        $orden_compra = OrdenCompra::create([
            'proveedor_id' => $proveedor->id,
            'fecha_generada' => $fecha_generada,
            'ruta_archivo_interna' => 'Sin ruta',
            'ruta_archivo_proveedor' => 'Sin ruta',
        ]);

        $orden_compra->update([
            'ruta_archivo_interna' => $this->generar_pdf($orden_compra, $productos, 'interna'),
            'ruta_archivo_proveedor' => $this->generar_pdf($orden_compra, $productos, 'proveedor'),
        ]);

        return redirect()->back()->with('success', 'Orden de compra generada con éxito, folio '.str_pad($orden_compra->id, 3, "0", STR_PAD_LEFT));
    }

    public function generar_pdf($orden_compra, $productos, $tipo_orden){
        $proveedor = $orden_compra->proveedor;
        $nombre = config('app.facturador_nombre');
        $correo = config('app.facturador_correo');
        $nombre_archivo = 'orden_compra_'.str_pad($orden_compra->id, 3, "0", STR_PAD_LEFT).
        "_".strtolower($orden_compra->proveedor->nombre). $orden_compra->fecha_generada->format('_d_m_Y')."_".$tipo_orden. '.pdf';
        $ruta = 'ordenes_compra/' . $nombre_archivo;
        
        $pdf = Pdf::loadView('formato_orden_compra', compact('productos','proveedor','orden_compra','nombre','correo', 'tipo_orden'))
                ->setPaper('A4', 'portrait');
        
        $pdf->render();
        
        /** @disregard */
        $pdf->getDomPDF()->get_canvas()->page_text(
            520, 820,              
            "Página {PAGE_NUM} de {PAGE_COUNT}",
            null,                  
            9,                     
            [0, 0, 0]              
        );
        
        Storage::disk('public')->put($ruta, $pdf->output());

        return $ruta;
    }

    public function ver_orden_compra(Request $request){
        
        $orden = OrdenCompra::find($request->orden);

        if($request->tipo == 'interna'){
            $ruta = $orden->ruta_archivo_interna;
        }else if($request->tipo == 'proveedor'){
            if(!auth()->user()->isAdmin()){
                abort(404);
            }
            $ruta = $orden->ruta_archivo_proveedor;
        }else{
            $ruta = null;
        }
        
        if (Storage::disk('public')->exists($ruta)) {
            return response()->file(storage_path('app/public/'.$ruta));
        }else{
            return back()->with(['error' => 'El archivo no existe']);
        }
    }

    public function descargar_orden_compra(Request $request){

        
        $orden = OrdenCompra::find($request->orden);
        $proveedor = Proveedor::find($orden->proveedor_id);
        if($request->tipo == 'interna'){
            $ruta = $orden->ruta_archivo_interna;
        }else if($request->tipo == 'proveedor'){
            $ruta = $orden->ruta_archivo_proveedor;
        }else{
            $ruta = null;
        }
        
        if (Storage::disk('public')->exists($ruta)) {
            return response()->download(storage_path('app/public/'.$ruta), 
            'orden_compra_'.str_pad($orden->id, 3, "0", STR_PAD_LEFT)."_".strtolower($proveedor->nombre). $orden->fecha_generada->format('_d_m_Y_') .$request->tipo .'.pdf');
        }else{
            return back()->withErrors(['error' => 'El archivo no existe']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OrdenCompra $ordenCompra)
    {   
        if ($ordenCompra->ruta_archivo && Storage::disk('public')->exists($ordenCompra->ruta_archivo)) {
            Storage::disk('public')->delete($ordenCompra->ruta_archivo);
        }
        
        $ordenCompra->delete();
        return redirect()->back()->with('success', 'Orden de compra eliminada con éxito');
    }

    /**
     * Display the specified resource.
     */
    public function show(OrdenCompra $ordenCompra)
    {
        $productos = Producto::all();
        $productos = $productos->toArray();
        $proveedor = Proveedor::first();
        $orden_compra = $proveedor->ordenes_compra()->first();
        $nombre = config('app.facturador_nombre');
        $correo = config('app.facturador_correo');

        return view('formato_orden_compra', compact('productos','proveedor','orden_compra','nombre','correo'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(OrdenCompra $ordenCompra)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, OrdenCompra $ordenCompra)
    {
        //
    }

    public function obtenerPrecioVenta($precio_proveedor, $utilidad, $contenido){
        
        $resultado = ceil(($precio_proveedor) / (1-($utilidad / 100))) * $contenido;
        return $resultado;
    }
}
