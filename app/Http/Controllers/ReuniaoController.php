<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReuniaoRequest;
use App\Models\Imagem;
use App\Models\Reuniao;
use App\Services\ImageService;
use Illuminate\Http\Request;

class ReuniaoController extends Controller
{
    protected $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function index()
    {
        $reunioes = Reuniao::all()->load(['ata', 'anexos']);

        return response()->json(['reunioes' => $reunioes]);
    }

    public function store(StoreReuniaoRequest $request)
    {
        $validatedData = $request->validated();

        $reuniao = Reuniao::create($validatedData);

        if ($request->user()->hasAnyRoles(['administrador', 'presidente', 'secretario'])) {
            $reuniao->status = 'Aprovada';
            $reuniao->save();
        }

        return response()->json(['reuniao' => $reuniao], 201);
    }

    public function update(StoreReuniaoRequest $request, $id)
    {
        $validatedData = $request->validated();
        $reuniao = Reuniao::findOrFail($id);

        $reuniao->update($validatedData);

        return response()->json(['reuniao' => $reuniao]);
    }

    public function destroy($id)
    {
        $reuniao = Reuniao::findOrFail($id);

        $this->imageService->deleteAllFiles($reuniao);
        $reuniao->delete();

        return response()->noContent();
    }

    public function anexarAta(Request $request, $id)
    {
        $request->validate(['ata' => 'required|file|mimes:jpeg,png,pdf|max:2048']);

        $reuniao = Reuniao::findOrFail($id);
        $ata = $request->file('ata');

        if (!$reuniao->ata()) {
            $this->imageService->storeImage(array($ata), $reuniao, '/atas');
        } else {
            $this->imageService->updateImage($ata, $reuniao->ata());
        }

        return response()->json(['success' => 'Ata anexada com sucesso'], 200);
    }

    public function verAta($id)
    {
        $fileInfo = Imagem::findOrFail($id);
        $dados = $this->imageService->getImage($fileInfo);

        return response($dados['file'])->header('Content-Type', $dados['mimeType']);
    }

    public function deletarAta($id)
    {
        $fileInfo = Imagem::findOrFail($id);
        $this->imageService->deleteImage($fileInfo);

        return response()->noContent();
    }

    public function enviarAnexos(Request $request, $id)
    {
        $request->validate([
            'anexos' => 'required|array|min:1',
            'anexos.*' => 'file|max:2048'
        ]);

        $reuniao = Reuniao::findOrFail($id);
        $this->imageService->storeImage($request->file('anexos'), $reuniao, '/anexos');
    }

    public function atualizarAnexo(Request $request, $arquivo_id)
    {
        $request->validate([
            'anexo' => 'required|file|max:2048'
        ]);

        $fileInfo = Imagem::findOrFail($arquivo_id);
        $this->imageService->updateImage($request->file('anexo'), $fileInfo);

        return response()->json(['success' => 'Anexo atualizado com sucesso.']);
    }
}
