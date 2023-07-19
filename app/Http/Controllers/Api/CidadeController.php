<?php


namespace App\Http\Controllers\Api;
use App\Models\Cidade;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CidadeController extends Controller
{
    public function store(Request $request)
    {
        Cidade::create($request->all());
        return response()->json(['cidade' => $request->all()]);
    }

}
