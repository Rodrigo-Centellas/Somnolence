<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\RutaController;
use App\Http\Controllers\StopController;
use App\Http\Controllers\TripController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VehicleController;
use App\Models\Stop;

Route::get('/', [AuthController::class, 'ShowLogin'])->name('login'); // welcome.blade.php
Route::post('/inicio', [AuthController::class, 'login'])->name('login.attempt');
Route::get('/inicio', [AuthController::class, 'inicio'])->middleware('auth')->name('dashboard');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');


// Listado
Route::get   ('/user_index',            [UserController::class,'index'])    ->name('user_index');
// Crear
Route::get   ('/user_create',           [UserController::class,'create'])   ->name('users.create');
Route::post  ('/create',                [UserController::class,'store'])    ->name('users.store');
// Editar
Route::get   ('/user_edit/{user}',      [UserController::class,'edit'])     ->name('users.edit');
Route::patch ('/user_update/{user}',    [UserController::class,'update'])   ->name('users.update');
// Eliminar
Route::delete('/user_delete/{user}',    [UserController::class,'destroy'])  ->name('users.destroy');


//Vehiculo
Route::get('/vehicle_index',          [VehicleController::class,'index'])   ->name('vehicle_index');
Route::get('/vehicle_create',         [VehicleController::class,'create'])  ->name('vehicles.create');
Route::post('/vehicle_store',         [VehicleController::class,'store'])   ->name('vehicles.store');
Route::get('/vehicle_edit/{vehicle}', [VehicleController::class,'edit'])    ->name('vehicles.edit');
Route::put('/vehicle_update/{vehicle}',[VehicleController::class,'update']) ->name('vehicles.update');
Route::delete('/vehicle_destroy/{vehicle}',[VehicleController::class,'destroy']) ->name('vehicles.destroy');


Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');


Route::get('/trips',                 [TripController::class,'index'])   ->name('trips.index');
Route::get('/trips/create',          [TripController::class,'create'])  ->name('trips.create');
Route::post('/trips',                [TripController::class,'store'])   ->name('trips.store');
Route::get('/trips/{trip}/edit',     [TripController::class,'edit'])    ->name('trips.edit');
Route::put('/trips/{trip}',          [TripController::class,'update'])  ->name('trips.update');
Route::delete('/trips/{trip}',       [TripController::class,'destroy']) ->name('trips.destroy');



Route::get('/rutas',               [RutaController::class, 'index'])->name('rutas.index');
Route::get('/rutas/create',       [RutaController::class, 'create'])->name('rutas.create');
Route::post('/rutas',             [RutaController::class, 'store'])->name('rutas.store');
Route::get('/rutas/{ruta}/edit',  [RutaController::class, 'edit'])->name('rutas.edit');
Route::put('/rutas/{ruta}',       [RutaController::class, 'update'])->name('rutas.update');
Route::delete('/rutas/{ruta}',    [RutaController::class, 'destroy'])->name('rutas.destroy');

Route::resource('stops', StopController::class);

Route::get('/mapa', [MapController::class, 'index'])->name('map.index');
Route::get('/api/gps-tracker', [MapController::class, 'apiGpsData'])->name('map.api');


Route::get('/reportes/viajes/excel', [ReporteController::class, 'exportExcel'])->name('reportes.viajes.excel');

Route::get('/reportes', [ReporteController::class, 'index'])->name('reportes.viajes.index');
Route::get('/reportes/viajes/excel', [ReporteController::class, 'exportExcel'])->name('reportes.viajes.excel');
Route::get('/reportes/viajes/pdf', [ReporteController::class, 'exportPdf'])->name('reportes.viajes.pdf');
