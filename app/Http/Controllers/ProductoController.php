<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Proveedor;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {    
        $nombre_proveedor_actual = null;
        if($request->filled('proveedor')){
            $proveedor = Proveedor::find($request->proveedor);
            $nombre_proveedor_actual = $proveedor->nombre;
            $productos = $proveedor->productos()->paginate(5)->withQueryString();
        }else{
            $productos = Producto::with('proveedor')->orderBy('producto')->paginate(5);
        }
        
        $proveedores = Proveedor::orderBy('nombre')->get();
        return view('lista_productos', compact('productos','proveedores', 'nombre_proveedor_actual'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $proveedores = Proveedor::orderBy('nombre')->get();
        return view('nuevo_producto', compact('proveedores'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'nombre' => 'required',
                'proveedor' => 'required',
                'unidad' => 'required|in:Caja,Pza,Bulto',
                'stock' => 'required|integer|min:0',
                'stock_max' => 'required|integer|min:1',
                'precio' => 'required',
            ]
        );
        //dd($request);
        $pedir = $request->stock_max - $request->stock;
        $producto = Producto::create([
            'producto' => $request->nombre,
            'proveedor_id' => $request->proveedor,
            'unidad' => $request->unidad,
            'existencia' => $request->stock,
            'maximo' => $request->stock_max,
            'pedir' => $pedir,
            'precio_venta' => $request->precio,
        ]);

        return redirect()->route('lista_productos')->with('success', 'Producto agregado con éxito');
    }

    /**
     * Display the specified resource.
     */
    public function show(Producto $producto)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Producto $producto)
    {       
        $proveedores = Proveedor::orderBy('nombre')->get();
        return view('editar_producto', compact('producto', 'proveedores'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Producto $producto)
    {
        $request->validate(
            [
                'nombre' => 'required',
                'proveedor' => 'required',
                'unidad' => 'required|in:Caja,Pza,Bulto',
                'stock' => 'required|integer|min:0',
                'stock_max' => 'required|integer|min:1',
                'precio' => 'required',
            ]
        );

        $pedir = $request->stock_max - $request->stock;
        $producto->update([
            'producto' => $request->nombre,
            'proveedor_id' => $request->proveedor,
            'unidad' => $request->unidad,
            'existencia' => $request->stock,
            'maximo' => $request->stock_max,
            'pedir' => $pedir,
            'precio_venta' => $request->precio,
        ]);

        return redirect($request->redirect_to)->with('success', 'Producto actualizado correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Producto $producto)
    {
        $producto->delete();
        return redirect()->back()->with('success', 'Producto eliminado con éxito');
    }

    public function filtro_proveedor(Request $request){
        $proveedor = Proveedor::find($request->proveedor);
        $proveedores = Proveedor::orderBy('nombre')->get();
        $productos = $proveedor->productos;

        return view('filtro_proveedor', compact('productos', 'proveedor', 'proveedores'));
    }
}
