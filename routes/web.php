<?php

use App\Http\Controllers\Web\Auth\WebAuthController;
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

// Parte do gestão web

//Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware(['auth:sanctum', 'type.admin'])->group(function () {

    Route::get('/usuarios', [App\Http\Controllers\AdminController::class, 'usuarios_index'])->name('usuarios.index');
    // Route::post('/usuario/store', [App\Http\Controllers\UserController::class, 'store'])->name('usuario.store');
    // Route::post('/usuario/update', [App\Http\Controllers\UserController::class, 'update'])->name('usuario.update');
});

Route::middleware(['auth:sanctum', 'type.admin.presidente'])->group(function () {

    Route::get('/associacoes', [App\Http\Controllers\AdminController::class, 'associacoes_index'])->name('associacoes.index');
    Route::get('/associacao/{associacao_id}/organizacaoControleSocial', [App\Http\Controllers\OrganizacaoControleSocialController::class, 'index'])->name('ocs.index');

    Route::post('/associacao/store', [App\Http\Controllers\AssociacaoController::class, 'store'])->name('associacao.store');
    Route::post('/associacao/update', [App\Http\Controllers\AssociacaoController::class, 'update'])->name('associacao.update');

    Route::post('/organizacaoControleSocial/store', [App\Http\Controllers\OrganizacaoControleSocialController::class, 'store'])->name('ocs.store');
    Route::post('/organizacaoControleSocial/update', [App\Http\Controllers\OrganizacaoControleSocialController::class, 'update'])->name('ocs.update');

    Route::get('/agricultores', [App\Http\Controllers\AgricultorController::class, 'agricultoresIndex'])->name('agricultores.index');
    Route::PUT('/agricultores/vincula-ocs', [App\Http\Controllers\AgricultorController::class, 'vincularAgricultoOrganizacao'])->name('vincula.agricultor');
});
