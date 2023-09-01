<?php

namespace App\Http\Requests;

use App\Models\Propriedade;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePropriedadeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $propriedade = Propriedade::findOrFail($this->route('propriedade'));

        return $this->user()->can('update', $propriedade);
    }

    public function rules()
    {
        return [
            'nome' => [
                'nullable',
                'string',
                'max:60'
            ],
            'rua' => [
                'nullable',
                'regex:/^[a-zA-ZÀ-ÿ\s]+$/',  // mesmo do "name"
                'max:60'
            ],
            'cep' => [
                'nullable',
                'regex:/^\d{5}\-\d{3}$/' // considerando cep no formato "99999-999"
            ],
            'numero' => [
                'nullable',
                'integer',
                'digits_between:1,4'
            ],
            'complemento' => [
                'nullable',
                'string',
                'max:120'
            ],
            'bairro_id' => [
                'nullable',
                'integer',
                'exists:bairros,id'
            ]
        ];
    }

    public function messages()
    {
        return [
            'nullable' => 'O campo :attribute é obrigatório.',
            'max' => 'O campo :attribute deve ter no máximo :max caracteres.',
            'digits_between' => 'O campo :attribute deve ter entre :min e :max dígitos.',
            'string' => 'O campo :attribute deve ser uma string.',
            'integer' => 'O campo :attribute deve ser numérico.',
            'cep.regex' => 'O campo CEP deve estar no formato 99999-999.'
        ];
    }
}
