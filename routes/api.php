<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\ReservationsController;

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

Route::post('/register', [RegisterController::class, 'post']);
Route::post('/login', [LoginController::class, 'post']);
Route::post('/logout', [LogoutController::class, 'post']);
Route::get('/user', [UsersController::class, 'get']);
Route::get('/getShopInfo', [UsersController::class, 'getShopInfo']);
Route::get('/getshops', [UsersController::class, 'getshops']);
Route::put('/user', [UsersController::class, 'put']);
Route::get('/getSlot', [ReservationsController::class, 'getSlot']);
Route::get('/getAvailableTableId', [ReservationsController::class, 'getAvailableTableId']);
Route::get('/confirmDateTime', [ReservationsController::class, 'confirmDateTime']);
Route::post('/confirmDateTime', [ReservationsController::class, 'confirmDateTime']);



