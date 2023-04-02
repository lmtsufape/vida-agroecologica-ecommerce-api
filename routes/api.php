<?php

use App\Http\Controllers\Api\BairrosController;
use App\Http\Controllers\Api\BancasController as ApiBancasController;
use App\Http\Controllers\Api\ConsumidorController;
use App\Http\Controllers\Api\EnderecoController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\ProdutoController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ProdutorController;
use App\Http\Controllers\Api\SacolaController;
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
    Route::controller(EnderecoController::class)->group(function () {
        Route::get('/enderecos', 'show');
        Route::put('/enderecos', 'update');
    });
    Route::controller(BairrosController::class)->group(function () {
        Route::get('bairros', 'index');
    });
    //produtor
    Route::middleware('check_produtor')->group(function () {
        Route::apiResource('/produtores', ProdutorController::class, ['parameters' => ['produtores' => 'produtor']])->except('store');

        Route::controller(ApiBancasController::class)->group(function () {
            Route::post('bancas', 'store')->middleware('check_bancas');
            Route::get('bancas', 'index');
            Route::get('bancas/{id}', 'show');
            Route::put('bancas/{id}', 'update');
            Route::delete('bancas/{id}', 'destroy')->middleware('check_valid_banca');
        });

        Route::apiResource('banca/produtos', ProdutoController::class);
    });
    //consumidor
    Route::middleware('check_consumidor')->group(function () {
        Route::apiResource('/consumidores', ConsumidorController::class, ['parameters' => ['consumidores' => 'consumidor']])->except('store');

        Route::apiResource('/sacolas', SacolaController::class)->only('index', 'destroy');
        Route::controller(SacolaController::class)->group(function () {
            Route::post('/sacolas', 'store')->middleware('check_estoque');
            Route::patch('/sacolas/{itemId}', 'update')->middleware('check_estoque');
            Route::delete('/sacolas/carrinho', 'limparCarrinho');
        });
    });
    //fora dos middlewares
    Route::get('/categorias', function (Request $request) {

        return response()->json(['categorias' => \App\Models\Categoria::all()]);
    });
    Route::controller(ProdutoController::class)->group(function () {
        Route::post('/busca', 'buscar');
        Route::get('/categorias/{categoria}/produtos', 'buscarCategoria');
    });
    Route::get('/produtos', function() {
        $produtos = App\Models\ProdutoTabelado::all();
        return response()->json(['produtos' => $produtos]);
    });
});

Route::post('/produtores', [ProdutorController::class, 'store']);

Route::post('/consumidores', [ConsumidorController::class, 'store']);

Route::post('/login', [LoginController::class, 'login']);
Route::post('/token', [LoginController::class, 'token']);
