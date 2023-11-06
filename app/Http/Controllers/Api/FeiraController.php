<?php

namespace App\Http\Controllers\Api;

use App\Models\Feira;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFeiraRequest;
use App\Services\ImageService;
use Illuminate\Support\Facades\DB;

class FeiraController extends Controller
{
    protected $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
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
            $this->imageService->storeImage($request->file('imagem'), $feira); // Armazenar a imagem
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
            $this->imageService->updateImage($request->file('imagem'), $feira); // Atualizar a imagem
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
        $feira = Feira::findOrFail($id);

        $dados = $this->imageService->getImage($feira);

        return response($dados['file'])->header('Content-Type', $dados['mimeType']);
    }

    public function deleteImagem($id)
    {
        $feira = Feira::findOrFail($id);
        $this->authorize('deleteImagem', $feira);

        $this->imageService->deleteImage($feira);

        return response()->noContent();
    }
}
