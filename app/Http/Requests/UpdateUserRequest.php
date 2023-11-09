<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $model = User::findOrFail($this->route('user'));
        return $this->user()->can('update', $model);
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
                'required',
                'regex:/^[a-zA-ZÀ-ÿ\s]+$/',  // regex para validar apenas letras do alfabeto (maiúsculas e minúsculas, com acento ou não) e espaços em branco.
                'min:3',
                'max:60'
            ],
            'telefone' => [
                'required',
                'regex:/^\(\d{2}\)\s\d{5}\-\d{4}$/', // considerando telefone no formato "(99) 99999-9999"
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
            'telefone.regex' => 'O campo telefone deve estar no formato (99) 99999-9999.',
        ];
    }
}
