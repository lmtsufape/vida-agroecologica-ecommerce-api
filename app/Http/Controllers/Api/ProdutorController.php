<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Produtor;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


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
            $produtor = Produtor::create(['bairro' => $request->bairro]);

            $data_user = $request->only(['name', 'email', 'apelido', 'telefone', 'cpf', 'cnpj']);
            $data_user['password'] = Hash::make($request->password);
            $produtor = $produtor->user()->create($data_user);
            event(new Registered($produtor));

            $data_endereco = $request->only(['rua', 'cep', 'numero', 'complemento', 'cidade', 'estado', 'país']);
            $data_endereco['bairro_id'] = 1; // bairro_id de produtores deverá corresponder a "outros" na tabela de bairros
            $endereco = $produtor->endereco()->create($data_endereco);
            DB::commit();
            return response()->json([
                'produtor' => $produtor,
                'endereco' => $endereco
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()]);
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

    public function update(UpdateUserRequest $request)
    {
        $user = Auth::user();
        DB::beginTransaction();
        if (!$user) {
            return response()->json(['erro' => 'Usuário não encontrado'], 404);
        }
        $user = User::find($user->id);
        $keys = ['name', 'apelido', 'telefone', 'email'];
        $dados = $request->only($keys);
        $dados['password'] = Hash::make($request->password);
        $user->update($dados);
        $user->papel()->update(['bairro' => $request->bairro]);
        $user->endereco()->update($request->only(['rua', 'cep', 'numero', 'complemento', 'cidade', 'estado', 'país']));
        $user->papel;
        DB::commit();
        return response()->json([$user], 200);
    }

    public function destroy($id)
    {
        $produtor = Produtor::findOrFail($id);
        DB::beginTransaction();
        $produtor->user()->delete();
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
