<?php

namespace App\Http\Controllers\Api;

use App\Models\Feira;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFeiraRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class FeiraController extends Controller
{

    public function index()
    {
        $feiras = Feira::all();

        return response()->json(['feiras' => $feiras]);
    }


    public function store(StoreFeiraRequest $request)
    {
        $validatedData = $request->validated();

        DB::beginTransaction();
        $feira = Feira::create($validatedData);

        // Imagem
        if ($request->hasFile('imagem')) {
            $imagem = $request->file('imagem');
            $nomeImagem = $feira->id . '.' . $imagem->getClientOriginalExtension();

            $caminho = $imagem->storeAs('public/uploads/imagens/feira', $nomeImagem); // O caminho completo é storage/app/public/uploads/imagens/feira.

            if (!$caminho) {
                DB::rollBack();
                return response()->json(['error' => 'Não foi possível fazer upload da imagem.'], 500);
            }

            $feira->imagem()->create(['caminho' => $caminho]);
        }
        DB::commit();

        return response()->json(['feira' => $feira], 201);
    }

    public function update(StoreFeiraRequest $request, $id)
    {
        $validatedData = $request->validated();
        $feira = Feira::findOrFail($id);

        DB::beginTransaction();
        $feira->update($validatedData);

        // Imagem
        if ($request->hasFile('imagem')) {
            $imagem = $request->file('imagem');
            $nomeImagem = $feira->id . '.' . $imagem->getClientOriginalExtension();
            $imagensAntigas = glob(storage_path("app/public/uploads/imagens/feira/{$feira->id}.*"));

            $caminho = $imagem->storeAs('public/uploads/imagens/feira', $nomeImagem); // O caminho completo é storage/app/public/uploads/imagens/feira.

            if (!$caminho) {
                DB::rollBack();
                return response()->json(['error' => 'Não foi possível fazer upload da imagem.'], 500);
            }

            $feira->imagem()->updateOrCreate(['imageable_id' => $feira->id, 'imageable_type' => 'feira'], ['caminho' => $caminho]);

            foreach ($imagensAntigas as $arquivo) {
                if (basename($arquivo) != $nomeImagem) {
                    File::delete($arquivo);
                }
            }
        }
        DB::commit();

        return response()->json(['feira'=> $feira], 200);
    }

    public function destroy($id)
    {
        $feira = Feira::findOrFail($id);

        $feira->delete();

        return response()->noContent();
    }

    public function getImagem($id)
    {
        $imagem = feira::findOrFail($id)->imagem;

        if (!$imagem || !Storage::exists($imagem->caminho)) {
            return response()->json(["error" => "imagem não encontrada."], 404);
        }

        $file = Storage::get($imagem->caminho);
        $mimeType = Storage::mimeType($imagem->caminho);

        return response($file)->header('Content-Type', $mimeType);
    }

    public function deleteImagem($id)
    {
        $feira = Feira::findOrFail($id);
        $this->authorize('deleteImagem', $feira);

        $imagem = $feira->imagem;

        if (!$imagem) {
            return response()->json(['error' => 'Imagem não encontrada.'], 404);
        }

        $imagens = glob(storage_path('app/') . $imagem->caminho);

        foreach ($imagens as $arquivo) {
            File::delete($arquivo);
        }

        $imagem->delete();

        return response()->noContent();
    }
}
