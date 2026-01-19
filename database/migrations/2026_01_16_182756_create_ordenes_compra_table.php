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
        Schema::create('ordenes_compra', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proveedor_id')->constrained()->restrictOnDelete();
            $table->timestamp('fecha_generada');
            $table->boolean('realizada')->default(false);
            $table->timestamp('fecha_realizada')->nullable();
            $table->boolean('recibida')->default(false);
            $table->timestamp('fecha_recibida')->nullable();
            $table->string('ruta_archivo', 2048);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ordenes_compra');
    }
};
