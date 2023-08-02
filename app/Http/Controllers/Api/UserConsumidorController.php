<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\StoreEnderecoRequest;
use App\Models\Endereco;
use App\Models\User;

class UserConsumidorController extends UserController
{
    public function storeEndereco(StoreEnderecoRequest $request, $consumidor_id)
    {
        $validatedData = $request->validated();
        $user = User::findOrFail($consumidor_id);

        $endereco = Endereco::make($validatedData);
        $user->endereco()->save($endereco);

        return response()->json(['endereco' => $endereco], 201);
    }

    public function updateEndereco(StoreEnderecoRequest $request, $endereco_id)
    {
        $validatedData = $request->validated();

        $endereco = Endereco::findOrFail($endereco_id);
        $endereco->update($validatedData);

        return response()->json(['endereco' => $endereco], 200);
    }

    public function destroyEndereco($endereco_id)
    {
        $endereco = Endereco::findOrFail($endereco_id);
        $endereco->delete();

        return response()->noContent();
    }
}
