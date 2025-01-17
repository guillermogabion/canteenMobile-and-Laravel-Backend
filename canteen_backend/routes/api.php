<?php

use App\Http\Controllers\MealsController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\TodayMealController;
use App\Http\Controllers\UsersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/login', [UsersController::class, 'login']);
Route::post('/logout', [UsersController::class, 'logout'])->middleware('auth:api');
Route::post('register', [UsersController::class, 'storeApi']);


Route::middleware('auth:api')->group(function () {
    Route::get('/self', [UsersController::class, 'self']);

    // today 
    Route::get('/get-today', [TodayMealController::class, 'getToday']);

    // order 
    Route::post('/order-store', [OrderController::class, 'storeOrder']);
});
