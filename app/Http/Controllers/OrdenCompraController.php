<?php

namespace App\Http\Controllers;

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
    public function index(Request $request)
    {   

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


        return view('lista_ordenes_compra', compact('ordenes_compra', 'proveedores', 'nombre_proveedor_actual'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
