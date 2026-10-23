
<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Cliente\InicioController;
use App\Http\Controllers\Panel\ClienteController;
use App\Http\Controllers\Panel\DashboardController;
use App\Http\Controllers\Panel\DespachanteController;
use App\Http\Controllers\Panel\FichaController;
use App\Http\Controllers\Panel\SeguimientoController;
use App\Http\Controllers\Panel\TransportistaController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Área pública
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get(
    '/login',
    [LoginController::class, 'show']
)->name('login');

Route::post(
    '/login',
    [LoginController::class, 'authenticate']
)->name('login.authenticate');

Route::post(
    '/logout',
    [LoginController::class, 'logout']
)->name('logout');

/*
|--------------------------------------------------------------------------
| Área interna de Translogística
|--------------------------------------------------------------------------
*/

Route::middleware('auth:interno')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Panel principal
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/panel',
        [DashboardController::class, 'index']
    )->name('panel.dashboard');

    /*
    |--------------------------------------------------------------------------
    | Clientes
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/panel/clientes',
        [ClienteController::class, 'index']
    )->name('panel.clientes.index');

    Route::get(
        '/panel/clientes/nuevo',
        [ClienteController::class, 'create']
    )->name('panel.clientes.create');

    Route::post(
        '/panel/clientes',
        [ClienteController::class, 'store']
    )->name('panel.clientes.store');

    Route::get(
        '/panel/clientes/{cliente}/editar',
        [ClienteController::class, 'edit']
    )->name('panel.clientes.edit');

    Route::put(
        '/panel/clientes/{cliente}',
        [ClienteController::class, 'update']
    )->name('panel.clientes.update');

    Route::delete(
        '/panel/clientes/{cliente}',
        [ClienteController::class, 'destroy']
    )->name('panel.clientes.destroy');

    Route::get(
        '/panel/clientes/{cliente}',
        [ClienteController::class, 'show']
    )->name('panel.clientes.show');

    /*
    |--------------------------------------------------------------------------
    | Fichas
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/panel/fichas',
        [FichaController::class, 'index']
    )->name('panel.fichas.index');

    Route::get(
        '/panel/fichas/nueva',
        [FichaController::class, 'create']
    )->name('panel.fichas.create');

    Route::post(
        '/panel/fichas',
        [FichaController::class, 'store']
    )->name('panel.fichas.store');

    Route::get(
        '/panel/fichas/{ficha}/editar',
        [FichaController::class, 'edit']
    )->name('panel.fichas.edit');

    Route::put(
        '/panel/fichas/{ficha}',
        [FichaController::class, 'update']
    )->name('panel.fichas.update');

    Route::delete(
        '/panel/fichas/{ficha}',
        [FichaController::class, 'destroy']
    )->name('panel.fichas.destroy');

    Route::get(
        '/panel/fichas/{ficha}',
        [FichaController::class, 'show']
    )->name('panel.fichas.show');

    /*
    |--------------------------------------------------------------------------
    | Seguimientos
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/panel/seguimientos',
        [SeguimientoController::class, 'index']
    )->name('panel.seguimientos.index');

    Route::get(
        '/panel/seguimientos/{seguimiento}/actualizar',
        [SeguimientoController::class, 'edit']
    )->name('panel.seguimientos.edit');

    Route::post(
        '/panel/seguimientos/{seguimiento}/actualizar',
        [SeguimientoController::class, 'update']
    )->name('panel.seguimientos.update');

    Route::get(
        '/panel/seguimientos/nuevo',
        [SeguimientoController::class, 'create']
    )->name('panel.seguimientos.create');

    Route::post(
        '/panel/seguimientos',
        [SeguimientoController::class, 'store']
    )->name('panel.seguimientos.store');

    Route::delete(
        '/panel/seguimientos/{seguimiento}',
        [SeguimientoController::class, 'destroy']
    )->name('panel.seguimientos.destroy');

    Route::get(
        '/panel/seguimientos/{seguimiento}',
        [SeguimientoController::class, 'show']
    )->name('panel.seguimientos.show');

    /*
    |--------------------------------------------------------------------------
    | Transportistas
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/panel/transportistas',
        [TransportistaController::class, 'index']
    )->name('panel.transportistas.index');

    Route::get(
        '/panel/transportistas/nuevo',
        [TransportistaController::class, 'create']
    )->name('panel.transportistas.create');

    Route::post(
        '/panel/transportistas',
        [TransportistaController::class, 'store']
    )->name('panel.transportistas.store');

    Route::get(
        '/panel/transportistas/{transportista}/editar',
        [TransportistaController::class, 'edit']
    )->name('panel.transportistas.edit');

    Route::put(
        '/panel/transportistas/{transportista}',
        [TransportistaController::class, 'update']
    )->name('panel.transportistas.update');

    Route::delete(
        '/panel/transportistas/{transportista}',
        [TransportistaController::class, 'destroy']
    )->name('panel.transportistas.destroy');

    Route::get(
        '/panel/transportistas/{transportista}',
        [TransportistaController::class, 'show']
    )->name('panel.transportistas.show');

    /*
    |--------------------------------------------------------------------------
    | Despachantes locales
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/panel/despachantes',
        [DespachanteController::class, 'index']
    )->name('panel.despachantes.index');

    Route::get(
        '/panel/despachantes/nuevo',
        [DespachanteController::class, 'create']
    )->name('panel.despachantes.create');

    Route::post(
        '/panel/despachantes',
        [DespachanteController::class, 'store']
    )->name('panel.despachantes.store');

    Route::get(
        '/panel/despachantes/{despachante}/editar',
        [DespachanteController::class, 'edit']
    )->name('panel.despachantes.edit');

    Route::put(
        '/panel/despachantes/{despachante}',
        [DespachanteController::class, 'update']
    )->name('panel.despachantes.update');

    Route::delete(
        '/panel/despachantes/{despachante}',
        [DespachanteController::class, 'destroy']
    )->name('panel.despachantes.destroy');

    Route::get(
        '/panel/despachantes/{despachante}',
        [DespachanteController::class, 'show']
    )->name('panel.despachantes.show');
});

/*
|--------------------------------------------------------------------------
| Área del cliente
|--------------------------------------------------------------------------
*/

Route::middleware('auth:cliente')->group(function () {
    Route::get(
        '/cliente',
        [InicioController::class, 'index']
    )->name('cliente.inicio');

    Route::get(
        '/cliente/cargas',
        [InicioController::class, 'cargas']
    )->name('cliente.cargas.index');

    Route::get(
        '/cliente/buscar',
        [InicioController::class, 'buscar']
    )->name('cliente.cargas.buscar');

    Route::get(
        '/cliente/cargas/{carga}',
        [InicioController::class, 'show']
    )->name('cliente.cargas.show');
});
