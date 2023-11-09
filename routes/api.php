<?php

use App\Http\Controllers\Api\EstadoController;
use App\Http\Controllers\Api\BairroController;
use App\Http\Controllers\Api\BancaController;
use App\Http\Controllers\Api\BuscaController;
use App\Http\Controllers\Api\CidadeController;
use App\Http\Controllers\Api\ProdutoController;
use App\Http\Controllers\Api\PropriedadeController;
use App\Http\Controllers\Api\VendaController;
use App\Http\Controllers\Api\FeiraController;
use App\Http\Controllers\Api\ApiUserController;
use App\Http\Controllers\Api\Auth\ApiAuthController;
use App\Http\Controllers\ReuniaoController;
use App\Http\Controllers\Web\AssociacaoController;
use App\Http\Controllers\Web\OrganizacaoControleSocialController;
use App\Http\Contollers\Web\UserAgricultorController;
use App\Http\Controllers\UserController;
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

# Auth
Route::controller(ApiAuthController::class)->group(function () {
    Route::post('/sanctum/token', 'token')->middleware('guest');
    Route::post('/sanctum/token/revoke', 'revokeToken')->middleware('auth:sanctum');
    Route::post('/email/verification-notification', 'resendEmail')->middleware(['auth:sanctum', 'throttle:6,1'])->name('verification.send');
    Route::post('/forgot-password', 'sendResetEmail')->middleware('guest')->name('password.email');
    Route::get('/email/verify', fn () => response()->json(['error' => 'O usuário não está verificado!', 'email' => Auth::user()->email], 403))->middleware('auth:sanctum')->name('verification.notice');
    Route::get('/login', fn () => response()->json(['error' => 'Unathorized'], 401))->middleware('guest')->name('login');
});

# Usuários
Route::controller(ApiUserController::class)->group(function () {
    Route::post('/users', 'store')->middleware('storeUser');
    Route::put('/users/{user}/updateroles', 'updateUserRoles')->middleware('auth:sanctum, role:administrador');
});

Route::get('/users/enderecos', [UserController::class, 'indexEndereco'])->middleware('auth:sanctum');
Route::post('/users/enderecos', [UserController::class, 'createNewEndereco'])->middleware('auth:sanctum');
Route::patch('/users/enderecos/{endereco}', [UserController::class, 'updateEndereco'])->middleware('auth:sanctum');
Route::delete('/users/enderecos/{endereco}', [UserController::class, 'destroyEndereco'])->middleware('auth:sanctum'); // Posteriormente, alterar classe para ApiUserController.

Route::apiResource('/users', ApiUserController::class)->except('store')->middleware('auth:sanctum');

# Bancas
Route::middleware('auth:sanctum')->controller(BancaController::class)->prefix('/bancas')->group(function () {
    Route::get('/{banca}/imagem', 'getImagem');
    Route::get('/agricultores/{agricultor}', 'getAgricultorBancas');
    Route::delete('/{banca}/imagem', 'deleteImagem');
});

Route::apiResource('/bancas', BancaController::class)->middleware('auth:sanctum');

# Vendas
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

# Produtos
Route::middleware('auth:sanctum')->controller(ProdutoController::class)->prefix('/produtos')->group(function () {
    Route::get('/categorias', 'getCategorias');
    Route::get('/tabelados', 'getTabelados');
    Route::get('/{produto}/imagem', 'getImagem');
});

Route::get('/bancas/{banca}/produtos', [ProdutoController::class, 'getBancaProdutos']);

Route::apiResource('/produtos', ProdutoController::class)->middleware('auth:sanctum');

# Feiras
Route::middleware('auth:sanctum')->controller(FeiraController::class)->prefix('/feiras')->group(function () {
    Route::get('/', 'index');
    Route::post('/', 'store')->middleware('role:administrador');
    Route::patch('/{feira}', 'update')->middleware('role:administrador');
    Route::delete('/{id}', 'destroy');

    Route::get('/{feira}/imagem', 'getImagem');
    Route::delete('/{feira}/imagem', 'deleteImagem');
});

# Bairros
Route::middleware('auth:sanctum')->controller(BairroController::class)->prefix('/bairros')->group(function () {
    Route::get('/', 'index');
    Route::post('/', 'store')->middleware('role:administrador');
    Route::patch('/{bairro}', 'update');
    Route::delete('/{id}', 'destroy')->middleware('role:administrador');
});

# Cidades
Route::middleware('auth:sanctum')->controller(CidadeController::class)->prefix('/cidades')->group(function () {
    Route::get('/', 'index');
    Route::post('/', 'store')->middleware('role:administrador');
    Route::patch('/{cidade}', 'update')->middleware('role:administrador');
    Route::delete('{id}', 'destroy')->middleware('role:administrador');
});

# Estados
Route::middleware('auth:sanctum')->controller(EstadoController::class)->prefix('/estados')->group(function () {
    Route::get('/', 'index');
});

Route::get('/locais', function () {
    $estados = \App\Models\Estado::has('cidades.bairros')->with('cidades.bairros')->get();

    return response()->json(['estados' => $estados]);
});

# Propriedades
Route::get('/propriedades/user/{user_id}', [PropriedadeController::class, 'getPropriedades'])->middleware('auth:sanctum');

Route::apiResource('/propriedades', PropriedadeController::class)->middleware('auth:sanctum');

# Buscas
Route::controller(BuscaController::class)->prefix('/buscar')->group(function () {
    Route::post('/', 'buscar');
});

# Reuniões
Route::middleware(['auth:sanctum', 'role:administrador,presidente,secretario'])->controller(ReuniaoController::class)->prefix('/reunioes')->group(function () {
    Route::post('/{reuniao}/ata','anexarAta');
    Route::get('/{reuniao}/ata','verAta');
    Route::delete('/{reuniao}/ata','deletarAta');

    Route::post('/{reuniao}/anexos','enviarAnexos');
    Route::post('/{arquivo_id}/anexos/atualizar','atualizarAnexo');
});

Route::apiResource('/reunioes', ReuniaoController::class)->except('show')->middleware('auth:sanctum');
# --------------------------------------------------------------------------------------------------------------------------------------------------------

Route::middleware(['auth:sanctum', 'role:administrador,presidente'])->group(function () {

    # OCS
    Route::get('/associacoes/{associacao_id}/ocs', [OrganizacaoControleSocialController::class, 'index']);
    Route::post('/associacoes/ocs', [OrganizacaoControleSocialController::class, 'store']);
    Route::patch('/associacoes/ocs/{id}', [OrganizacaoControleSocialController::class, 'update']);
    
    # Associações
    Route::get('/associacoes', [AssociacaoController::class, 'index']); //funcionando
    Route::post('/associacoes', [AssociacaoController::class, 'store']); //funcionando
    Route::patch('/associacoes/{id}', [AssociacaoController::class, 'update']); //funcionando
});

Route::middleware('role:administrador,presidente')->controller(UserAgricultorController::class)->prefix('/agricultores')->group(function () {
    Route::get('/', 'index');
    Route::put('/vincular/{id}', 'vincularAgricultorOrganizacao');
});
