<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => [
                'required', 'regex:/^[a-zA-ZÀ-ÿ\s]+$/', 'min:3', 'max:50'  // regex para validar apenas letras do alfabeto (maiúsculas e minúsculas, com acento ou não) e espaços em branco.
            ],
            'email' => [
                'required', 'string', 'email', 'unique:users'
            ],
            'password' => [
                'required', 'string', 'min:8', 'max:30'
            ],
            'apelido' => [
                'nullable', 'string', 'min:3', 'max:30'
            ],
            'telefone' => [
                'required', 'regex:/^\(\d{2}\)\s\d{4,5}\-\d{4}$/', 'unique:users' // considerando telefone no formato "(99) 99999-9999"
            ],
            'cpf' => [
                'nullable', 'regex:/^\d{3}\.\d{3}\.\d{3}\-\d{2}$/', 'unique:users' // considerando cpf no formato "999.999.999-99"
            ],
            'cnpj' => [
                'nullable', 'regex:/^\d{2}\.\d{3}\.\d{3}\/\d{4}\-\d{2}$/', 'unique:users' // considerando cnpj no formato "99.999.999/9999-99"
            ],
            'rua' => [
                'required', 'regex:/^[a-zA-ZÀ-ÿ\s]+$/', 'max:50'  // mesmo do "name"
            ],
            'bairro' => [
                'required', 'regex:/^[a-zA-ZÀ-ÿ\s]+$/', 'max:50'
            ],
            'cep' => [
                'required', 'regex:/^\d{5}\-\d{3}$/' // considerando cep no formato "99999-999"
            ],
            'numero' => [
                'required', 'integer', 'max:5'
            ]
        ];
    }

    public function messages() 
    {
        return [
            'required' => 'O campo :attribute é obrigatório.',
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
