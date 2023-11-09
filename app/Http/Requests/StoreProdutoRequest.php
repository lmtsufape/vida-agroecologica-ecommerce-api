<?php

namespace App\Http\Requests;

use App\Models\Banca;
use Illuminate\Foundation\Http\FormRequest;

class StoreProdutoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $banca = Banca::findOrFail($this->input('banca_id'));

        return $this->user()->can('create', [Produto::class, $banca]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'descricao' =>  'required|max:120|string',
            'tipo_medida' => 'required|in:unidade,fracionario,peso',
            'estoque' => 'required|numeric',
            'preco' => 'required|decimal:2',
            'custo' => 'required|decimal:2',
            'banca_id' => 'required|integer|exists:bancas,id',
            'produto_tabelado_id' => 'required|integer|exists:produtos_tabelados,id'
        ];
    }
    public function messages()
    {
        return [
            'required' => ':attribute é um parâmetro obrigatório.',
            'max' => ':attribute deve ter no máximo :max caracteres.',
            'min' => ':attribute deve ter no no mínimo:min  caracteres.',
            'string' => ':attribute deve ser uma string.',
            'integer' => ':attribute deve ser inteiro.',
            'decimal' => ':attribute deve ser decimal.',
            'in' => ':attribute deve está entre :values'
        ];
    }
}
