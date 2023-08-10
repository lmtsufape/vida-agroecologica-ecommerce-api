<?php

namespace App\Http\Controllers\Auth\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Contato;
use App\Models\Endereco;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function store(Request $request)
    {
        $usuario = new User();
        $contato = new Contato();
        $endereco = new Endereco();

        $contato->email = $request->email;
        $contato->telefone = $request->telefone;
        $contato->save();

        $endereco->bairro = $request->bairro;
        $endereco->rua = $request->rua;
        $endereco->numero = $request->numero;
        $endereco->cep = $request->cep;
        $endereco->save();

        $usuario->nome = $request->nome;
        $usuario->email = $request->email;
        $usuario->cpf = $request->cpf;
        $usuario->password = Hash::make($request->password);
        $usuario->endereco_id = $endereco->id;
        $usuario->contato_id = $contato->id;
        if(!empty($request->organizacao_controle_social_id)){
            $usuario->organizacao_controle_social_id = $request->organizacao_controle_social_id;
        }
        $usuario->save();
        $role = Role::find($request->id);
        $usuario->roles()->attach($role);

        return response()->json([
            'data' => [
                'status'  => "Usuário Criado com Sucesso!"
            ]
        ]);
    }

    public function update(Request $request)
    {
        $usuario = User::find($request->usuario_id);
        $contato = Contato::find($usuario->contato_id);
        $endereco = Endereco::find($usuario->endereco_id);

        $usuario->nome = $request->nome;
        $usuario->email = $request->email;
        $usuario->cpf = $request->cpf;
        $usuario->password = Hash::make($request->password);

        $contato->email = $request->email;
        $contato->telefone = $request->telefone;

        $endereco->bairro = $request->bairro;
        $endereco->rua = $request->rua;
        $endereco->numero = $request->numero;
        $endereco->cep = $request->cep;

        $contato->update();
        $endereco->update();
        $usuario->update();

        return response()->json([
            'data' => [
                'status'  => "Usuário Atualizado com Sucesso!"
            ]
        ]);
    }

    public function index(){
        $users = User::all();
        return response()->json($users);
    }

    public function verificaUsuario(Request $request)
    {
        $user = DB::table('users')->where('cpf', $request->cpf)
        ->whereNotNull('organizacao_controle_social_id')->get();
        if($user->isEmpty()){
            return response()->json(['erro' => 'Usuário não encontrado'],404);
        }
        return response()->json(true,200);
    }
}
