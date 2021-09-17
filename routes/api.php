<?php

use App\Http\Controllers\Api\AuthController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::post('/login-with-email', [AuthController::class, 'loginWithEmail']);

Route::post('/register-with-email', [AuthController::class, 'registerWithEmail']);

Route::post('/login-with-phone', [AuthController::class, 'loginWithPhone']);

Route::post('/register-with-phone', [AuthController::class, 'registerWithPhone']);

Route::post('/profile/{id}', [AuthController::class, 'profile']);
