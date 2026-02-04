<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Proveedor;
use App\Models\OrdenCompra;
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

        $ruta_origen="lista_ordenes_compra_internas";
        $ordenes_compra = OrdenCompra::orderBy('id')
        ->when($request->proveedor, function ($query) use ($request) {
            $query->where('proveedor_id', $request->proveedor);
        })//Filtro por proveedor
        ->when($request->filtro === 'realizadas', function ($query) {
            $query->where('realizada', true);
        })//Filtro realizadas
        ->when($request->filtro === 'recibidas', function ($query) {
            $query->where('recibida', true);
        })//Filtro recibidas
        ->when($request->filtro === 'pendientes', function($query){
            $query->where('realizada', false);
        })
        ->paginate(5)
        ->withQueryString();

        $proveedores = Proveedor::orderBy('nombre')->get();

        $nombre_proveedor_actual = ($request->proveedor) ? Proveedor::find($request->proveedor)->nombre : null;


        return view('lista_ordenes_compra', compact('ordenes_compra', 'proveedores', 'nombre_proveedor_actual', 'ruta_origen'));
    }

    public function index_proveedores(Request $request){

        $ruta_origen="lista_ordenes_compra_proveedores";
        $ordenes_compra = OrdenCompra::orderBy('id')
        ->when($request->proveedor, function ($query) use ($request) {
            $query->where('proveedor_id', $request->proveedor);
        })//Filtro por proveedor
        ->when($request->filtro === 'realizadas', function ($query) {
            $query->where('realizada', true);
        })//Filtro realizadas
        ->when($request->filtro === 'recibidas', function ($query) {
            $query->where('recibida', true);
        })//Filtro recibidas
        ->when($request->filtro === 'pendientes', function($query){
            $query->where('realizada', false);
        })
        ->paginate(5)
        ->withQueryString();

        $proveedores = Proveedor::orderBy('nombre')->get();

        $nombre_proveedor_actual = ($request->proveedor) ? Proveedor::find($request->proveedor)->nombre : null;


        return view('lista_ordenes_compra', compact('ordenes_compra', 'proveedores', 'nombre_proveedor_actual', 'ruta_origen'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function generar_reporte_inventario(Request $request)
    {

        $request->validate(
            [
                'productos' => 'required|array',
                'productos.*.existencia' => 'required|integer|min:0',
                'productos.*.maximo' => 'required|integer|min:1',
            ],
            [
                'productos.*.existencia.required' => 'Debes ingresar la existencia',
                'productos.*.existencia.integer' => 'La existencia debe ser un número',
                'productos.*.existencia.min' => 'La existencia no puede ser negativa',
            ]
        );
        $productos = collect($request->input('productos'))
            ->map(function ($producto) {
                $producto['pedir'] = $producto['maximo'] - $producto['existencia'];
                return $producto;
            })
            ->filter(function ($producto) {
                return $producto['pedir'] > 0;
            })
            ->values();
        
        if($productos->isEmpty()){
            return redirect()->back()->withInput()->with(['error' => "No hay productos suficientes para generar el reporte"]);
        }

        $proveedor = Proveedor::find($request->proveedor);
        $fecha_generada = now('America/Belize');

        foreach($productos as $producto){
            $producto_update = Producto::find($producto['id']);
            $producto_update->update(['existencia' => $producto['existencia'],
            'pedir' => $producto['pedir'], 'ultimo_reporte' => $fecha_generada]);
        }

        $orden_compra = OrdenCompra::create([
            'proveedor_id' => $proveedor->id,
            'fecha_generada' => $fecha_generada,
            'ruta_archivo' => "Sin ruta de archivo",
        ]);

        $nombre = config('app.facturador_nombre');
        $correo = config('app.facturador_correo');
        $nombre_archivo = 'orden_compra_'.str_pad($orden_compra->id, 3, "0", STR_PAD_LEFT)."_".strtolower($proveedor->nombre). $fecha_generada->format('_d_m_Y') . '.pdf';
        $ruta = 'ordenes_compra/' . $nombre_archivo;
        
        $pdf = Pdf::loadView('formato_orden_compra', compact('productos','proveedor','orden_compra','nombre','correo'))
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
        
        $orden_compra->update(['ruta_archivo' => $ruta]);
        
        Storage::disk('public')->put($ruta, $pdf->output());

        return redirect()->route('reporte_inventario')->with('success', 'Reporte generado con éxito');

    }

    public function ver_orden_compra(Request $request){
        $orden = OrdenCompra::find($request->orden);
        if (Storage::disk('public')->exists($orden->ruta_archivo)) {
            return response()->file(storage_path('app/public/'.$orden->ruta_archivo));
        }else{
            return back()->with(['error' => 'El archivo no existe']);
        }
    }

    public function descargar_orden_compra(Request $request){
        $orden = OrdenCompra::find($request->orden);
        $proveedor = Proveedor::find($orden->proveedor_id);
        

        if (Storage::disk('public')->exists($orden->ruta_archivo)) {
            return response()->download(storage_path('app/public/'.$orden->ruta_archivo), 
            'orden_compra_'.str_pad($orden->id, 3, "0", STR_PAD_LEFT)."_".strtolower($proveedor->nombre). $orden->fecha_generada->format('_d_m_Y') . '.pdf');
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

    public function index_reporte(Request $request){
        $proveedores = Proveedor::whereHas('productos')->with('productos')
        ->withMax('ordenes_compra', 'fecha_generada')->orderBy('nombre')->get();

        return view('generar_reporte_inventario', ['proveedores' => $proveedores, 
        'proveedorActivo' => $request->proveedor_id ?? $proveedores->first()?->id,]);
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
}
