<?php

namespace App\Http\Controllers\Api;

use App\Models\Feira;
use App\Models\Bairro;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FeiraController extends Controller
{

    public function index()
    {
        $feiras = Feira::All();

        return response()->json(['feiras' => $feiras]);
    }


    public function store(Request $request)
    {
        $feira = Feira::create($request->all());
        $bairro = Bairro::findOrFail($request->bairro_id);
        $feira->bairro()->associate($bairro);

        return response()->json(['feira' => $feira]);

    }
}
