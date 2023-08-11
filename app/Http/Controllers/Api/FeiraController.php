<?php

namespace App\Http\Controllers\Api;

use App\Models\Feira;
use App\Models\Bairro;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFeiraRequest;
use Illuminate\Http\Request;

class FeiraController extends Controller
{

    public function index()
    {
        $feiras = Feira::all();

        return response()->json(['feiras' => $feiras]);
    }


    public function store(StoreFeiraRequest $request)
    {
        $validatedData = $request->validated();
        $feira = Feira::create($validatedData);

        return response()->json(['feira' => $feira], 201);
    }
}
