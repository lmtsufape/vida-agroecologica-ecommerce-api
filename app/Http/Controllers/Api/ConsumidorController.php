<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Consumidor;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ConsumidorController extends Controller
{
    public function index()
    {
        $consumidores = User::where("users.papel_type", "=", "Consumidor")->join("consumidores", "consumidores.id", "=", "users.papel_id")
            ->orderBy('name')
            ->get();
        if (!$consumidores) {
            return response()->json(['erro' => 'Nenhum consumidor cadastrado'], 200);
        }
        return response()->json(['usuários' => $consumidores], 200);
    }

    public function store(StoreUserRequest $request)
    {
        $consumidorRole = Role::where('nome', 'consumidor')->first();

        DB::beginTransaction();
        $consumidor = User::make($request->except('password'));
        $consumidor->password = Hash::make($request->password);
        $consumidor->save();
        $consumidor->contato()->create($request->all());
        $consumidor->roles()->attach($consumidorRole);
        DB::commit();

        event(new Registered($consumidor));

        return response()->json(['usuário' => $consumidor], 201);
    }

    public function show($id)
    {
        $consumidor = Consumidor::find($id);
        $consumidor->user;
        if (!$consumidor) {
            return response()->json(['erro' => 'Usuário não encontrado'], 404);
        }
        return response()->json(['usuário' => $consumidor], 200);
    }

    public function update(UpdateUserRequest $request, $id)
    {
        $user = $request->user();
        $consumidor = Consumidor::findOrFail($id);
        if ($user->cannot('update', $consumidor)) {
            abort(403);
        }

        DB::beginTransaction();
        $consumidor->user()->update($request->only('nome', 'apelido', 'telefone'));
        $consumidor->user->endereco()->update($request->only(
            'rua',
            'numero',
            'cep',
            'complemento',
            'cidade',
            'estado',
            'país',
            'bairro_id'
        ));
        $consumidor->user;
        DB::commit();
        return response()->json([$consumidor], 200);
    }

    public function destroy($id)
    {
        $user = User::find(Auth::user()->id);
        $consumidor = Consumidor::findOrFail($id);
        if ($user->cannot('delete', $consumidor)) {
            abort(403);
        }

        DB::beginTransaction();
        $consumidor->user->delete();
        $consumidor->delete();
        DB::commit();
        return response()->noContent();
    }
}
