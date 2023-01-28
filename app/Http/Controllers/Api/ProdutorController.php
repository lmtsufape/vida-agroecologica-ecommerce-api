<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;


use App\Models\Produtor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use function PHPSTORM_META\map;

class ProdutorController extends Controller
{
    public function index()
    {
        $produtores = Produtor::join("users", "users.papel_id", "=", "produtors.id")->orderBy('name')
        ->get();
        if(!$produtores){
            return response()->json(['erro'=>'Nenhum usuário cadastrado'],200);
        }
        return response()->json(['usuários' => $produtores],200);
    }
    public function store(Request $request)
    {
        DB::beginTransaction();   
        $produtor = new Produtor();
        $produtor->save();
        
        $produtor = $produtor->user()->create($request->except('passowrd'));
        if(!$produtor){
            return response()->json(['erro' =>'Não foi possível criar o usuário'],400);
        }
        $produtor->password = Hash::make($request->password);
        $produtor->save();
        $produtor->user;
        DB::commit();
        return response()->json(['usuário' => $produtor],201);
    }
    public function show($id)
    {
        $produtor = Produtor::find($id);
        $produtor->user;
        if(!$produtor){
            return response()->json(['erro'=>'Usuário não encontrado'],404);
        }
      
        return response()->json(['usuário' => $produtor],200);
    } 
    public function update(Request $request)
    {

        DB::beginTransaction();

        $produtor = Produtor::find($request->produtor);
        if(!$produtor){

            return response()->json(['erro'=>'Usuário não encontrado'],404);
        }
        $produtor->fill($request->all());
        $produtor->save();
        $produtor->user;
        DB::commit();
        return response()->json([$produtor],200);
    }
    public function destroy($id)
    {
        DB::beginTransaction();
        $produtor = Produtor::find($id);
        $produtor->delete();
        DB::commit();
        return response()->noContent();
        
    }

}
