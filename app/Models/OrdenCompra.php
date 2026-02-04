<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdenCompra extends Model
{
    use HasFactory;
    protected $table = 'ordenes_compra', 

    $fillable = ['proveedor_id', 'fecha_generada', 
    'realizada', 'fecha_realizada',
    'recibida', 'fecha_recibida',
    'ruta_archivo_interna',
    'ruta_archivo_proveedor'],

    $casts = ['fecha_generada' => 'datetime',];


    public function proveedor(){
        return $this->belongsTo(Proveedor::class);
    }

    // public function isRealizada(){
    //     return $this->realizada == 1;
    // }

    // public function isRecibida(){
    //     return $this->recibida == 1;
    // }
}
