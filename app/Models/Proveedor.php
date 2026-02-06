<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    use HasFactory;

    protected $fillable = ['nombre'];

    public function productos(){
        return $this->hasMany(Producto::class)->orderBy('producto');
    }

    public function ordenes_compra(){
        return $this->hasMany(OrdenCompra::class)->orderBy('id');
    }

    public function reportes_faltantes(){
        return $this->hasMany(ReporteFaltante::class)->orderBy('id');
    }

    public function hasReportes(){
        return count($this->reportes_faltantes) > 0;
    }
}
