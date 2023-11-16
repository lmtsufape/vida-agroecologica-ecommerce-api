<?php

namespace App\Http\Requests;

use App\Models\Associacao;
use App\Models\Contato;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAssociacaoRequest extends FormRequest
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
            "data_fundacao"     => ['required', 'date'],
            "email"             => ['required', 'email', 'max:60', Rule::unique('contatos', 'email')->ignore(Associacao::find($this->route('id'))->contato->id)],
            "telefone"          => ['required', "celular_com_ddd"],
            "presidentes_id"    => ['required', 'array', 'min:1'],
            "secretarios_id"    => ['required', 'array'],
        ];
    }
}
