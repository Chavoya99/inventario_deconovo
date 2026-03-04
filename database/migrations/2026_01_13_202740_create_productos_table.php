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
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string('producto');
            $table->boolean('recubrimiento')->default(false);
            $table->string('unidad');
            $table->decimal('contenido', 8,2)->default(1);
            $table->smallInteger('maximo')->default(1);
            $table->smallInteger('existencia')->default(0);
            $table->smallInteger('pedir')->default(0);
            $table->tinyInteger('utilidad_1')->default(0)->max(99);
            $table->tinyInteger('utilidad_2')->default(0)->max(99);
            $table->tinyInteger('utilidad_3')->default(0)->max(99);
            $table->tinyInteger('utilidad_4')->default(0)->max(99);
            $table->decimal('precio_venta_1', 8, 2)->default(0);
            $table->decimal('precio_venta_2', 8, 2)->default(0);
            $table->decimal('precio_venta_3', 8, 2)->default(0);
            $table->decimal('precio_venta_4', 8, 2)->default(0);
            $table->decimal('precio_proveedor', 8, 2)->default(0);
            $table->foreignId('proveedor_id')->constrained()->onDelete('cascade')->default('general');
            $table->timestamp('ultimo_reporte')->nullable();
            $table->timestamp('ultima_orden')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
