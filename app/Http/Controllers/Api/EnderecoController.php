<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEnderecoRequest;
use App\Models\Endereco;
use Illuminate\Support\Facades\Auth;

class EnderecoController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Endereco::class);
        $enderecos = Endereco::all();

        return response()->json(['enderecos' => $enderecos], 200);
    }

    public function indexUser()
    {
        $enderecos = Auth::user()->enderecos;

        return response()->json(['enderecos' => $enderecos], 200);
    }

    public function show($id)
    {
        $endereco = Endereco::findOrFail($id);
        $this->authorize('view', $endereco);

        return response()->json(['endereco' => $endereco], 200);
    }

    public function update(StoreEnderecoRequest $request, $id)
    {
        $validatedData = $request->validated();

        $endereco = Endereco::findOrFail($id);
        $this->authorize('update', $endereco);

        $endereco->update($validatedData);

        return response()->json(['endereco' => $endereco], 200);
    }

    public function destroy($id)
    {
        $endereco = Endereco::findOrFail($id);
        $this->authorize('delete', $endereco);

        $endereco->delete();

        return response()->noContent();
    }
}
