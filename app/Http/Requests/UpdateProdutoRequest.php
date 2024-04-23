<?php

namespace App\Http\Requests;

use App\Models\Produto;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProdutoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $produto = Produto::findOrFail($this->route('produto'));

        return $this->user()->can('update', $produto);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'descricao' => 'nullable|string',
            'titulo' => 'required|max:50|string',
            'tipo_medida' => 'required|in:Unidade,Fracionario,Peso,Molho,Kg,Litro,Pote,Dúzia,Mão,Arroba,Bandeja',
            'estoque' => 'nullable|numeric',
            'preco' => 'nullable|decimal:2',
            'custo' => 'nullable|decimal:2',
            'disponivel' => 'nullable|bool'
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
