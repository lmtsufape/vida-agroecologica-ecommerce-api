<?php

use App\Http\Controllers\Web\Auth\WebAuthController;
use App\Http\Controllers\Web\UserAgricultorController;
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

Route::get('/', function () {
    return view('welcome');
});

// Auth
Route::controller(WebAuthController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->middleware('guest')->name('login');
    Route::post('/login', 'authenticate')->middleware('guest');
    Route::post('/logout', 'logout')->middleware('auth')->name('logout');

    Route::get('/email/verify', 'showEmailNotice')->middleware('auth')->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', 'verifyEmail')->middleware('signed')->name('verification.verify');
    Route::post('/email/verification-notification', 'resendEmail')->middleware(['auth', 'throttle:6,1'])->name('verification.send');

    Route::get('/forgot-password', 'showResetPasswordForm')->middleware('guest')->name('password.request');
    Route::post('/forgot-password', 'sendResetEmail')->middleware('guest')->name('password.email');
    Route::get('/reset-password/{token}', 'showResetForm')->middleware('guest')->name('password.reset');
    Route::post('/reset-password', 'resetPassword')->middleware('guest')->name('password.update');
});

// Agricultores
Route::middleware('role:administrador,presidente')->controller(UserAgricultorController::class)->prefix('/agricultores')->group(function () {
    Route::get('/', 'index')->name('agricultor.index');
    Route::put('/vincular', 'vincularAgricultorOrganizacao')->name('agricultor.vincular');
});

// Parte do gestão web

Route::get('/home', [App\Http\Controllers\Web\HomeController::class, 'index'])->name('home');

Route::middleware(['auth:sanctum', 'role:administrador,presidente'])->group(function () {
    Route::get('/usuarios', [App\Http\Controllers\Web\UserController::class, 'index'])->name('usuarios.index');
    Route::post('/usuarios/store', [App\Http\Controllers\Web\UserController::class, 'store'])->name('usuario.store');
    Route::post('/usuarios/update', [App\Http\Controllers\Web\UserController::class, 'update'])->name('usuario.update');
});

Route::middleware(['auth:sanctum', 'role:administrador,presidente'])->group(function () {

    # ASSOCIAÇÃO

    Route::get('/associacoes', [App\Http\Controllers\Web\AssociacaoController::class, 'index'])->name('associacoes.index');
    Route::post('/associacao/store', [App\Http\Controllers\Web\AssociacaoController::class, 'store'])->name('associacao.store');
    Route::post('/associacao/update', [App\Http\Controllers\Web\AssociacaoController::class, 'update'])->name('associacao.update');

    # OCS

    Route::get('/associacao/{associacao_id}/organizacaoControleSocial', [App\Http\Controllers\Web\OrganizacaoControleSocialController::class, 'index'])->name('ocs.index');
    Route::post('/organizacaoControleSocial/store', [App\Http\Controllers\Web\OrganizacaoControleSocialController::class, 'store'])->name('ocs.store');
    Route::post('/organizacaoControleSocial/update', [App\Http\Controllers\Web\OrganizacaoControleSocialController::class, 'update'])->name('ocs.update');

    # AGRICULTORES
});
