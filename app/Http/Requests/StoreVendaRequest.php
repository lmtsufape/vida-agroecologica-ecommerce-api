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
        return $this->user()->papel_type == 'Consumidor';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'produtor' => 'required|exists:produtores,id',
            'tipo_entrega' => 'required|in:retirada,entrega',
            'forma_pagamento' => 'required|exists:formas_pagamento,id',
            'produtos' => 'required|array|min:1',
            'produtos.*' => 'array|size:2',
            'produtos.*.*' => 'integer',
            'produtos.*.0' => 'exists:produtos,id'
        ];
    }
}
