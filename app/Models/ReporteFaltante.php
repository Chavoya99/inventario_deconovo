<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReporteFaltante extends Model
{
    use HasFactory;
    protected $table="reportes_faltantes";

    public function proveedor(){
        return $this->belongsTo(Proveedor::class);
    }
}
