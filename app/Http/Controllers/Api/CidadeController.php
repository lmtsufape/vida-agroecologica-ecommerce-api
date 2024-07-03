<?php


namespace App\Http\Controllers\Api;

use App\Models\Cidade;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCidadeRequest;
use App\Http\Requests\UpdateCidadeRequest;
use Illuminate\Http\Request;
use Exception;

class CidadeController extends Controller
{
    public function show($id)
    {
        $cidade = Cidade::with('estado')->findOrFail($id);
        return response()->json(['cidade' => $cidade]);
    }

    public function index()
    {
        $cidades = Cidade::with('estado')->get();

        return response()->json(['cidades' => $cidades], 200);
    }

    public function store(StoreCidadeRequest $request)
    {
        $validatedData = $request->validated();
        $cidade = Cidade::create($validatedData);

        return response()->json(['cidade' => $cidade], 201);
    }

    public function update(UpdateCidadeRequest $request, $id)
    {
        $cidade = Cidade::findOrFail($id);

        $cidade->update($request->all());

        return response()->json(['cidade'=> $cidade], 200);

    }
    public function destroy($id)
    {
        $cidade = Cidade::findOrFail($id);

        try {
            $cidade->delete();
        } catch (Exception $e) {
            return response()->json(['error' => 'Não foi possível deletar esta cidade.'], 400);
        }

        return response()->noContent();
    }

    public function buscar(Request $request)
    {
        $request->validate(['q' => 'required|string']);
        $cidades = Cidade::where('nome', 'ilike', "%$request->q%")->get();

        return $cidades->count() != 0 ? Response()->json(['success' => 'busca concluída', 'cidades' => $cidades], 200) : abort(404);
    }
}
