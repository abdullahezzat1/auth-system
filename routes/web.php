<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\HomeController;
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

Route::get('/', [HomeController::class, 'viewHome'])->middleware('guest')->name('login');
Route::get('/app', [AccountController::class, 'viewAccount'])->middleware(['auth', 'verified']);

Route::post('/account/signup', [AccountController::class, 'signup']);
Route::post('/account/login', [AccountController::class, 'login']);
Route::post('/account/logout', [AccountController::class, 'logout']);
Route::post('/account/info/change', [AccountController::class, 'changeInfo']);

Route::get('/account/email/verify', [AccountController::class, 'viewVerifyEmail'])
	->middleware('auth')->name('verification.notice');
Route::get('/account/email/verify/{id}/{hash}', [AccountController::class, 'verifyEmail'])
	->middleware(['auth', 'signed'])->name('verification.verify');
Route::get('/account/email/resend-verification', [AccountController::class, 'resendVerification'])
	->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Route::post('/account/password/forgot', [AccountController::class, 'forgotPassword'])
	->middleware('guest')->name('password.email');
Route::get('/account/password/reset/{token}', [AccountController::class, 'viewResetPassword'])
	->middleware('guest')->name('password.reset');
Route::post('/account/password/reset', [AccountController::class, 'resetPassword'])
	->middleware('guest')->name('password.update');
Route::post('/account/password/change', [AccountController::class, 'changePassword'])->middleware(['auth', 'verified']);
