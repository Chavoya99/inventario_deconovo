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
            $table->smallInteger('maximo')->default(1);
            $table->smallInteger('existencia')->default(0);
            $table->smallInteger('pedir')->default(0);
            $table->smallInteger('precio_venta')->default(0);
            $table->string('proveedor')->default('general');
            $table->timestamp('ultima_actualizacion')->nullable();
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
