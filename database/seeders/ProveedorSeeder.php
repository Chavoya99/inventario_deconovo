<?php

namespace Database\Seeders;

use App\Models\Proveedor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProveedorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $proveedores = ["Perdura", "Pegaduro", "Hydroflud", "Azyco"];

        foreach($proveedores as $proveedor){
            Proveedor::create([
                'nombre' => $proveedor,
            ]);  
        }
    }
}
