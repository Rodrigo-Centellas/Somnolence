<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MapController;

Route::middleware('api')->get('/gps-tracker', [MapController::class, 'apiGpsData']);
