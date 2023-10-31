<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreFeiraRequest extends FormRequest
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
        $validDiasSemana = ['domingo', 'segunda', 'terca', 'quarta', 'quinta', 'sexta', 'sabado'];

        $rules = [
            'nome' => [
                'required',
                'regex:/^[a-zA-ZÀ-ÿ\s]+$/',  // regex para validar apenas letras do alfabeto (maiúsculas e minúsculas, com acento ou não) e espaços em branco.
                'min:3',
                'max:60'
            ],
            'descricao' => [
                'required',
                'max:120',
                'string'
            ],
            'imagem' => [
                'nullable',
                'image',
                'max:5120'
            ],
            'horarios_funcionamento' => [
                'required',
                'array',
                'min:1', // Certifica-se de que haja 7 dias da semana
                'max:7',
                'each' => [
                    'array',
                    'size:2', // Certifica-se de que haja 2 horários (abertura e fechamento)
                    Rule::in($validDiasSemana), // Verifica se a chave está em $validDiasSemana
                ],
                'distinct', // Certifica-se de que não existam dias repetidos
            ],
            'horarios_funcionamento.*.0' => [
                'date_format:H:i'
            ],
            'horarios_funcionamento.*.1' => [
                'date_format:H:i',
                'after:horarios_funcionamento.*.0'
            ],
            'bairro_id' => [
                'required',
                'integer',
                'exists:bairros,id'
            ],
            'associacao_id' => [
                'required',
                'integer',
                'exists:associacoes,id'
            ]
        ];

        return $rules;
    }
}
