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
            'existencia',
            'maximo',
            'pedir',
            'ultima_actualizacion'];

    public $timestamps = false;
}
