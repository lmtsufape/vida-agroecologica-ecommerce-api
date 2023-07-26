<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Api\ResetPasswordController;

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

// Exibir formulário de redefinição de senha
Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');

// Rota para redefinir a senha com base no token
Route::post('/reset-password', [ResetPasswordController::class, 'resetPassword'])->name('password.update');


// Parte do gestão web

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware(['auth:sanctum', 'type.admin'])->group(function () {

    Route::get('/usuarios', [App\Http\Controllers\AdminController::class, 'usuarios_index'])->name('usuarios.index');
    Route::post('/usuario/store', [App\Http\Controllers\UserController::class, 'store'])->name('usuario.store');
    Route::post('/usuario/update', [App\Http\Controllers\UserController::class, 'update'])->name('usuario.update');
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
