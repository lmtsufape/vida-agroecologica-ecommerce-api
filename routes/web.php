<?php

use Illuminate\Support\Facades\Route;
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