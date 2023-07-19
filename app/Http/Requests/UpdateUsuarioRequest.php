<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUsuarioRequest extends FormRequest
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
            "name"              => ['required', 'string', 'min:10', 'max:255', 'regex:/^[A-Za-záâãéêíóôõúçÁÂÃÉÊÍÓÔÕÚÇ\s]+$/'],
            "cpf"               => ['required', 'cpf', 'min:11', 'max:11', Rule::unique('users')->ignore($this->usuario_id)],
            "tipo_usuario_id"   => ['required', 'numeric'],
            "usuario_id"        => ['required', 'numeric'],

            "email"             => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($this->usuario_id)],
            "telefone"          => ['required', "celular_com_ddd"],

            "bairro"            => ['required', 'string', 'min:3', "max:50"],
            "rua"               => ['required', 'string', 'min:3', "max:50"],
            "numero"            => ['required', 'numeric'],
            "cep"               => ['required', 'numeric', 'min:0', 'digits:8']
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'cpf'        => preg_replace('/[^0-9]/', '', $this->cpf),
            'cep'        => preg_replace('/[^0-9]/', '', $this->cep)
        ]);
    }
}
