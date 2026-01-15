<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use Illuminate\Http\Request;

class ProveedorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {   
        $proveedores = Proveedor::orderBy('nombre')->get();
        return view('lista_proveedores', compact('proveedores'));
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
        $request->validate(['nombre' => 'required',]);
        

        Proveedor::create(['nombre'=> $request->nombre]);
    
        return redirect()->route('lista_proveedores')->with('success', 'Proveedor guardado con éxito');
    }

    /**
     * Display the specified resource.
     */
    public function show(Proveedor $proveedor)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Proveedor $proveedor)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Proveedor $proveedor)
    {
        $request->validate(['nombre' => 'required']);

        $proveedor->update(['nombre' => $request->nombre]);

        return redirect()->route('lista_proveedores')->with('success', 'Proveedor actualizado con éxito');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Proveedor $proveedor)
    {
        $proveedor->delete();
        return redirect()->route('lista_proveedores')->with('success', 'Proveedor eliminado con éxito');

    }
}
