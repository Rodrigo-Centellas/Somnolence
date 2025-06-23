<?php

use App\Http\Controllers\Api\EventApiController;
use App\Http\Controllers\Api\GpsLocationApiController;
use App\Http\Controllers\Api\TripApiController;
use App\Http\Controllers\Api\UserApiController;
use App\Http\Controllers\Api\VehicleApiController;
use Illuminate\Http\Request;
use App\Http\Controllers\MapController;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



Route::middleware('api')->get('/gps-tracker', [MapController::class, 'apiGpsData']);

Route::get('/user-by-ci/{ci}', [UserApiController::class, 'buscarPorCI'])->name('api.user.buscarPorCI');
Route::get('/vehiculos', [VehicleApiController::class, 'index']);
Route::post('/eventos', [EventApiController::class, 'store']);
Route::get('/trip/activo/{userId}', [TripApiController::class, 'getTripActivoPorUsuario']);
Route::post('/gpslocations', [GpsLocationApiController::class, 'store']);
