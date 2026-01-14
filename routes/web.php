<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ProductoController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\EmpleadoMiddleware;

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
        Route::get('nuevo_producto', 'create')->name('nuevo_producto');
        Route::post('guardar_producto', 'store')->name('guardar_producto');

    });
    
    Route::middleware(EmpleadoMiddleware::class)->group(function(){});
    

    
});

require __DIR__.'/auth.php';
