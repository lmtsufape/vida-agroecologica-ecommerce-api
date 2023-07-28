<?php

namespace App\Http\Controllers;

use App\Models\Associacao;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function usuarios_index()
    {
        $users = User::whereHas('roles', function ($query) {
            $query->whereIn('nome', ['presidente', 'administrador']);
        })->with('contato')->get();

        return view('admin.usuarios_index', compact('users'));
    }

    public function associacoes_index()
    {
        $associacoes = Associacao::all();
        $presidentes = User::whereHas('roles', function ($query) {
            $query->whereIn('nome', ['presidente', 'administrador']);
        })->get();

        return view('admin.associacoes_index', compact('associacoes', 'presidentes'));
    }

}
