<?php

namespace App\Http\Controllers\Api;

use App\Models\Feira;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFeiraRequest;
use App\Http\Requests\UpdateFeiraRequest;
use App\Services\FileService;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class FeiraController extends Controller
{
    protected $fileService;

    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }

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

        if ($request->hasFile('imagem')) {
            $this->fileService->storeFile($request->file('imagem'), $feira); // Armazenar a imagem
        } 
        DB::commit();

        return response()->json(['feira' => $feira], 201);
    }

    public function update(UpdateFeiraRequest $request, $id)
    {
        $validatedData = $request->validated();
        $feira = Feira::findOrFail($id);

        DB::beginTransaction();
        $feira->update($validatedData);

        //if ($request->hasFile('imagem')) {
            //$this->fileService->updateFile($request->file('imagem'), $feira); }
        //DB::commit();

        return response()->json(['feira'=> $feira], 200);
    }

    public function destroy($id)
    {
        $feira = Feira::findOrFail($id);

        if ($feira->bancas()->count() > 0) {
            return response()->json(['error' => 'Esta feira tem uma ou mais bancas associadas e não pode ser excluída.'], 400);
        }
        DB::transaction(function () use ($feira) {
            if ($feira->file) {
                $this->fileService->deleteFile($feira->file);
            }
            $feira->delete();
        });

        return response()->noContent();
    }

    
    public function getImagem($id)
    {
        $feira = Feira::findOrFail($id);

        $dados = $this->fileService->getFile($feira->file);

        return response($dados['file'])->header('Content-Type', $dados['mimeType']);
    }

    public function deleteImagem($id)
    {
        $feira = Feira::findOrFail($id);
        $this->authorize('deleteImagem', $feira);

        $this->fileService->deleteFile($feira->file);

        return response()->noContent();
    }

    public function getBancas($id)
    {
        $feira = Feira::findOrFail($id);

        $bancas = $feira->bancas;

        return response()->json(['bancas' => $bancas], 200);
    }

    public function getFeira($id)
    {
        $feira = Feira::findOrFail($id);
        return response()->json(['feira' => $feira], 200);
    }

    public function buscar(Request $request)
    {
        $request->validate(['q' => 'required|string']);
        $feiras = Feira::where('nome', 'ilike', "%$request->q%")->get();

        return $feiras->count() != 0 ? Response()->json(['success' => 'busca concluída', 'feiras' => $feiras], 200) : abort(404);
    }
}
