<?php

use App\Http\Controllers\GETController;
use App\Http\Controllers\POSTController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [GETController::class, 'home']);
Route::get('/app', [GETController::class, 'app']);
Route::get('/reset-password/{token}', [GETController::class, 'resetPassword']);


Route::post('/signup', [POSTController::class, 'signup']);
Route::post('/login', [POSTController::class, 'login']);
Route::post('/forgot-password', [POSTController::class, 'forgotPassword']);
Route::post('/reset-password', [POSTController::class, 'resetPassword']);
Route::post('/change-password', [POSTController::class, 'changePassword']);
Route::post('/change-profile-info', [POSTController::class, 'changeProfileInfo']);
Route::post('/logout', [POSTController::class, 'logout']);


// dd('routing');
