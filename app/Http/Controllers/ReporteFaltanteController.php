<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Proveedor;
use Illuminate\Http\Request;
use App\Models\ReporteFaltante;

class ReporteFaltanteController extends Controller
{
    public function index_faltantes(Request $request){

        $proveedores = Proveedor::orderBy('nombre')->get();    

        $reportes = ReporteFaltante::with('proveedor')->orderBy('id')
        ->when($request->proveedor, function ($query) use ($request) {
            $query->where('proveedor_id', $request->proveedor);
        })//Filtro por proveedor
        ->when($request->filtro === 'aprobados', function ($query) {
            $query->where('status', 'aprobado');
        })//Filtro aprobados
        ->when($request->filtro === 'rechazados', function ($query) {
            $query->where('status', 'rechazado');
        })//filtro rechazados
        ->when($request->filtro === 'revision', function ($query) {
            $query->where('status', 'revision');
        })//filtro en revision
        ->paginate(5)
        ->withQueryString();

        
        return view('reportes_faltantes', compact('reportes', 'proveedores'));
    }

    public function index_reporte(Request $request){
        // $proveedores = Proveedor::whereHas('productos')->with('productos', 'reportes_faltantes')
        // ->withMax('ordenes_compra', 'fecha_generada')->orderBy('nombre')->get();

        $proveedores = Proveedor::whereHas('productos', function ($query) {
        $query->where('recubrimiento', false);
        })
        ->with([
            'productos' => function ($query) {
                $query->where('recubrimiento', false);
            },
            'reportes_faltantes'
        ])
        ->withMax([
        'reportes_faltantes' => function ($query) {
            $query->where('de_recubrimiento', 0);
        }
        ], 'fecha_generada')
        ->orderBy('nombre')
        ->get();

        return view('generar_reporte_inventario', ['proveedores' => $proveedores, 
        'proveedorActivo' => $request->proveedor_id ?? $proveedores->first()->id,]);
    }

    public function index_reporte_recubrimientos(Request $request){
        // $proveedores = Proveedor::whereHas('productos')->with('productos', 'reportes_faltantes')
        // ->withMax('ordenes_compra', 'fecha_generada')->orderBy('nombre')->get();

        $proveedores = Proveedor::whereHas('productos', function ($query) {
        $query->where('recubrimiento', true);
        })
        ->with([
            'productos' => function ($query) {
                $query->where('recubrimiento', true);
            },
            'reportes_faltantes'
        ])
        ->withMax([
        'reportes_faltantes' => function ($query) {
            $query->where('de_recubrimiento', 1);
        }
        ], 'fecha_generada')
        ->orderBy('nombre')
        ->get();

        return view('generar_reporte_recubrimientos', ['proveedores' => $proveedores, 
        'proveedorActivo' => $request->proveedor_id ?? $proveedores->first()->id,]);
    }

    public function eliminar_reporte(ReporteFaltante $reporte){
        $reporte->delete();
        return redirect()->back()->with('success', 'Reporte eliminado con éxito');
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
            ->values();

        if($productos->isEmpty()){
            return redirect()->back()->withInput()->with(['error' => "No hay productos suficientes para generar el reporte"]);
        }

        $proveedor = Proveedor::find($request->proveedor);
        $fecha_generada = now('America/Belize');

        $reporte_faltante = ReporteFaltante::create([
            'proveedor_id' => $proveedor->id,
            'fecha_generada' => $fecha_generada,
            'de_recubrimiento' => $request->recubrimiento,
        ]);
 
        foreach($productos as $producto){
            $producto_update = Producto::find($producto['id']);
            $producto_update->update(['existencia' => $producto['existencia'],
            'pedir' => $producto['pedir'], 'ultimo_reporte' => $fecha_generada]);
            $reporte_faltante->productos()->attach($producto_update->id,['existencia' => $producto['existencia'],
            'pedir_registrado' => $producto['pedir'], 'pedir_modificado' => $producto['pedir'],
            'incluir' => ($producto['pedir'] != 0) ? 1 : 0]);
        }

        return redirect(url()->previous())->with('success', 'Reporte generado con éxito');

    }

    public function revisar_reporte(Request $request){
        $reporte = ReporteFaltante::find($request->reporte);
        
        $proveedor = Proveedor::find($reporte->proveedor_id);
        
        //Filtrar productos con columna incluir igual a 1
        $productos = $reporte->productos->filter(function ($producto){
            return $producto->pivot->incluir != 0 && $producto->pivot->registrado == 0;
        });

        //Filtrar productos con columna incluir igual a 0
        $productos_cero = $reporte->productos->filter(function ($producto){
            return $producto->pivot->incluir == 0;
        });

        return view('revisar_reporte_faltante', compact('productos', 'reporte', 'proveedor', 'productos_cero'));
    }

    public function detalles_reporte(Request $request){
        $reporte = ReporteFaltante::find($request->reporte);
        $proveedor = Proveedor::find($reporte->proveedor_id);
        
        $productos = $reporte->productos;

        return view('detalles_reporte_faltante', compact('productos', 'reporte', 'proveedor'));
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ReporteFaltante $reporteFaltante)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ReporteFaltante $reporteFaltante)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ReporteFaltante $reporteFaltante)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ReporteFaltante $reporteFaltante)
    {
        //
    }
}
