<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReuniaoRequest;
use App\Models\File;
use App\Models\Reuniao;
use App\Services\FileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReuniaoController extends Controller
{
    protected $fileService;

    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }

    public function index()
    {
        $reunioes = Reuniao::all()->load(['ata', 'anexos', 'participantes']);

        return response()->json(['reunioes' => $reunioes]);
    }

    public function store(StoreReuniaoRequest $request)
    {
        $validatedData = $request->validated();

        DB::beginTransaction();
        $reuniao = Reuniao::create($validatedData);
        $reuniao->participantes()->sync($validatedData['participantes']);
        DB::commit();

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
        
        DB::beginTransaction();
        $reuniao->update($validatedData);
        $reuniao->participantes()->sync($validatedData['participantes']);
        DB::commit();

        return response()->json(['reuniao' => $reuniao]);
    }

    public function destroy($id)
    {
        $reuniao = Reuniao::findOrFail($id);

        DB::beginTransaction();
        $this->fileService->deleteAllFiles($reuniao);
        $reuniao->delete();
        DB::commit();

        return response()->noContent();
    }

    public function anexarAta(Request $request, $id)
    {
        $request->validate(['ata' => 'required|file|mimes:jpeg,png,pdf|max:2048']);

        $reuniao = Reuniao::findOrFail($id);
        $ata = $request->file('ata');

        if (!$reuniao->ata()->exists()) {
            $this->fileService->storeFile($ata, $reuniao, '/atas');
        } else {
            $this->fileService->updateFile($ata, $reuniao->ata()->first());
        }

        return response()->json(['success' => 'Ata anexada com sucesso'], 200);
    }

    public function verAta($id)
    {
        $reuniao = Reuniao::findOrFail($id);
        $dados = $this->fileService->getFile($reuniao->ata->first());

        return response($dados['file'])->header('Content-Type', $dados['mimeType']);
    }

    public function deletarAta($id)
    {
        $reuniao = Reuniao::findOrFail($id);
        $this->fileService->deleteFile($reuniao->ata()->first());

        return response()->noContent();
    }

    public function enviarAnexos(Request $request, $id)
    {
        $request->validate([
            'anexos' => 'required|array|min:1',
            'anexos.*' => 'file|max:2048'
        ]);

        $reuniao = Reuniao::findOrFail($id);
        $this->fileService->storeFile($request->file('anexos'), $reuniao, '/anexos');
    }

    public function atualizarAnexo(Request $request, $arquivo_id)
    {
        $request->validate([
            'anexo' => 'required|file|max:2048'
        ]);

        $fileInfo = File::findOrFail($arquivo_id);
        $this->fileService->updateFile($request->file('anexo'), $fileInfo);

        return response()->json(['success' => 'Anexo atualizado com sucesso.']);
    }
}
