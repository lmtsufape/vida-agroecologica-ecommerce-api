<?php

use App\Http\Controllers\api\BancasController as ApiBancasController;
use App\Http\Controllers\Api\ConsumidorController;
use App\Http\Controllers\Api\EnderecoController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ProdutorController;
use App\Http\Controllers\BancasController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::apiResource('/produtor', ProdutorController::class)->except('store');

    Route::apiResource('/consumidor', ConsumidorController::class)->except('store');
    Route::apiResource('/{userId}/endereco', EnderecoController::class);
    Route::controller(ApiBancasController::class)->group(function () {
        Route::post('bancas', 'store')->middleware(['check_produtor', 'check_bancas']);
        Route::get('bancas', 'index')->name('bancas.index');
        //Route::get('items/create', 'create')->name('items.create');
        //Route::get('items/{item}', 'show')->name('items.show');
        //Route::put('items/{item}', 'update')->name('items.update');
        //Route::delete('items/{item}', 'destroy')->name('items.destroy');
        //Route::get('items/{item}/edit', 'edit')->name('items.edit');

    });
});

Route::post('/produtor', [ProdutorController::class, 'store']);

Route::post('/consumidor', [ConsumidorController::class, 'store']);

Route::post('/login', [LoginController::class, 'login']);
Route::post('/token', [LoginController::class, 'token']);
