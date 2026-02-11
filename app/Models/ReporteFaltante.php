<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReporteFaltante extends Model
{
    use HasFactory;
    protected $table="reportes_faltantes",

    $fillable = ['proveedor_id', 'fecha_generada', 'status'],

    $casts = ['fecha_generada'=> 'datetime'];


    public function proveedor(){
        return $this->belongsTo(Proveedor::class);
    }

    public function productos()
    {
        return $this->belongsToMany(Producto::class, 'reportes_productos', 'reporte_id', 'producto_id')
            ->withPivot(['existencia', 'pedir_registrado', 'pedir_modificado', 'registrado', 'incluir']);
    }

    public function status(){

        return match ($this->status) {
        'revision' => 'Revisión <i class="fa-solid fa-magnifying-glass text-yellow-500"></i>',
        'aprobado' => 'Aprobado <i class="fa-solid fa-circle-check text-green-600"></i>',
        'rechazado' => 'Rechazado <i class="fa-solid fa-circle-xmark text-red-600"></i>',
        default => 'Desconocido <i class="fa-solid fa-question text-gray-400"></i>',
    };

        
    }
}
