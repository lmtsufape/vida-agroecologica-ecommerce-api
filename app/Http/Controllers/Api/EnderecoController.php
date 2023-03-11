<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Models\Endereco;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EnderecoController extends Controller
{
    public function store(StoreUserRequest $request)
    {
        DB::beginTransaction();
        $user = User::find($request->userId);        
        $endereco = $user->endereco()->create($request->all());
        if(!$endereco){
            return response()->json(['erro' =>'Não foi possível criar o Endereço'],400);
        }
    
        DB::commit();
        return response()->json(['Endereço' => $endereco],201);
    }
    public function show($id)
    {
        $endereco = Endereco::find($id);
        $endereco->user;
        if(!$endereco){
        	return response()->json(['erro'=>'Endereço não encontrado'],404);
        }
        return response()->json(['Endereço' => $endereco],200);
    } 
    public function update(StoreUserRequest $request)
    {
        DB::beginTransaction();

        $endereco = Endereco::find($request->endereco);
        if(!$endereco){
            return response()->json(['erro'=>'Endereço não encontrado'],404);
        }
        $endereco->fill($request->all());
        $endereco->save();
        $endereco->user;
        DB::commit();
        return response()->json([$endereco],200);
    }
    public function destroy($id)
    {
        DB::beginTransaction();
        $endereco = Endereco::find($id);
        $endereco->delete();
        DB::commit();
        return response()->noContent();
    }
}
