<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Proveedor;
use Illuminate\Http\Request;

class RecubrimientoController extends Controller
{
    public function index_recubrimientos(Request $request)
    {    
        $nombre_proveedor_actual = null;
        if($request->filled('proveedor')){
            $proveedor = Proveedor::find($request->proveedor);
            $nombre_proveedor_actual = $proveedor->nombre;
            $productos = $proveedor->productos()->where('recubrimiento', 1)->with('proveedor')->paginate(10)->withQueryString();
        }else if($request->filled('busqueda')){
            $productos = Producto::with('proveedor')->orderBy('producto')
            ->where('producto', 'LIKE', '%'.$request->busqueda.'%')->where('recubrimiento', 1)->paginate(10)->withQueryString();
        }
        else{
            $productos = Producto::with('proveedor')->where('recubrimiento', 1)->orderBy('producto')->paginate(10);
        }
        
        $proveedores = Proveedor::orderBy('nombre')->get();
        return view('lista_productos', compact('productos','proveedores', 'nombre_proveedor_actual'));
    }

    public function create_recubrimiento()
    {
        $proveedores = Proveedor::orderBy('nombre')->get();
        $ruta_guardar = 'guardar_recubrimiento';
        $ruta_anterior = 'lista_recubrimientos';
        return view('nuevo_producto', compact('proveedores','ruta_guardar','ruta_anterior'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store_recubrimiento(Request $request)
    {
        $request->validate(
            [
                'nombre' => 'required',
                'proveedor' => 'required',
                'unidad' => 'required|in:Caja,Pza,Bulto por saco,Tarima',
                'stock' => 'required|integer|min:0',
                'stock_max' => 'required|integer|min:1',
                'precio_proveedor' => 'required|',
                'utilidad' => 'required|max:99|integer|min:1',
                'contenido' => 'required|min:0.01|numeric',
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
            'contenido' => $request->contenido,
            'pedir' => $pedir,
            'recubrimiento' => true,
            'precio_proveedor' => $request->precio_proveedor,
            'precio_venta' => $this->obtenerPrecioVenta($request->precio_proveedor, $request->utilidad, $request->contenido),
        ]);

        return redirect()->route('lista_recubrimientos')->with('success', 'Producto agregado con éxito');
    }

    public function edit_recubrimiento(Producto $producto)
    {       
        $proveedores = Proveedor::orderBy('nombre')->get();
        $ruta_guardar = 'editar_recubrimiento';
        $ruta_anterior = 'lista_recubrimientos';
        return view('editar_producto', compact('producto', 'proveedores', 'ruta_anterior', 'ruta_guardar'));
    }

    public function update_recubrimiento(Request $request, Producto $producto)
    {
        $request->validate(
            [
                'nombre' => 'required',
                'proveedor' => 'required',
                'unidad' => 'required|in:Caja,Pza,Bulto por saco,Tarima',
                'stock' => 'required|integer|min:0',
                'stock_max' => 'required|integer|min:1',
                'precio_proveedor' => 'required|numeric|min:0.01',
                'utilidad' => 'required|integer|min:1|max:99',
                'contenido' => 'required|min:0.01|numeric',
            ]
        );

        $pedir = $request->stock_max - $request->stock;
        $producto->update([
            'producto' => $request->nombre,
            'proveedor_id' => $request->proveedor,
            'unidad' => $request->unidad,
            'existencia' => $request->stock,
            'maximo' => $request->stock_max,
            'utilidad' => $request->utilidad,
            'contenido' => $request->contenido,
            'pedir' => $pedir,
            'recubrimiento' => true,
            'precio_proveedor' => $request->precio_proveedor,
            'precio_venta' => $this->obtenerPrecioVenta($request->precio_proveedor, $request->utilidad, $request->contenido),
        ]);

        return redirect()->back()->with('success', 'Producto actualizado con éxito');
    }

    public function obtenerPrecioVenta($precio_proveedor, $utilidad, $contenido){
        return ($precio_proveedor * $contenido) / (1-($utilidad / 100));
    }


}
