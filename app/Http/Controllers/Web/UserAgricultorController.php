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
        return view('agricultor.agricultores_index', ['agricultores' => $agricultores, "organizacoes" => $organizacoes]);
    }

    public function vincularAgricultorOrganizacao(VincularAgricultorRequest $request, $id)
    {
        $agricultor = User::findOrFail($id);
        $agricultor->associate(OrganizacaoControleSocial::findOrFail($request->organizacao_id));

        return redirect(route('agricultores.index'))->with('sucesso', 'Organização vinculada com sucesso!');
    }
}
