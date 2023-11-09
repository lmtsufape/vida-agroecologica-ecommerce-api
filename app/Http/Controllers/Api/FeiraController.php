<?php

namespace App\Http\Controllers\Api;

use App\Models\Feira;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFeiraRequest;
use App\Services\FileService;
use Illuminate\Support\Facades\DB;

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

    public function update(StoreFeiraRequest $request, $id)
    {
        $validatedData = $request->validated();
        $feira = Feira::findOrFail($id);

        DB::beginTransaction();
        $feira->update($validatedData);

        if ($request->hasFile('imagem')) {
            $this->fileService->updateFile($request->file('imagem'), $feira); // Atualizar a imagem
        }
        DB::commit();

        return response()->json(['feira'=> $feira], 200);
    }

    public function destroy($id)
    {
        $feira = Feira::findOrFail($id);

        $this->fileService->deleteFile($feira);
        $feira->delete();

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
}
