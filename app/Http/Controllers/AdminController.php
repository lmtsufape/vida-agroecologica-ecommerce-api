<?php

namespace App\Http\Controllers;

use App\Models\Associacao;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function usuarios_index(){
        $users = User::all()->except(Auth::id());
        return view('admin.usuarios_index', compact('users'));
    }

    public function associacoes_index()
    {
        $associacoes = Associacao::all();
        $presidentes = User::where('tipo_usuario_id', 2)->get();
        return view('admin.associacoes_index', compact('associacoes', 'presidentes'));
    }

}
