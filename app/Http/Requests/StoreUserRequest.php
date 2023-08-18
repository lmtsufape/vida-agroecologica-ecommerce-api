<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function prepareForValidation()
    {
        $roles = $this->input('roles');

        if (!is_array($roles)) {
            $campo = explode(',', $roles); // Transforma o valor em um array
            $this->merge(['roles' => $campo]); // Atualiza o valor no request
        }
    }

    public function rules()
    {
        return [
            'name' => [
                'required',
                'regex:/^[a-zA-ZÀ-ÿ\s]+$/',  // regex para validar apenas letras do alfabeto (maiúsculas e minúsculas, com acento ou não) e espaços em branco.
                'min:3',
                'max:60'
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:60',
                'unique:users'
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'max:30'
            ],
            'apelido' => [
                'nullable',
                'string',
                'max:30'
            ],
            'telefone' => [
                'required',
                'regex:/^\(\d{2}\)\s\d{5}\-\d{4}$/' // considerando telefone no formato "(99) 99999-9999"
            ],
            'cpf' => [
                'required',
                'regex:/^\d{3}\.\d{3}\.\d{3}\-\d{2}$/', // considerando cpf no formato "999.999.999-99"
                'unique:users'
            ],
            'roles' => [
                'required',
                'array',
                'min:1'
            ],
            'roles.*' => [
                'integer',
                'exists:roles,id'
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
        ];
    }
}
