<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAssociacaoRequest extends FormRequest
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
            "nome"              => ['required', 'string', 'min:10', 'max:255', 'regex:/^[A-Za-záâãéêíóôõúçÁÂÃÉÊÍÓÔÕÚÇ\s]+$/'],
            "codigo"            => ['required', 'min:1', 'max:10'],
            "presidente"        => ['required', 'numeric'],

            "email"             => ['required', 'string', 'email', 'max:255', 'unique:users'],
            "telefone"          => ['required', "celular_com_ddd"]
        ];
    }
}
