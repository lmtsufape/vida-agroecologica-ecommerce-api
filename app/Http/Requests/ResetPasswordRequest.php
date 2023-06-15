<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
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
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|regex:/^(?=.*[a-zA-Z])(?=.*\d).+$/|confirmed',
        ];
    }

    public function messages()
    {
        return [
            'email.email' => 'O endereço de email precisa ser válido.',
            'password.min' => 'A senha precisa ter no mínimo 8 dígitos.',
            'password.regex' => 'A senha precisa conter letras e números.',
            'password.confirmed' => 'As senhas não são iguais.'
        ];
    }
}
