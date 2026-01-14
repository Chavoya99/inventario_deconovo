<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {   
        
        $productos = Producto::orderBy('proveedor')->orderBy('producto')->get();
        return view('lista_productos', compact('productos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('nuevo_producto');
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
                'stock' => 'required|integer|min:0',
                'stock_max' => 'required|integer|min:1',
                'precio' => 'required',
            ]
        );
        //dd($request);
        $pedir = $request->stock_max - $request->stock;
        $producto = Producto::create([
            'producto' => $request->nombre,
            'proveedor' => $request->proveedor,
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
        return view('editar_producto', compact('producto'));
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
                'stock' => 'required|integer|min:0',
                'stock_max' => 'required|integer|min:1',
                'precio' => 'required',
            ]
        );

        $pedir = $request->stock_max - $request->stock;
        $producto->update([
            'producto' => $request->nombre,
            'proveedor' => $request->proveedor,
            'existencia' => $request->stock,
            'maximo' => $request->stock_max,
            'pedir' => $pedir,
            'precio_venta' => $request->precio,
        ]);

        return redirect()->route('lista_productos')->with('success', 'Producto actualizado con éxito');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Producto $producto)
    {
        $producto->delete();
        return redirect()->route('lista_productos')->with('success', 'Producto eliminado con éxito');
    }
}
