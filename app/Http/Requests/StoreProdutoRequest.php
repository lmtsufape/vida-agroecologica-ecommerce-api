<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreProdutoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {

        return Auth::user()->papel_type == "Produtor" ? true : false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'nome' => 'required|min:3|max:25|string',
            'descricao' =>  'required|max:250|string',
            'tipo_unidade' => 'required',
            'estoque' => 'required|integer',
            'preco' => 'required|decimal:1,3',
            'custo' => 'required|decimal:1,3',

        ];
    }
    public function messages()
    {
        return [
            'required' => ':attribute é um parâmetro obrigatório.',
            'max' => ':attribute deve ter no máximo :max caracteres.',
            'min' => ':attribute deve ter no no mínimo:min  caracteres.',
            'string' => ':attribute deve ser uma string.',
            'integer' =>':attribute deve ser inteiro.',
            'decimal' =>':attribute deve ser decimal.',
        ];
    }
}
