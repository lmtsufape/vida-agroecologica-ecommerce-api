<?php

namespace App\Services;

use App\Http\Requests\StoreEnderecoRequest;
use App\Models\Endereco;

class EnderecoService
{
    public function Criar(StoreEnderecoRequest $request)
    {
        $validatedData = $request->validated();

        $endereco = Endereco::make($validatedData);

        return $endereco;
    }
}
