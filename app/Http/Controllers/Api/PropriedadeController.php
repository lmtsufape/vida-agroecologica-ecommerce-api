<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Propriedade;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePropriedadeRequest;
use App\Http\Requests\UpdatePropriedadeRequest;
use Illuminate\Support\Facades\DB;

class PropriedadeController extends Controller
{
    public function index()
    {
        $propriedades = Propriedade::all();
        $this->authorize('viewAny', Propriedade::class);

        return response()->json(['propriedades' => $propriedades], 200);
    }

    public function store(StorePropriedadeRequest $request)
    {
        $validatedData = $request->validated();
        $user = $request->user();

        DB::beginTransaction();
        $propriedade = $user->propriedades()->create($validatedData);
        $propriedade->endereco()->create($validatedData);
        DB::commit();

        return response()->json(['propriedade' => $propriedade], 201);
    }

    public function show($id)
    {
        $propriedade = Propriedade::findOrFail($id);
        $this->authorize('view', $propriedade);

        return response()->json(['propriedade' => $propriedade], 200);
    }

    public function update(UpdatePropriedadeRequest $request, $id)
    {
        $validatedData = $request->validated();
        $propriedade = Propriedade::findOrFail($id);

        DB::beginTransaction();
        $propriedade->update($validatedData);
        $propriedade->endereco->update($validatedData);
        DB::commit();

        return response()->json(['propriedade' => $propriedade], 200);
    }

    public function destroy($id)
    {
        $propriedade = Propriedade::findOrFail($id);
        $this->authorize('delete', $propriedade);

        DB::beginTransaction();
        $propriedade->endereco->delete();
        $propriedade->delete();
        DB::commit();

        return response()->noContent();
    }

    public function getPropriedades($agricultor_id)
    {
        $agricultor = User::findOrFail($agricultor_id);
        $this->authorize('getPropriedades', [Propriedade::class, $agricultor]);

        $propriedades = $agricultor->propriedades;

        return response()->json(['propriedades' => $propriedades], 200);
    }
}
