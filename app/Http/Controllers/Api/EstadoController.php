<?php

namespace App\Http\Controllers\Api;

use App\Models\Estado;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EstadoController extends Controller
{
    public function index()
    {
        $estados = Estado::all();
        return response()->json(['estados' => $estados], 200);
    }
}
