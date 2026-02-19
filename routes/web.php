<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\EmpleadoMiddleware;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\OrdenCompraController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\RecubrimientoController;
use App\Http\Controllers\ReporteFaltanteController;

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

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    //Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

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
        Route::get('lista_ordenes_compra_internas', 'index_internas')->name('lista_ordenes_compra_internas');
        Route::get('lista_ordenes_compra_proveedor', 'index_proveedor')->name('lista_ordenes_compra_proveedor')->middleware(AdminMiddleware::class);
        //Route::get('lista_ordenes_compra_proveedores', 'index_proveedores')->name('lista_ordenes_compra_proveedores')->middleware(AdminMiddleware::class);
        Route::get('ver_orden_compra', 'ver_orden_compra')->name('ver_orden_compra');
        Route::get('descargar_orden_compra', 'descargar_orden_compra')->name('descargar_orden_compra');
        Route::get('show', 'show');
        Route::post('generar_orden_compra/{reporte_id}', 'create')->name('generar_orden_compra')->middleware(AdminMiddleware::class);
        Route::delete('eliminar_orden_compra/{ordenCompra}', 'destroy')->name('eliminar_orden_compra')->middleware(AdminMiddleware::class);
    });

    Route::controller(ReporteFaltanteController::class)->group(function(){
        Route::get('reportes_faltantes', 'index_faltantes')->name('reportes_faltantes');
        Route::get('revisar_reporte_faltante', 'revisar_reporte')->name('revisar_reporte')->middleware(AdminMiddleware::class);
        Route::get('detalles_reporte_faltante', 'detalles_reporte')->name('detalles_reporte')->middleware(EmpleadoMiddleware::class);
        Route::delete('eliminar_reporte_faltante/{reporte}', 'eliminar_reporte')->name('eliminar_reporte_faltante')->middleware(AdminMiddleware::class);
        Route::get('reporte_inventario', 'index_reporte')->name('reporte_inventario');
        Route::post('generar_reporte_inventario', 'generar_reporte_inventario')->name('generar_reporte_inventario');
    });

    Route::controller(RecubrimientoController::class)->group(function(){
        Route::get('lista_recubrimientos' , 'index_recubrimientos')->name('lista_recubrimientos');
        Route::get('nuevo_recubrimiento', 'create_recubrimiento')->name('nuevo_recubrimiento')->middleware(AdminMiddleware::class);
        Route::post('guardar_recubrimiento', 'store_recubrimiento')->name('guardar_recubrimiento')->middleware(AdminMiddleware::class);
        Route::get('editar_recubrimiento/{producto}', 'edit_recubrimiento')->name('editar_recubrimiento')->middleware(AdminMiddleware::class);
        Route::post('editar_recubrimiento/{producto}', 'update_recubrimiento')->name('editar_recubrimiento')->middleware(AdminMiddleware::class);
        
    });
 
});

require __DIR__.'/auth.php';
