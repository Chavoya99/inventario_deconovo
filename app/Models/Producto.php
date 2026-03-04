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
            'utilidad_1',
            'utilidad_2',
            'utilidad_3',
            'utilidad_4',
            'recubrimiento',
            'contenido',
            'maximo',
            'pedir',
            'proveedor_id',
            'precio_venta_1',
            'precio_venta_2',
            'precio_venta_3',
            'precio_venta_4',
            'precio_proveedor',
            'ultimo_reporte',
            'ultima_orden'];

    public $timestamps = false;
    protected $casts = ['ultimo_reporte' => 'datetime:d-m-Y H:i:s',
    'ultima_orden' => 'datetime:d-m-Y H:i:s'];

    public function proveedor(){
        return $this->belongsTo(Proveedor::class);
    }

    public function reportes()
    {
        return $this->belongsToMany(ReporteFaltante::class, 'reportes_productos', 'producto_id', 'reporte_id')
            ->withPivot(['existencia', 'pedir_registrado', 'pedir_modificado', 'registrado', 'incluir', 
            'contenido', 'producto', 'unidad', 'precio_proveedor',
            'utilidad_1', 'utilidad_2', 'utilidad_3', 'utilidad_4', 
            'precio_venta_1', 'precio_venta_2', 'precio_venta_3', 'precio_venta_4']);
    }

}
