<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAssociacaoRequest extends StoreEnderecoRequest
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
        return array_merge(parent::rules(), [
            "nome"              => ['required', 'string', 'min:10', 'max:255', 'regex:/^[A-Za-záâãéêíóôõúçÁÂÃÉÊÍÓÔÕÚÇ\s]+$/'],
            "data_fundacao"     => ['required', 'date'],
            "email"             => ['nullable', 'email', 'max:60', 'unique:contatos'],
            "telefone"          => ['nullable', "celular_com_ddd"],
            "presidentes_id"    => ['required', 'array', 'min:1'],
            "secretarios_id"    => ['required', 'array'],
        ]);
    }
}
