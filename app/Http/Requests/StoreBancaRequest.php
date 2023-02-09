<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Http\Exceptions\HttpResponseException;

use Illuminate\Contracts\Validation\Validator;

class StoreBancaRequest extends FormRequest
{

    public function rules()
    {
        $rules = [
            'nome' => [
                'required', 'string', 'max:50', 'min:3'
            ],
            'descricao' => [
                'required', 'string', 'max:250'
            ],
            'horario_funcionamento' => [
                'required',
                'date_format:H:i',
                'before:horario_fechamento'
            ],
            'horario_fechamento' => [
                'required',
                'date_format:H:i',
                'after:horario_funcionamento'
            ],
            'preco_minimo' => [
                'required'
            ],
            'tipo_entrega' => [
                'required',
                'in:ENTREGA,RETIRADA'
            ]
        ];

        return $rules;
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json(
                [
                    'success'   => false,
                    'message'   => 'Validation errors',
                    'data'      => $validator->errors()
                ]
            )
        );
    }

    public function messages()
    {
        return [
            'nome.required' => 'Nome é um parâmetro obrigatório.',
            'nome.max' => 'Nome deve ter no máximo 50 caracteres.',
            'nome.min' => 'Nome deve ter no mínimo 3 caracteres.',
            'nome.string' => 'Nome deve ser uma string.',
            'descricao.required' => 'Descrição é um parâmetro obrigatório',
            'descricao.string' => 'Descrição deve ser uma string.',
            'descricao.max' => 'Descrição deve ter no máximo 250 caracteres.',
            'horario_funcionamento.required' => 'Horário de funcionamento é um parâmetro obrigatório.',
            'horario_funcionamento.date_format' => 'Horário de funcionamento deve estar no formato: hora:minuto.',
            'horario_funcionamento.before' => 'Horário de funcionamento deve ser antes do fechamento.',
            'horario_fechamento.required' => 'Horário de fechamento é um parâmetro obrigatório.',
            'horario_fechamento.date_format' => 'Horário de fechamento deve estar no formato: hora:minuto.',
            'horario_fechamento.after' => 'Horário de fechamento deve ser depois da abertura.',
            'preco_minimo.required' => 'Preço mínimo é um parêmtro obrigatório.',
            'tipo_entrega.required' => 'Tipo de entrega deve ser um campo obrigatório',
            'tipo_entrega.in' => 'Tipo de entrega de ser entrega ou retirada'
        ];
    }
}
