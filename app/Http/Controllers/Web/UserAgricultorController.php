<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\VincularAgricultorRequest;
use App\Models\OrganizacaoControleSocial;
use App\Models\User;

class UserAgricultorController extends Controller
{
    public function index()
    {
        $agricultores = User::with('organizacao')->whereHas('roles', function ($query) {
            $query->where('nome', 'agricultor');
        })->get();

        $organizacoes = OrganizacaoControleSocial::all();

        return response()->json(['agricultores' => $agricultores, 'organizacao' => $organizacoes]);
    }

    public function vincularAgricultorOrganizacao(VincularAgricultorRequest $request, $id)
    {
        $agricultor = User::findOrFail($id);
        $organizacao = OrganizacaoControleSocial::findOrFail($request->organizacao_id);

        $agricultor->organizacao()->associate($organizacao);
        $agricultor->save();

        return response()->json();
    }
}
