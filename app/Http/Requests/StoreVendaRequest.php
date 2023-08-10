<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVendaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('create', Venda::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'tipo_entrega' => 'required|string|in:retirada,entrega',
            'banca_id' => 'required|integer|exists:bancas,id',
            'forma_pagamento_id' => 'required|integer|exists:formas_pagamento,id',
            'endereco_id' => 'nullable|integer|exists:enderecos,id',
            'produtos' => 'required|array|min:1',
            'produtos.*' => 'array|size:2',
            'produtos.*.*' => 'integer',
            'produtos.*.0' => 'exists:produtos,id'
        ];
    }
}
