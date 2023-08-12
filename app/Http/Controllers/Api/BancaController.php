<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBancaRequest;
use App\Http\Requests\UpdateBancaRequest;
use App\Models\Banca;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class BancaController extends Controller
{
    public function index()
    {
        $bancas = Banca::all();

        return response()->json(['bancas' => $bancas], 200);
    }

    public function store(StoreBancaRequest $request)
    {
        $validatedData = $request->validated();

        DB::beginTransaction();
        $banca = Banca::create($validatedData);
        $banca->formasPagamento()->sync($validatedData['formas_pagamento']);
        foreach ($request->bairro_entrega as $bairro_info) {
            $banca->bairros_info_entrega()->attach($bairro_info[0], ['taxa' => $bairro_info[1]]);
        }

        // Imagem
        if ($request->hasFile('imagem')) {
            $imagem = $request->file('imagem');
            $nomeImagem = $banca->id . '.' . $imagem->getClientOriginalExtension();

            $caminho = $imagem->storeAs('public/uploads/imagens/banca', $nomeImagem); // O caminho completo é storage/app/public/uploads/imagens/banca.

            if (!$caminho) {
                DB::rollBack();
                return response()->json(['error' => 'Não foi possível fazer upload da imagem.'], 500);
            }

            $banca->imagem()->create(['caminho' => $caminho]);
        }
        DB::commit();

        return response()->json(['banca' => $banca], 201);
    }

    public function show($id)
    {
        $banca = Banca::findOrFail($id);

        return response()->json(['banca' => $banca], 200);
    }

    public function update(UpdateBancaRequest $request, $id)
    {
        $validatedData = $request->validated();
        $banca = Banca::findOrFail($id);

        DB::beginTransaction();
        $banca->update($validatedData);
        $banca->formasPagamento()->sync($validatedData['formas_pagamento']);
        foreach ($request->bairro_entrega as $bairro_info) {
            $banca->bairros_info_entrega()->detach();
            $banca->bairros_info_entrega()->attach($bairro_info[0], ['taxa' => $bairro_info[1]]);
        }

        // Imagem
        if ($request->hasFile('imagem')) {
            $imagem = $request->file('imagem');
            $nomeImagem = $banca->id . '.' . $imagem->getClientOriginalExtension();
            $imagensAntigas = glob(storage_path("app/public/uploads/imagens/banca/{$banca->id}.*"));

            $caminho = $imagem->storeAs('public/uploads/imagens/banca', $nomeImagem); // O caminho completo é storage/app/public/uploads/imagens/banca.

            if (!$caminho) {
                DB::rollBack();
                return response()->json(['error' => 'Não foi possível fazer upload da imagem.'], 500);
            }

            $imagemBanco = $banca->imagem()->updateOrCreate(['imageable_id' => $banca->id, 'imageable_type' => 'banca'], ['caminho' => $caminho]);

            foreach ($imagensAntigas as $arquivo) {
                if (basename($arquivo) != $nomeImagem) {
                    File::delete($arquivo);
                }
            }
        }
        DB::commit();

        return response()->json(['banca' => $banca], 200);
    }

    public function destroy($id)
    {
        $banca = Banca::findOrFail($id);
        $this->authorize('delete', $banca);

        $banca->delete();

        return response()->noContent();
    }

    public function getImagem($id)
    {
        $imagem = Banca::findOrFail($id)->imagem;

        if (!$imagem || !Storage::exists($imagem->caminho)) {
            return response()->json(["error" => "imagem não encontrada."], 404);
        }

        $file = Storage::get($imagem->caminho);
        $mimeType = Storage::mimeType($imagem->caminho);

        return response($file)->header('Content-Type', $mimeType);
    }

    public function deleteImagem($id)
    {
        $banca = Banca::findOrFail($id);
        $this->authorize('deleteImagem', $banca);

        $imagem = $banca->imagem;

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
