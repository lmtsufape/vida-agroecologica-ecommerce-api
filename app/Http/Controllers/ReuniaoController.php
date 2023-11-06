<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReuniaoRequest;
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
        $reunioes = Reuniao::all();

        return response()->json(['reunioes'=> $reunioes]);
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
        
        $reuniao->delete();

        return response()->noContent();
    }

    public function anexarAta(Request $request, $id)
    {
        $request->validate(['ata' => 'required|file|mimes:jpeg,png,pdf|max:2048']);

        $reuniao = Reuniao::findOrFail($id);
        $ata = $request->file('ata');

        $this->imageService->updateImage($ata, $reuniao);

        return response()->json(['success' => 'Ata anexada com sucesso'], 200);
    }
}

