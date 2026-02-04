<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\EmpleadoMiddleware;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\OrdenCompraController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    // Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    // Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::middleware(AdminMiddleware::class)->group(function(){});
    Route::controller(ProductoController::class)->group(function(){
        Route::get('lista_productos', 'index')->name('lista_productos');
        Route::get('nuevo_producto', 'create')->name('nuevo_producto')->middleware(AdminMiddleware::class);
        Route::post('guardar_producto', 'store')->name('guardar_producto')->middleware(AdminMiddleware::class);
        Route::get('editar_producto/{producto}', 'edit')->name('editar_producto')->middleware(AdminMiddleware::class);
        Route::post('editar_producto/{producto}', 'update')->name('editar_producto')->middleware(AdminMiddleware::class);
        Route::delete('eliminar_producto/{producto}', 'destroy')->name('eliminar_producto')->middleware(AdminMiddleware::class);
        Route::get('productos_proveedor', 'filtro_proveedor')->name('filtro_proveedor');
    });

    Route::controller(ProveedorController::class)->group(function(){
        Route::get('lista_proveedores', 'index')->name('lista_proveedores')->middleware(AdminMiddleware::class);
        Route::post('guardar_proveedor', 'store')->name('guardar_proveedor')->middleware(AdminMiddleware::class);
        Route::put('editar_proveedor/{proveedor}', 'update')->name('editar_proveedor')->middleware(AdminMiddleware::class);
        Route::delete('eliminar_proveedor/{proveedor}', 'destroy')->name('eliminar_proveedor')->middleware(AdminMiddleware::class);
    });

    Route::controller(OrdenCompraController::class)->group(function(){
        Route::get('lista_ordenes_compra', 'index')->name('lista_ordenes_compra')->middleware(AdminMiddleware::class);
        Route::get('reporte_inventario', 'index_reporte')->name('reporte_inventario');
        Route::post('generar_reporte_inventario', 'generar_reporte_inventario')->name('generar_reporte_inventario');
        Route::get('ver_orden_compra', 'ver_orden_compra')->name('ver_orden_compra')->middleware(AdminMiddleware::class);
        Route::get('descargar_orden_compra', 'descargar_orden_compra')->name('descargar_orden_compra')->middleware(AdminMiddleware::class);
        Route::get('show', 'show');
        Route::delete('eliminar_orden_compra/{ordenCompra}', 'destroy')->name('eliminar_orden_compra')->middleware(AdminMiddleware::class);
    });
            
});

require __DIR__.'/auth.php';
