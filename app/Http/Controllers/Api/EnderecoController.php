<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateEnderecoRequest;
use App\Models\Endereco;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EnderecoController extends Controller
{
    public function show()
    {
        return response()->json(['Endereço' => Auth::user()->endereco], 200);
    }

    public function update(UpdateEnderecoRequest $request)
    {
        DB::beginTransaction();

        $endereco = Auth::user()->endereco;

        $endereco->fill($request->all());
        $endereco->save();
        $endereco->user;
        DB::commit();
        return response()->json([$endereco], 200);
    }
}
