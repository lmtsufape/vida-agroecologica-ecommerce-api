<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBancaRequest;
use App\Models\Banca;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

        try {
            $banca = $user->papel->banca()->create($request->all());

            DB::commit();
            return response()->json(['banca' => $banca], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Ocorreu um erro ao salvar a banca']);
        }
    }

    public function show($id)
    {
        $banca = Banca::find($id);

        if (!$banca) {
            return response()->json(['message' =>  'Banca não encontrada.']);
        }

        return response()->json(['banca' => $banca]);
    }

    public function update(StoreBancaRequest $request, $id)
    {
        $banca = Banca::find($id);

        if (!$banca) {
            return response()->json(['message' =>  'Banca não encontrada.']);
        }

        $banca->update($request->all());

        return response()->json(['banca' => $banca]);
    }

    public function destroy($id)
    {
        Auth::user()->papel->banca()->delete();

        return response()->json(true);
    }
}
