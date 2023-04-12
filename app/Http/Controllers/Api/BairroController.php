<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Bairro;
use Illuminate\Http\Request;

class BairrosController extends Controller
{
    public function index()
    {
        $bairros = Bairro::all();

        return response()->json(['bairros' => $bairros]);
    }
}
