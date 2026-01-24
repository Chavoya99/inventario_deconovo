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
    public function index(Request $request)
    {   
        if($request->proveedor){
            $proveedor = Proveedor::find($request->proveedor);
            $ordenes_compra = $proveedor->ordenes_compra; 
        }else{
            $ordenes_compra = OrdenCompra::orderBy('id')->get();
        }

        $proveedores = Proveedor::orderBy('nombre')->get();
       
        return view('lista_ordenes_compra', compact('proveedores', 'ordenes_compra'));
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

        $producto_aux = Producto::find($productos[0]['id']);
        $proveedor = Proveedor::find($producto_aux->proveedor_id);
        $folio_orden = OrdenCompra::orderBy('id', 'desc')->first();

        $nombre_archivo = 'orden_compra_'.strtolower($proveedor->nombre). now('America/Belize')->format('_d_m_Y') . '.pdf';

        $pdf = Pdf::loadView('formato_orden_compra', compact('productos'))
                ->setPaper('A4', 'portrait');

        $ruta = 'ordenes_compra/' . $nombre_archivo;

        Storage::disk('public')->put($ruta, $pdf->output());

        $orden_compra = OrdenCompra::create([
            'proveedor_id' => $producto_aux->proveedor_id,
            'fecha_generada' => now('America/Belize'),
            'ruta_archivo' => $ruta,
        ]);

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
            'orden_compra_'.strtolower($proveedor->nombre).$orden->fecha_generada->format('_d_m_Y') . '.pdf');
        }else{
            return back()->withErrors(['error' => 'El archivo no existe']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(OrdenCompra $ordenCompra)
    {
        $productos = Producto::all();
        $productos = $productos->toArray();


        return view('formato_orden_compra', compact('productos'));
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OrdenCompra $ordenCompra)
    {
        $ordenCompra->delete();
        return redirect()->back()->with('success', 'Orden de compra eliminada con éxito');
    }

    public function index_reporte(Request $request){
        $proveedores = Proveedor::whereHas('productos')->with('productos')->orderBy('nombre')->get();
        return view('generar_reporte_inventario', ['proveedores' => $proveedores, 
        'proveedorActivo' => $request->proveedor_id ?? $proveedores->first()?->id,]);
    }

    public function filtro_orden_proveedor(Request $request){
        $proveedor = Proveedor::find($request->proveedor);
        $proveedores = Proveedor::all();
        $ordenes_compra = $proveedor->ordenes_compra;
        return view('filtro_orden_proveedor', compact('proveedores', 'proveedor', 'ordenes_compra'));
    }
}
