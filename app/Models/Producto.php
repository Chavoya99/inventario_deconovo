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
            'precio_proveedor',
            'ultimo_reporte'];

    public $timestamps = false;
    protected $casts = ['ultimo_reporte' => 'datetime:d-m-Y H:i:s'];

    public function proveedor(){
        return $this->belongsTo(Proveedor::class);
    }

    public function reportes()
    {
        return $this->belongsToMany(ReporteFaltante::class, 'reportes_productos', 'producto_id', 'reporte_id')
            ->withPivot(['existencia', 'pedir_registrado', 'pedir_modificado', 'registrado', 'incluir']);
    }

}
