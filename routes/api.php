<?php

use App\Http\Controllers\Api\BairroController;
use App\Http\Controllers\Api\BancaController;
use App\Http\Controllers\Api\ConsumidorController;
use App\Http\Controllers\Api\EnderecoController;
use App\Http\Controllers\Api\CidadeController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\ProdutoController;
use App\Http\Controllers\Api\ProdutorController;
use App\Http\Controllers\Api\VendaController;
use App\Http\Controllers\Api\FeiraController;
use App\Http\Controllers\Api\ResetPasswordController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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

Route::middleware(['auth:sanctum'])->group(function () {
    Route::controller(EnderecoController::class)->group(function () {
        Route::get('/enderecos', 'show');
        Route::put('/enderecos', 'update');
    });
    Route::controller(BairroController::class)->group(function () {
        Route::get('bairros', 'index');
    });
    //produtor
    Route::middleware('check_produtor')->group(function () {
        Route::apiResource('/produtores', ProdutorController::class, ['parameters' => ['produtores' => 'produtor']])->except('store');

        Route::controller(BancaController::class)->group(function () {
            Route::delete('/bancas/imagens', 'deleteImagem');
            Route::post('/bancas', 'store')->middleware('check_bancas');
            Route::get('/bancas', 'index');
            Route::get('/bancas/{banca}', 'show');
            Route::put('/bancas/{banca}', 'update');
            Route::delete('/bancas/{banca}', 'destroy')->middleware('check_valid_banca');
        });

        Route::apiResource('banca/produtos', ProdutoController::class);

        Route::post('/vendas/{id}/confirmar', [VendaController::class, 'confirmarVenda']);
        Route::post('/vendas/{id}/enviar', [VendaController::class, 'marcarEnviado']);
    });
    //consumidor
    Route::middleware('check_consumidor')->group(function () {
        Route::apiResource('/consumidores', ConsumidorController::class, ['parameters' => ['consumidores' => 'consumidor']])->except('store');

        Route::post('/vendas/{id}/comprovante', [VendaController::class, 'anexarComprovante']);
        Route::post('/vendas/{id}/entregar', [VendaController::class, 'marcarEntregue']);
        Route::post('/vendas', [VendaController::class, 'store']);
    });
    //fora dos middlewares
    Route::get('/vendas/{id}/comprovante', [VendaController::class, 'verComprovante']);
    Route::post('/vendas/{id}/cancelar', [VendaController::class, 'cancelarCompra']);
    Route::apiResource('/vendas', VendaController::class)->except('store', 'destroy', 'update');
    Route::get('/categorias', function () {
        return response()->json(['categorias' => App\Models\ProdutoTabelado::distinct()->pluck('categoria')]);
    });
    Route::controller(ProdutoController::class)->group(function () {
        Route::post('/busca', 'buscar');
        Route::get('/categorias/{categoria}/produtos', 'buscarCategoria');
    });
    Route::get('/produtos', function () {
        $produtos = App\Models\ProdutoTabelado::all();
        return response()->json(['produtos' => $produtos]);
    });
    Route::get('/imagens/bancas/{banca}', [BancaController::class, 'getImagem']);
    Route::get('/produtores/{produtorId}/bancas', [ProdutorController::class, 'getBanca']);
});



Route::get('/login', fn () => response()->json(['error' => 'Não autorizado'], 401))->name('login'); //desnecessário, apenas para teste
Route::post('/login', [LoginController::class, 'login']);
Route::post('/token', [LoginController::class, 'token']);

Route::get('/email/verify', function () {
    return response()->json(['error' => 'O usuário não está verificado!', 'email' => Auth::user()->email], 403);
})->middleware('auth:sanctum')->name('verification.notice');
Route::get('/email/verify/{id}/{hash}', [LoginController::class, 'verificarEmail'])->middleware('signed')->name('verification.verify');
Route::post('/email/verification-notification', [LoginController::class, 'reenviarEmail'])->middleware(['auth:sanctum', 'throttle:6,1'])->name('verification.send');

Route::get('/imagens/produtos/{id}', [ProdutoController::class, 'getImagem']);

// Rota para solicitar o email de redefinição de senha

Route::post('/forgot-password', [ResetPasswordController::class, 'sendResetEmail'])->name('password.email');
Route::prefix('cidades')->group(function(){
    Route::get('/', [CidadeController::class, 'index']);
    Route::post('/store', [CidadeController::class, 'store']);
});

Route::prefix('bairros')->group(function(){
    Route::get('/', [BairroController::class, 'index']);
    Route::post('/store', [BairroController::class, 'store']);
});

Route::prefix('feiras')->group(function(){
    Route::get('/', [FeiraController::class, 'index']);
    Route::post('/store', [FeiraController::class, 'store']);
});

Route::prefix('produtores')->group(function(){
    Route::post('/store', [ProdutorController::class, 'store']);
});

Route::prefix('consumidores')->group(function(){
    Route::post('/store', [ConsumidorController::class, 'store']);
});
