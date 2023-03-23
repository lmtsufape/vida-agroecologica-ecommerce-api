<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEnderecoRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'rua' => [
                'nullable',
                'regex:/^[a-zA-ZÀ-ÿ\s]+$/',  // mesmo do "name"
                'max:50'
            ],
            'cep' => [
                'nullable',
                'regex:/^\d{5}\-\d{3}$/' // considerando cep no formato "99999-999"
            ],
            'numero' => [
                'nullable',
                'integer',
                'max:5'
            ],
            'complemento' => [
                'nullable',
                'string',
                'max:50'
            ],
            'bairro_id' => [
                'nullable',
                'integer',
                'max:5'
            ]
        ];
    }

    public function messages()
    {
        return [
            'max' => 'O campo :attribute deve ter no máximo :max caracteres.',
            'min' => 'O campo :attribute deve ter no mínimo :min caracteres.',
            'string' => 'O campo :attribute deve ser uma string.',
            'integer' => 'O campo :attribute deve ser numérico.',
            'unique' => 'O campo :attribute está sendo utilizado.',
            'email' => 'O email precisa ser válido.',
            'telefone.regex' => 'O campo telefone deve estar no formato (99) 99999-9999.',
            'cpf.regex' => 'O campo CPF deve estar no formato 999.999.999-99.',
            'cnpj.regex' => 'O campo CNPJ deve estar no formato 99.999.999/9999-99.',
            'cep.regex' => 'O campo CEP deve estar no formato 99999-999.',
        ];
    }
}
