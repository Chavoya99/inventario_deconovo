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
            $productos = $proveedor->productos()->where('recubrimiento', 0)->with('proveedor')->paginate(10)->withQueryString();
        }else if($request->filled('busqueda')){
            $productos = Producto::with('proveedor')->orderBy('producto')
            ->where('producto', 'LIKE', '%'.$request->busqueda.'%')->where('recubrimiento', 0)->paginate(10)->withQueryString();
        }
        else{
            $productos = Producto::with('proveedor')->where('recubrimiento', 0)->orderBy('producto')->paginate(10);
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
        $ruta_guardar = 'guardar_producto';
        $ruta_anterior = 'lista_productos';
        return view('nuevo_producto', compact('proveedores', 'ruta_anterior', 'ruta_guardar'));
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
                'unidad' => 'required|in:Caja,Pza,Bulto por saco,Tarima',
                'stock' => 'required|integer|min:0',
                'stock_max' => 'required|integer|min:1',
                'precio_proveedor' => 'required|min:0.01|numeric',
                'utilidad' => 'required|integer|min:1|max:99',
            ]
        );

        $pedir = $request->stock_max - $request->stock;
        $producto = Producto::create([
            'producto' => $request->nombre,
            'proveedor_id' => $request->proveedor,
            'unidad' => $request->unidad,
            'existencia' => $request->stock,
            'maximo' => $request->stock_max,
            'utilidad' => $request->utilidad,
            'pedir' => $pedir,
            'precio_proveedor' => $request->precio_proveedor,
            'precio_venta' => $this->obtenerPrecioVenta($request->precio_proveedor, $request->utilidad),
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
        $ruta_guardar = 'editar_producto';
        $ruta_anterior = 'lista_productos';
        return view('editar_producto', compact('producto', 'proveedores','ruta_guardar','ruta_anterior'));
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
                'unidad' => 'required|in:Caja,Pza,Bulto por saco,Tarima',
                'stock' => 'required|integer|min:0',
                'stock_max' => 'required|integer|min:1',
                'utilidad' => 'required|integer|min:1|max:99',
                'precio_proveedor' => 'required|numeric|min:0.01',
            ]
        );

        $pedir = $request->stock_max - $request->stock;
        $producto->update([
            'producto' => $request->nombre,
            'proveedor_id' => $request->proveedor,
            'unidad' => $request->unidad,
            'existencia' => $request->stock,
            'utilidad' => $request->utilidad,
            'maximo' => $request->stock_max,
            'pedir' => $pedir,
            'precio_proveedor' => $request->precio_proveedor,
            'precio_venta' => $this->obtenerPrecioVenta($request->precio_proveedor, $request->utilidad),
        ]);

        return redirect()->back()->with('success', 'Producto actualizado correctamente');
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

    public function obtenerPrecioVenta($precio_proveedor, $utilidad){
        return ($precio_proveedor) / (1-($utilidad / 100));
    }
}
