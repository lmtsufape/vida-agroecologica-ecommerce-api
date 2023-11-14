<?php

use App\Http\Controllers\Web\Auth\WebAuthController;
use App\Http\Controllers\Web\UserAgricultorController;
use App\Http\Controllers\Web\WebUserController;
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

Route::get('/check', function () {
    return response()->json(['message' => 'Vida Agroecológica API']);
});

// Auth
Route::controller(WebAuthController::class)->group(function () {

    Route::get('/email/verify', 'showEmailNotice')->middleware('auth')->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', 'verifyEmail')->middleware('signed')->name('verification.verify');
    Route::post('/email/verification-notification', 'resendEmail')->middleware(['auth', 'throttle:6,1'])->name('verification.send');

    Route::get('/forgot-password', 'showResetPasswordForm')->middleware('guest')->name('password.request');
    Route::post('/forgot-password', 'sendResetEmail')->middleware('guest')->name('password.email');
    Route::get('/reset-password/{token}', 'showResetForm')->middleware('guest')->name('password.reset');
    Route::post('/reset-password', 'resetPassword')->middleware('guest')->name('password.update');
});

# AGRICULTORES



// Users
Route::middleware('guest')->controller(WebUserController::class)->prefix('/users')->group(function () {
    Route::get('/create', 'create')->name('register');
    Route::post('/', 'store')->name('users.store');
});

Route::resource('/users', WebUserController::class)->except(['create', 'store'])->middleware('auth');

// Parte do gestão web

Route::get('/home', [App\Http\Controllers\Web\HomeController::class, 'index'])->name('home');

// Route::middleware(['auth:sanctum', 'role:administrador,presidente'])->group(function () {
//     Route::get('/usuarios', [App\Http\Controllers\Web\UserController::class, 'index'])->name('usuarios.index');
//     Route::post('/usuarios/store', [App\Http\Controllers\Web\UserController::class, 'store'])->name('usuario.store');
//     Route::post('/usuarios/update', [App\Http\Controllers\Web\UserController::class, 'update'])->name('usuario.update');
// });





