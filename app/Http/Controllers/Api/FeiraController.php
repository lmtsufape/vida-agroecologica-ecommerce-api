<?php

namespace App\Http\Controllers;

use App\Models\Feira;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class FeiraController extends Controller
{
    public function store(Request $request){
        $feira = Feira::create($request->all());
        $bairro = Bairro::findOrFail($request->bairro_id);
        $feira->bairro()->associate($bairro);

        return response()->json(['feira' => $feira]);
    }
}
