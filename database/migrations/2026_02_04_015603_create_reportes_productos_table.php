<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reportes_productos', function (Blueprint $table) {
            $table->foreignId('reporte_id')->constrained('reportes_faltantes')->cascadeOnDelete();
            $table->foreignId('producto_id')->constrained()->restrictOnDelete();
            $table->smallInteger('existencia')->default(0);
            $table->smallInteger('pedir_registrado')->default(0);
            $table->smallInteger('pedir_modificado')->default(0);
            $table->boolean('registrado')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reportes_productos');
    }
};
