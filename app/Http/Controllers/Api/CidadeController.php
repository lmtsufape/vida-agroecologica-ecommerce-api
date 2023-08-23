<?php


namespace App\Http\Controllers\Api;

use App\Models\Cidade;
use App\Models\Bairro;
use App\Models\Endereco;
use App\Models\Feira;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreCidadeRequest;

class CidadeController extends Controller
{
    public function index()
    {
        $cidades = Cidade::all();

        return response()->json(['cidades' => $cidades], 200);
    }

    public function store(StoreCidadeRequest $request)
    {
        $validatedData = $request->validated();
        $cidade = Cidade::create($validatedData);

        return response()->json(['cidade' => $cidade], 201);
    }

    public function destroy($id)
    {
        $cidade = Cidade::findOrFail($id);
        $bairros = Bairro::where('cidade_id', $cidade->id)->get();
        $deletar = true;
        if(sizeof($bairros) != 0){
            foreach($bairros as $bairro){
                $feira = Feira::where('bairro_id', $bairro->id)->first();
                $endereco = Endereco::where('bairro_id', $bairro->id)->first();
                if($feira || $endereco){
                    $deletar = false;
                    break;
                }

            }
            if($deletar){
                $cidade->delete();
                return response()->noContent();
            }else{
                return response()->json("Não é possivel deletar. Feira ou Endereço estão vinculados.");
            }


        }else{
            $cidade->delete();
            return response()->noContent();
        }


    }
}
