<?php

use App\Http\Controllers\Api\EstadoController;
use App\Http\Controllers\Api\BairroController;
use App\Http\Controllers\Api\BancaController;
use App\Http\Controllers\Api\BuscaController;
use App\Http\Controllers\Api\CidadeController;
use App\Http\Controllers\Api\ProdutoController;
use App\Http\Controllers\Api\PropriedadeController;
use App\Http\Controllers\Api\UserConsumidorController;
use App\Http\Controllers\Api\VendaController;
use App\Http\Controllers\Api\FeiraController;
use App\Http\Controllers\Api\ApiUserController;
use App\Http\Controllers\Api\Auth\ApiAuthController;
use App\Http\Controllers\Web\AssociacaoController;
use App\Http\Controllers\Web\OrganizacaoControleSocialController;
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

// Consumidores
Route::middleware('auth:sanctum')->controller(UserConsumidorController::class)->prefix('/users')->group(function () {
    Route::get('/enderecos', 'indexEndereco');
    Route::post('/enderecos', 'storeEndereco');
    Route::get('/enderecos/{endereco}', 'showEndereco');
    Route::patch('/enderecos/{endereco}', 'updateEndereco');
    Route::delete('/enderecos/{endereco}', 'destroyEndereco');
});

// Usuários
Route::controller(ApiUserController::class)->group(function () {
    Route::post('/users', 'store')->middleware('storeUser');
    Route::put('/users/{user}/updateroles', 'updateUserRoles')->middleware('auth:sanctum, role:administrador');
});

Route::apiResource('/users', ApiUserController::class)->except('store')->middleware('auth:sanctum');

// Bancas
Route::middleware('auth:sanctum')->controller(BancaController::class)->prefix('/bancas')->group(function () {
    Route::get('/{banca}/produtos', 'getProdutos');
    Route::get('/{banca}/imagem', 'getImagem');
    Route::delete('/{banca}/imagem', 'deleteImagem');
});

Route::apiResource('/bancas', BancaController::class)->middleware('auth:sanctum');

// Vendas
Route::middleware('auth:sanctum')->controller(VendaController::class)->prefix('/transacoes')->group(function () {
    Route::post('/{venda}/confirmar', 'confirmarVenda')->middleware('role:agricultor');
    Route::post('/{venda}/enviar', 'marcarEnviado')->middleware('role:agricultor');
    Route::get('/{agricultorId}/vendas', 'getVendas')->middleware('role:agricultor');
    Route::get('/bancas/{banca}', 'getBancaVendas')->middleware('role:agricultor');

    Route::post('/{venda}/comprovante', 'anexarComprovante')->middleware('role:consumidor');
    Route::post('/{venda}/entregar', 'marcarEntregue')->middleware('role:consumidor');
    Route::post('/', 'store')->middleware('role:consumidor');
    Route::get('/{consumidorId}/compras', 'getCompras')->middleware('role:consumidor');


    Route::get('/', 'index')->middleware('role:administrador');

    Route::post('/{venda}/cancelar', 'cancelarCompra');
    Route::get('/{venda}/comprovante', 'verComprovante');
    Route::get('/{venda}', 'show');
});

// Produtos
Route::middleware('auth:sanctum')->controller(ProdutoController::class)->prefix('/produtos')->group(function () {
    Route::get('/categorias', 'getCategorias');
    Route::get('/tabelados', 'getTabelados');
    Route::get('/{produto}/imagem', 'getImagem');
});

Route::apiResource('/produtos', ProdutoController::class)->middleware('auth:sanctum');

// Feiras
Route::middleware('auth:sanctum')->controller(FeiraController::class)->prefix('/feiras')->group(function () {
    Route::get('/', 'index');
    Route::post('/', 'store')->middleware('role:administrador');
    Route::patch('/{feira}', 'update');//fazer função
    Route::delete('/{id}', 'destroy');//Fazer polices
});

// Bairros
Route::middleware('auth:sanctum')->controller(BairroController::class)->prefix('/bairros')->group(function () {
    Route::get('/', 'index');
    Route::post('/', 'store')->middleware('role:administrador');
    Route::patch('/{bairro}', 'update');//fazer função
    Route::delete('/{id}', 'destroy')->middleware('role:administrador');//Fazer polices
});

// Cidades
Route::middleware('auth:sanctum')->controller(CidadeController::class)->prefix('/cidades')->group(function () {
    Route::get('/', 'index');
    Route::post('/', 'store')->middleware('role:administrador');
    Route::patch('/{cidade}', 'update');//fazer função
    Route::delete('{id}', 'destroy')->middleware('role:administrador');
});

// Estados
Route::middleware('auth:sanctum')->controller(EstadoController::class)->prefix('/estados')->group(function () {
    Route::get('/', 'index');
});

Route::get('/locais', function () {
    $estados = \App\Models\Estado::has('cidades.bairros')->with('cidades.bairros')->get();

    return response()->json(['estados' => $estados]);
});

// Propriedades
Route::get('/propriedades/user/{user_id}', [PropriedadeController::class, 'getPropriedades'])->middleware('auth:sanctum');

Route::apiResource('/propriedades', PropriedadeController::class)->middleware('auth:sanctum');

// Auth
Route::controller(ApiAuthController::class)->group(function () {
    Route::post('/sanctum/token', 'token')->middleware('guest');
    Route::post('/sanctum/token/revoke', 'revokeToken')->middleware('auth:sanctum');

    Route::get('/logado', 'user_logado')->middleware('auth:sanctum');//para testes

    Route::post('/email/verification-notification', 'resendEmail')->middleware(['auth:sanctum', 'throttle:6,1'])->name('verification.send');

    Route::post('/forgot-password', 'sendResetEmail')->middleware('guest')->name('password.email');
    Route::get('/email/verify', fn () => response()->json(['error' => 'O usuário não está verificado!', 'email' => Auth::user()->email], 403))->middleware('auth:sanctum')->name('verification.notice');
    Route::get('/login', fn () => response()->json(['error' => 'Unathorized'], 401))->middleware('guest')->name('login');
});

//Busca
Route::controller(BuscaController::class)->prefix('/buscar')->group(function () {
    Route::get('/{nome}', 'buscar_banca');
    Route::post('/produto', 'buscar_produto');
    Route::get('/vendedor/{name}', 'buscar_vendedor');
    Route::get('/categoria/{categoria}', 'buscar_categoria');
});

// --------------------------------------------------------------------------------------------------------------------------------------------------------

// Parte do gesão web

Route::middleware(['auth:sanctum', 'type.admin'])->group(function () {
    // Usuario
    // Route::post('cadastro', [UserController::class, 'store']);
    // Route::post('atualizar/usuario', [UserController::class, 'update']);
    // Route::get('users', [UserController::class, 'index']);

    // Associacao
    Route::get('associacoes', [AssociacaoController::class, 'index']);
});

Route::middleware(['auth:sanctum', 'type.admin.presidente'])->group(function () {
    //Associacao
    Route::post('cadastrar/associacao', [AssociacaoController::class, 'store']);
    Route::post('atualizar/associacao', [AssociacaoController::class, 'update']);

    // OCS
    Route::post('/organizacaoControleSocial/store', [OrganizacaoControleSocialController::class, 'store']);
    Route::post('/organizacaoControleSocial/update', [OrganizacaoControleSocialController::class, 'update']);
    Route::get('/associacao/{associacao_id}/organizacaoControleSocial', [OrganizacaoControleSocialController::class, 'index']);
});

Route::middleware(['auth:sanctum', 'type.presidente'])->group(function () {
    //minhas associações
});

//Route::post('/verifica', [UserController::class, 'verificaUsuario']);
