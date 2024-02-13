<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\GifController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/login', [LoginController::class, 'login'])->middleware('logger');

Route::middleware(['auth:api', 'logger'])->group(function () {
    Route::get('/gif/search', [GifController::class, 'search']);
    Route::get('/gif/{id}', [GifController::class, 'show']);
    Route::post('/gif/save', [GifController::class, 'save']);
});
