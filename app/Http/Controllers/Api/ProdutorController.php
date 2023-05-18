<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Models\Produtor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use function PHPSTORM_META\map;

class ProdutorController extends Controller
{
    public function index()
    {
        $produtores = User::where("users.papel_type", "=", "Produtor")->join("produtores", "produtores.id", "=", "users.papel_id")
            ->orderBy('name')
            ->get();
        if (!$produtores) {
            return response()->json(['erro' => 'Nenhum usuário cadastrado'], 200);
        }
        return response()->json(['usuários' => $produtores], 200);
    }

    public function store(StoreUserRequest $request)
    {
        DB::beginTransaction();
        try {
            $produtor = Produtor::create();

            $data_user = $request->only(['name', 'email', 'apelido', 'telefone', 'cpf', 'cnpj']);
            $data_user['password'] = Hash::make($request->password);
            $produtor = $produtor->user()->create($data_user);

            $data_endereco = $request->only(['rua', 'cep', 'numero', 'complemento', 'bairro_id']);
            $endereco = $produtor->endereco()->create($data_endereco);
            DB::commit();
            return response()->json([
                'produtor' => $produtor,
                'endereco' => $endereco
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e]);
        }
    }

    public function show($id)
    {
        $produtor = Produtor::find($id);
        $produtor->user;
        if (!$produtor) {
            return response()->json(['erro' => 'Usuário não encontrado'], 404);
        }

        return response()->json(['usuário' => $produtor], 200);
    }

    public function update(Request $request)
    {
        $produtor = Auth::user();
        DB::beginTransaction();
        if (!$produtor) {

            return response()->json(['erro' => 'Usuário não encontrado'], 404);
        }
        $produtor = User::find($produtor->id);
        $keys = ['name','apelido','telefone','email'];
        $dados = $request->only($keys);
        $dados['password'] = Hash::make($request->only('password'));
        $produtor->update($dados);
        $produtor->papel;
        DB::commit();
        return response()->json([$produtor], 200);
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        $produtor = Produtor::find($id);
        $produtor->delete();
        DB::commit();
        return response()->noContent();
    }

    public function getBanca($produtorId)
    {
        $produtor = Produtor::findOrFail($produtorId);

        return response()->json(['Banca' => $produtor->banca]);
    }
}
