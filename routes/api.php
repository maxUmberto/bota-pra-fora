<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\VentCommentController;
use App\Http\Controllers\VentController;
use App\Http\Controllers\VentReactionController;
use App\Http\Controllers\VentViewController;

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

Route::post('sign-up', [LoginController::class, 'userSignUp']);
Route::post('login', [LoginController::class, 'userLogin']);
Route::post('logout', [LoginController::class, 'userLogout']);
Route::post('forgot-password', [PasswordController::class, 'createResetPasswordLink']);
Route::post('reset-password', [PasswordController::class, 'resetPassword']);

Route::middleware(['jwt.auth'])->prefix('vent')->group(function() {
    Route::post('new', [VentController::class, 'createNewVent']);
    Route::get('my-vents', [VentController::class, 'loadUserVents']);
    Route::get('{vent}/info', [VentController::class, 'loadVentInfo']);
    Route::get('view', [VentViewController::class, 'getRandomVent']);
    Route::post('{vent}/comment', [VentCommentController::class, 'createNewComment']);
    Route::post('{vent}/react/{reaction}', [VentReactionController::class, 'reactToAVent']);
});
