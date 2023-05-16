<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBancaRequest;
use App\Models\Banca;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class BancaController extends Controller
{

    public function index()
    {
        $bancas = Banca::all();

        return response()->json(['bancas' => $bancas]);
    }

    public function store(StoreBancaRequest $request)
    {
        $user = Auth::user();
        DB::beginTransaction();
        $banca = $user->papel->banca()->create($request->all());

        // Imagem
        if ($request->hasFile('imagem')) {
            $imagem = $request->file('imagem');
            $nomeImagem = $user->id . '.' . $imagem->getClientOriginalExtension();

            $caminho = $imagem->storeAs('public/uploads/imagens/banca', $nomeImagem); // O caminho completo é storage/app/public/uploads/imagens/banca.

            if (!$caminho) {
                DB::rollBack();
                return response()->json(['erro' => 'Não foi possível fazer upload da imagem'], 500);
            }

            $banca->imagem()->create(["caminho" => $caminho]);
        }
        DB::commit();
        return response()->json(["banca" => $banca], 201);
    }

    public function show($id)
    {
        $banca = Banca::find($id);

        if (!$banca) {
            return response()->json(['message' =>  'Banca não encontrada.']);
        }

        return response()->json(['banca' => $banca]);
    }

    public function update(StoreBancaRequest $request)
    {
        $user = Auth::user();
        $banca = $user->papel->banca;
        DB::beginTransaction();
        $banca->update($request->all());

        // Imagem
        if ($request->hasFile('imagem')) {
            $imagem = $request->file('imagem');
            $nomeImagem = $user->id . '.' . $imagem->getClientOriginalExtension();
            $imagensAntigas = glob(storage_path("app/public/uploads/imagens/banca/{$user->id}.*"));

            $caminho = $imagem->storeAs('public/uploads/imagens/banca', $nomeImagem); // O caminho completo é storage/app/public/uploads/imagens/banca.

            if (!$caminho) {
                DB::rollBack();
                return response()->json(['erro' => 'Não foi possível fazer upload da imagem'], 500);
            }

            $imagemBanco = $banca->imagem()->FirstOrNew();
            $imagemBanco->caminho = $caminho;
            $imagemBanco->save();

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
        Auth::user()->papel->banca()->delete();

        return response()->json(true);
    }

    public function getImagem($id)
    {
        $imagem = Banca::findOrFail($id)->imagem;

        if (!$imagem || !file_exists(storage_path('app/') . $imagem->caminho)) {
            abort(404);
        }

        $file = Storage::get($imagem->caminho);
        $mimeType = Storage::mimeType($imagem->caminho);

        return response($file)->header('Content-Type', $mimeType);
    }

    public function deleteImagem()
    {
        $imagem = Auth::user()->papel->banca->imagem;

        if (!$imagem) {
            return response()->json(['erro' => 'Imagem não encontrada.']);
        }

        $imagens = glob(storage_path('app/') . $imagem->caminho);

        foreach ($imagens as $arquivo) {
            File::delete($arquivo);
        }

        $imagem->delete();

        return response()->json(['sucesso' => 'Imagem deletada.'], 200);
    }
}
