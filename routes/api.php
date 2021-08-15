<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\VentController;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('sign-up', [LoginController::class, 'userSignUp']);
Route::post('login', [LoginController::class, 'userLogin']);
Route::post('logout', [LoginController::class, 'userLogout']);

Route::prefix('vent')->group(function() {
    Route::post('new', [VentController::class, 'createNewVent']);
});
