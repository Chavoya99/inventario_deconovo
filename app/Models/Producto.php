<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;
    protected $fillable = [
            'producto',
            'proveedor',
            'unidad',
            'existencia',
            'maximo',
            'pedir',
            'proveedor_id',
            'precio_venta',
            'ultimo_reporte'];

    public $timestamps = false;
    protected $casts = ['ultimo_reporte' => 'datetime:d-m-Y H:i:s'];

    public function proveedor(){
        return $this->belongsTo(Proveedor::class);
    }

    public function productos()
    {
        return $this->belongsToMany(Producto::class, 'reportes_productos')
            ->withPivot(['existencia']);
    }
}
