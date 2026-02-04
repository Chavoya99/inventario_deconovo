<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReporteFaltante extends Model
{
    use HasFactory;
    protected $table="reportes_faltantes",

    $fillable = ['proveedor_id', 'fecha_generada'],

    $casts = ['fecha_generada'=> 'datetime'];


    public function proveedor(){
        return $this->belongsTo(Proveedor::class);
    }

    public function productos()
    {
        return $this->belongsToMany(Producto::class, 'reportes_productos')
            ->withPivot(['existencia']);
    }
}
